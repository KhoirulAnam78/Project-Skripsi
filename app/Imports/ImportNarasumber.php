<?php

namespace App\Imports;

use App\Models\Narasumber;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportNarasumber implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function rules(): array
    {
        return [
            'nama' => 'required',
            'nomor_telepon' => 'required|max:14|regex:/^([0-9\s\+]*)$/',
            'instansi' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'instansi.required' => 'Instansi wajib diisi !',
            'nama.required' => 'Nama wajib diisi !',
            'nomor_telepon.required' => 'No Telp wajib diisi !',
            'nomor_telepon.max' => 'No Telp maksimal 14 karakter number !',
            'nomor_telepon.regex' => 'No Telp merupakan angka dan boleh menggunakan karakter + !',
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            DB::transaction(function () use ($row) {
                Narasumber::updateOrCreate(
                [
                    'nama' => $row['nama'],
                ],
                [
                    'nama' => $row['nama'],
                    'no_telp' => $row['nomor_telepon'],
                    'instansi' => $row['instansi'],
                ]);
            });
        }
    }
}
