<?php

namespace App\Presentation\Livewire\Admin\Table;

use App\Application\Actions\Table\CreateTableAction;
use App\Application\Actions\Table\UpdateTableAction;
use App\Domain\Table\Models\Table;
use Livewire\Component;

class TableForm extends Component
{
    public ?int $tableId = null;
    public string $table_number = '';
    public ?bool $is_active = true;
    public string $status = 'available';

    protected function rules()
    {
        $outletId = auth()->user()->outlet_id ?? 1;
        $uniqueRule = \Illuminate\Validation\Rule::unique('tables', 'table_number')->where('outlet_id', $outletId);
        
        if ($this->tableId) {
            $uniqueRule->ignore($this->tableId);
        }

        return [
            'table_number' => ['required', 'string', 'max:50', $uniqueRule],
            'is_active' => 'boolean',
            'status' => 'required|in:available,occupied',
        ];
    }

    protected $messages = [
        'table_number.required' => 'Nomor meja tidak boleh kosong.',
        'table_number.unique' => 'Nomor meja ini sudah ada.',
        'status.in' => 'Status tidak valid.',
    ];

    public function mount($tableId = null)
    {
        if ($tableId) {
            $table = Table::findOrFail($tableId);
            $this->tableId = $table->id;
            $this->table_number = $table->table_number;
            $this->is_active = $table->is_active ?? true;
            $this->status = $table->status ?? 'available';
        }
    }

    public function save(CreateTableAction $createAction, UpdateTableAction $updateAction)
    {
        $this->validate();

        $data = [
            'table_number' => $this->table_number,
            'is_active' => (bool) ($this->is_active ?? true),
            'status' => $this->status ?: 'available',
            'outlet_id' => auth()->user()->outlet_id ?? 1, // Placeholder jika single-outlet
        ];

        if ($this->tableId) {
            $updateAction->execute($this->tableId, $data);
            session()->flash('message', 'Meja berhasil diupdate!');
        } else {
            $createAction->execute($data);
            session()->flash('message', 'Meja berhasil ditambahkan!');
        }

        session()->flash('message_type', 'success');
        return redirect()->route('admin.table.index');
    }

    public function render()
    {
        return view('livewire.admin.table.table-form')->layout('layouts.admin');
    }
}
