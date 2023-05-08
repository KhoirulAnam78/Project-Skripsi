<?php

namespace App\Imports;

use App\Models\MataPelajaran;
use Illuminate\Support\Collection;
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
            'nama_mata_pelajaran' => 'required|unique:mata_pelajarans,nama',
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
            MataPelajaran::create([
                'nama' => $row['nama_mata_pelajaran'],
            ]);
        }
    }
}
