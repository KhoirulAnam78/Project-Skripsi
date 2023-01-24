<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportRombel implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected $kelas_id;

    public function __construct($id)
    {
        $this->kelas_id = $id;
    }

    public function prepareForValidation(array $row)
    {
        if (Siswa::where('nisn', $row['nisn'])->first()) {
            $row['nisn'] = Siswa::where('nisn', $row['nisn'])->first()->id;
        } else {
            $row['nisn'] = -1;
        }
        return $row;
    }

    public function rules(): array
    {
        return [
            'nisn' => 'required|exists:siswas,id|unique:kelas_siswa,siswa_id,NULL,id,kelas_id,' . $this->kelas_id,
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nisn.required' => 'Nama kelas wajib diisi !',
            'nisn.exists' => 'Nisn siswa tidak terdaftar !',
            'nisn.unique' => 'Siswa Sudah berada pada rombongan belajar ini !'
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // $siswa_id = Siswa::where('id', $row['nisn'])->first()->id;
            Rombel::create([
                'siswa_id' => $row['nisn'],
                'kelas_id' => $this->kelas_id
            ]);
        }
    }
}
