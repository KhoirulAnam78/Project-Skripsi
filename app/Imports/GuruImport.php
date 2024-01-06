<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
    public function prepareForValidation(array $row)
    {
        $row['status'] = strtolower($row['status']);
        return $row;
    }
    public function rules(): array
    {
        return [
            'nip' => 'required|numeric|min_digits:18|max_digits:18',
            'nama' => 'required',
            'nomor_telepon' => 'required|max:14|regex:/^([0-9\s\+]*)$/',
            'status' => 'required|in:aktif,tidak aktif',
            'kode_guru' => 'required|min:2|max:2',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nip.required' => 'NIP wajib diisi !',
            'nip.min_digits' => 'NIP harus berisi 18 karakter !',
            'nip.max_digits' => 'NIP lebih dari 18 karakter !',
            'nip.numeric' => 'NIP harus merupakan angka !',
            // 'nip.unique' => 'NIP telah digunakan !',
            'nama.required' => 'Nama wajib diisi !',
            'nomor_telepon.required' => 'No Telp wajib diisi !',
            'nomor_telepon.max' => 'No Telp maksimal 14 karakter number !',
            'nomor_telepon.regex' => 'No Telp merupakan angka dan boleh menggunakan karakter + !',
            'nama.required' => 'Status wajib diisi !',
            'status.in' => 'Status tidak diketahui (Harap isi dengan aktif/tidak aktif) !',
            'kode_guru.required' => 'Kode Guru wajib diisi !',
            'kode_guru.min' => 'Kode Guru harus berisi 2 karakter !',
            'kode_guru.max' => 'Kode Guru harus berisi 2 karakter !',
            // 'kode_guru.unique' => 'Kode Guru telah digunakan !',
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            DB::transaction(function () use ($row) {
                $user = User::updateOrCreate(
                    ['username' => $row['nip']],
                    ['password' => bcrypt($row['nip']),
                    'role' => 'guru']);
                Guru::updateOrCreate(
                    ['nip' => $row['nip']],
                    ['kode_guru' => $row['kode_guru'],
                    'nama' => $row['nama'],
                    'no_telp' => $row['nomor_telepon'],
                    'status' => $row['status'],
                    'user_id' => $user->id,]);
            });
        }
    }
}
