<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
    protected $akademik;

    public function __construct($id)
    {
        $this->akademik = $id;
    }

    // public function prepareForValidation(array $row)
    // {
    //     if (Siswa::where('nisn', $row['nisn'])->first()) {
    //         $row['nisn'] = Siswa::where('nisn', $row['nisn'])->first()->id;
    //     } else {
    //         $row['nisn'] = -1;
    //     }
    //     if (Kelas::where('nama', $row['kelas'])->first()) {
    //         $row['kelas'] = Kelas::where('kelas', $row['kelas'])->first()->id;
    //     } else {
    //         $row['kelas'] = -1;
    //     }
    //     return $row;
    // }

    public function rules(): array
    {
        return [
            'nisn' => 'required|exists:siswas,nisn',
            'kelas' => 'required|exists:kelas,nama'
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nisn.required' => 'Nisn wajib diisi !',
            'nisn.exists' => 'Nisn siswa tidak terdaftar !',
            'nisn.unique' => 'Siswa Sudah berada pada rombongan belajar ini !',
            'kelas.exists' => 'Kelas tidak diketahui !',
            'kelas.required' => 'Nama kelas wajib diisi!'
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $siswa_id = Siswa::where('nisn', $row['nisn'])->pluck('id')->first();
            $kelas_id = Kelas::where('nama', $row['kelas'])->pluck('id')->first();
            DB::transaction(function () use ($siswa_id,$kelas_id) {
                Rombel::updateOrCreate(
                [
                    'siswa_id' => $siswa_id,
                    'tahun_akademik_id' => $this->akademik
                ],
                [
                    'kelas_id' => $kelas_id,
                ]);
            });
        }
    }
}
