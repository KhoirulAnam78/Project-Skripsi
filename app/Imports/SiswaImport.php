<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
            'nisn' => 'required|numeric|min_digits:10|max_digits:10',
            'nama_siswa' => 'required',
            'status' => 'in:lulus,belum lulus,non aktif',
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
            'status.in' => 'Status tidak diketahui (Harap isi dengan lulus/belum lulus/non aktif) !',
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            DB::transaction(function () use($row) {

                DB::table('users')->updateOrInsert(
                    ['username' => $row['nisn'],],    
                    ['username' => $row['nisn'],
                    'password' => bcrypt($row['nisn']),
                    'role' => 'siswa']
                );
                
                $user = DB::table('users')
                ->select('id')
                ->where('username', $row['nisn'])
                ->first();
                // $user = User::updateOrCreate(
                //     ['username' => $row['nisn'],],    
                //     ['username' => $row['nisn'],
                //     'password' => bcrypt($row['nisn']),
                //     'role' => 'siswa']);
                
                DB::table('siswas')->updateOrInsert(
                    ['nisn' => $row['nisn']],
                    ['nisn' => $row['nisn'],
                    'nama' => $row['nama_siswa'],
                    'status' => $row['status'],
                    'user_id' => $user->id]
                );
                // Siswa::updateOrCreate(
                //     ['nisn' => $row['nisn']],
                //     ['nisn' => $row['nisn'],
                //     'nama' => $row['nama_siswa'],
                //     'status' => $row['status'],
                //     'user_id' => $user->id
                // ]); 
            });
        }
    }
}
