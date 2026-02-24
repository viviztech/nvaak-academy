<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Batch;
use Carbon\Carbon;

class AttendanceReportService
{
    public function getBatchAttendanceReport(int $batchId, string $fromDate, string $toDate): array
    {
        $batch       = Batch::with('students.user')->findOrFail($batchId);
        $workingDays = $this->countWorkingDays(Carbon::parse($fromDate), Carbon::parse($toDate));

        return $batch->students->map(function ($student) use ($batchId, $fromDate, $toDate, $workingDays) {
            $present = Attendance::where('batch_id', $batchId)
                ->where('student_id', $student->id)
                ->whereBetween('date', [$fromDate, $toDate])
                ->whereIn('status', ['present', 'late'])
                ->count();

            $absent = Attendance::where('batch_id', $batchId)
                ->where('student_id', $student->id)
                ->whereBetween('date', [$fromDate, $toDate])
                ->where('status', 'absent')
                ->count();

            $pct = $workingDays > 0 ? round($present / $workingDays * 100, 1) : 0;

            return [
                'student_id'   => $student->id,
                'student_code' => $student->student_code,
                'name'         => $student->user->name,
                'present'      => $present,
                'absent'       => $absent,
                'working_days' => $workingDays,
                'percentage'   => $pct,
                'status'       => $pct >= 75 ? 'good' : ($pct >= 60 ? 'warning' : 'critical'),
            ];
        })->toArray();
    }

    private function countWorkingDays(Carbon $from, Carbon $to): int
    {
        $days = 0;
        $cur  = $from->copy();

        while ($cur->lte($to)) {
            if (! $cur->isSunday()) {
                $days++;
            }
            $cur->addDay();
        }

        return $days;
    }

    public function getStudentAttendanceCalendar(int $studentId, int $batchId, string $month): array
    {
        $start = Carbon::parse($month . '-01')->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        $records = Attendance::where('student_id', $studentId)
            ->where('batch_id', $batchId)
            ->whereBetween('date', [$start, $end])
            ->get()
            ->keyBy(fn ($a) => $a->date->format('Y-m-d'));

        $cal = [];
        $cur = $start->copy();

        while ($cur->lte($end)) {
            $key = $cur->format('Y-m-d');
            $rec = $records->get($key);

            $cal[$key] = [
                'date'      => $key,
                'day'       => $cur->format('d'),
                'weekday'   => $cur->format('D'),
                'status'    => $rec?->status ?? ($cur->isSunday() ? 'holiday' : 'unmarked'),
                'is_sunday' => $cur->isSunday(),
                'is_future' => $cur->isFuture(),
            ];

            $cur->addDay();
        }

        return $cal;
    }
}
