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
            'nisn' => 'required|numeric|min_digits:10|max_digits:10|unique:siswas,nisn',
            'nama_siswa' => 'required',
            'status' => 'in:lulus,belum lulus',
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
            'status.in' => 'Status tidak diketahui (Harap isi dengan lulus/belum lulus) !',
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
                'status' => $row['status'],
                'user_id' => $user->id
            ]);
        }
    }
}
