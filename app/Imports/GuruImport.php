<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class GuruImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function rules(): array
    {
        return [
            'nip' => 'required|min:18|unique:gurus',
            'nama' => 'required',
            'nomor_telepon' => 'required|max:14',
            'status' => 'required',
            'kode_guru' => 'required|min:2|max:2|unique:gurus',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nip.required' => 'NIP wajib diisi !',
            'nip.min' => 'NIP harus berisi 18 karakter !',
            'nip.unique' => 'NIP telah digunakan !',
            'nama.required' => 'Nama wajib diisi !',
            'nomor_telepon.required' => 'No Telp wajib diisi !',
            'no_telepon.max' => 'No Telp maksimal 14 karakter number !',
            'status.required' => 'Status wajib diisi !',
            'kode_guru.required' => 'Kode Guru wajib diisi !',
            'kode_guru.min' => 'Kode Guru harus berisi 2 karakter !',
            'kode_guru.max' => 'Kode Guru harus berisi 2 karakter !',
            'kode_guru.unique' => 'Kode Guru telah digunakan !',
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $user = User::create([
                'username' => $row['nip'],
                'password' => bcrypt($row['nip']),
                'role' => 'guru'
            ]);
            Guru::create([
                'nip' => $row['nip'],
                'kode_guru' => $row['kode_guru'],
                'nama' => $row['nama'],
                'no_telp' => $row['nomor_telepon'],
                'status' => strToLower($row['status']),
                'user_id' => $user->id,
            ]);
        }
    }
}
