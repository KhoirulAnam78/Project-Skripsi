<?php

namespace App\Imports;

use App\Models\User;
use App\Models\WaliAsrama;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class WaliAsramaImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function prepareForValidation(array $row)
    {
        $row['status'] = strtolower($row['status']);
        return $row;
    }
    public function rules(): array
    {
        return [
            'nama' => 'required',
            'nomor_telepon' => 'required|max:14',
            'status' => 'in:aktif,tidak aktif',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama.required' => 'Nama wajib diisi !',
            'nomor_telepon.required' => 'No Telp wajib diisi !',
            'nomor_telepon.max' => 'No Telp maksimal 14 karakter number !',
            'status.in' => 'Status tidak diketahui (Harap isi dengan aktif/tidak aktif) !',
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $user = User::create([
                'username' => Str::slug($row['nama']),
                'password' => bcrypt('monitoring2023'),
                'role' => 'wali_asrama'
            ]);
            WaliAsrama::create([
                'nama' => $row['nama'],
                'no_telp' => $row['nomor_telepon'],
                'status' => $row['status'],
                'user_id' => $user->id,
            ]);
        }
    }
}
