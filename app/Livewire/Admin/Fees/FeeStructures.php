<?php

namespace App\Livewire\Admin\Fees;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Batch;
use App\Models\FeeStructure;
use App\Models\FeeInstallment;
use App\Models\Institute;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FeeStructures extends Component
{
    use WithPagination;

    public string $search          = '';
    public string $batchFilter     = '';
    public int    $perPage         = 15;
    public bool   $showModal       = false;
    public ?int   $editingId       = null;

    // Form fields
    public string $name                  = '';
    public string $batch_id              = '';
    public string $fee_type              = 'tuition';
    public string $total_amount          = '';
    public bool   $installments_allowed  = false;
    public int    $installment_count     = 2;
    public string $valid_from            = '';
    public string $valid_to              = '';
    public bool   $discount_allowed      = false;
    public float  $max_discount_percent  = 0;
    public string $description           = '';

    protected function rules(): array
    {
        return [
            'name'                 => 'required|string|max:255',
            'batch_id'             => 'required|exists:batches,id',
            'fee_type'             => 'required|in:tuition,registration,exam,material,hostel,other',
            'total_amount'         => 'required|numeric|min:0',
            'installments_allowed' => 'boolean',
            'installment_count'    => 'nullable|integer|min:2|max:12',
            'valid_from'           => 'required|date',
            'valid_to'             => 'nullable|date|after_or_equal:valid_from',
            'discount_allowed'     => 'boolean',
            'max_discount_percent' => 'nullable|numeric|min:0|max:100',
            'description'          => 'nullable|string',
        ];
    }

    public function getBatchesProperty()
    {
        return Batch::active()->orderBy('name')->get();
    }

    public function getFeeStructuresProperty()
    {
        return FeeStructure::with('batch')
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->batchFilter, fn ($q) => $q->where('batch_id', $this->batchFilter))
            ->latest()
            ->paginate($this->perPage);
    }

    public function save(): void
    {
        $this->validate();

        $institute = Institute::first();

        $data = [
            'institute_id'         => $institute->id,
            'batch_id'             => $this->batch_id,
            'name'                 => $this->name,
            'description'          => $this->description,
            'fee_type'             => $this->fee_type,
            'total_amount'         => $this->total_amount,
            'installments_allowed' => $this->installments_allowed,
            'installment_count'    => $this->installments_allowed ? $this->installment_count : null,
            'valid_from'           => $this->valid_from,
            'valid_to'             => $this->valid_to ?: null,
            'discount_allowed'     => $this->discount_allowed,
            'max_discount_percent' => $this->discount_allowed ? $this->max_discount_percent : 0,
            'is_active'            => true,
        ];

        if ($this->editingId) {
            $structure = FeeStructure::findOrFail($this->editingId);
            $structure->update($data);
        } else {
            $structure = FeeStructure::create($data);

            if ($this->installments_allowed && $this->installment_count > 1) {
                $splitAmount   = round($this->total_amount / $this->installment_count, 2);
                $startDate     = Carbon::parse($this->valid_from);

                for ($i = 1; $i <= $this->installment_count; $i++) {
                    FeeInstallment::create([
                        'fee_structure_id'    => $structure->id,
                        'installment_number'  => $i,
                        'name'                => 'Installment ' . $i,
                        'amount'              => $i === $this->installment_count
                            ? $this->total_amount - ($splitAmount * ($this->installment_count - 1))
                            : $splitAmount,
                        'due_date'            => $startDate->copy()->addMonths($i - 1)->toDateString(),
                    ]);
                }
            }
        }

        $this->resetForm();
        session()->flash('success', 'Fee structure saved successfully.');
    }

    public function edit(int $id): void
    {
        $structure = FeeStructure::findOrFail($id);

        $this->editingId             = $id;
        $this->name                  = $structure->name;
        $this->batch_id              = (string) $structure->batch_id;
        $this->fee_type              = $structure->fee_type;
        $this->total_amount          = (string) $structure->total_amount;
        $this->installments_allowed  = $structure->installments_allowed;
        $this->installment_count     = $structure->installment_count ?? 2;
        $this->valid_from            = $structure->valid_from->toDateString();
        $this->valid_to              = $structure->valid_to ? $structure->valid_to->toDateString() : '';
        $this->discount_allowed      = $structure->discount_allowed;
        $this->max_discount_percent  = (float) $structure->max_discount_percent;
        $this->description           = $structure->description ?? '';
        $this->showModal             = true;
    }

    public function delete(int $id): void
    {
        FeeStructure::findOrFail($id)->delete();
        session()->flash('success', 'Fee structure deleted.');
    }

    public function toggleActive(int $id): void
    {
        $structure             = FeeStructure::findOrFail($id);
        $structure->is_active  = ! $structure->is_active;
        $structure->save();
    }

    public function resetForm(): void
    {
        $this->editingId            = null;
        $this->showModal            = false;
        $this->name                 = '';
        $this->batch_id             = '';
        $this->fee_type             = 'tuition';
        $this->total_amount         = '';
        $this->installments_allowed = false;
        $this->installment_count    = 2;
        $this->valid_from           = '';
        $this->valid_to             = '';
        $this->discount_allowed     = false;
        $this->max_discount_percent = 0;
        $this->description          = '';
        $this->resetValidation();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.fees.fee-structures', [
            'feeStructures' => $this->feeStructures,
            'batches'       => $this->batches,
        ])->layout('layouts.admin', ['title' => 'Fee Structures']);
    }
}
