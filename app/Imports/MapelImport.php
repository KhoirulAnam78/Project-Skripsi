<?php

namespace App\Imports;

use App\Models\MataPelajaran;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MapelImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function rules(): array
    {
        return [
            'nama_mata_pelajaran' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_mata_pelajaran.required' => 'Mata Pelajaran wajib diisi !',
            'nama_mata_pelajaran.unique' => 'Mata Pelajaran telah ada !',
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            DB::transaction(function () use ($row) {
                MataPelajaran::updateOrCreate(
                    ['nama' => $row['nama_mata_pelajaran']],
                    ['nama' => $row['nama_mata_pelajaran']]
                );
            });
        }
    }
}
