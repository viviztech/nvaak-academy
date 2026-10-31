<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdmissionDocumentController extends Controller
{
    public function __invoke(Request $request, Admission $admission, string $type): StreamedResponse
    {
        abort_unless(
            $request->user()->hasRole('super-admin')
                || $request->user()->institute_id === $admission->institute_id,
            403
        );

        $path = $admission->documents[$type] ?? null;

        abort_unless(
            is_string($path)
                && str_starts_with($path, 'admissions/'.$admission->application_number.'/')
                && !str_contains($path, '..')
                && Storage::disk('private')->exists($path),
            404
        );

        return Storage::disk('private')->download($path, null, [
            'Cache-Control' => 'private, no-store',
        ]);
    }
}
