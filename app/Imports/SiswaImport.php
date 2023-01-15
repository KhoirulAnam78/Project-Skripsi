<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SiswaImport implements ToCollection, WithHeadingRow, WithValidation
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
            'nisn' => 'required|numeric|min_digits:10|max_digits:10|unique:siswas',
            'nama_siswa' => 'required',
            'nomor_telepon' => 'required|max:14|regex:/^([0-9\s\+]*)$/',
            'status' => 'in:aktif,tidak aktif',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nisn.required' => 'NISN wajib diisi !',
            'nisn.min_digits' => 'NISN harus berisi 10 karakter !',
            'nisn.max_digits' => 'NISN lebih dari 10 karakter !',
            'nisn.numeric' => 'NISN harus merupakan angka !',
            'nisn.unique' => 'NISN telah digunakan !',
            'nama_siswa.required' => 'Nama wajib diisi !',
            'nomor_telepon.required' => 'No Telp wajib diisi !',
            'nomor_telepon.max' => 'No Telp maksimal 14 karakter angka (numeric) !',
            'nomor_telepon.regex' => 'No Telp merupakan angka dan boleh menggunakan karakter + !',
            'status.in' => 'Status tidak diketahui (Harap isi dengan aktif/tidak aktif) !',
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $user = User::create([
                'username' => $row['nisn'],
                'password' => bcrypt($row['nisn']),
                'role' => 'siswa'
            ]);
            Siswa::create([
                'nisn' => $row['nisn'],
                'nama' => $row['nama_siswa'],
                'no_telp' => $row['nomor_telepon'],
                'status' => $row['status'],
                'user_id' => $user->id
            ]);
        }
    }
}
