<?php

namespace App\Exports;

use App\Models\Guru;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportGuru implements FromView
{
    public function view(): View
    {
        return view('livewire.tables.guru_table', [
            'guru' => Guru::latest()->get()->all(),
            'show' => false
        ]);
    }
}
