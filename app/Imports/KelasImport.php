<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\TahunAkademik;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class KelasImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public $tahun_akademik_id, $nama;

    public function prepareForValidation(array $row)
    {
        $this->nama = $row['nama_kelas'];;
        $akademik = TahunAkademik::where('nama', 'like', '%' . $row['tahun_akademik'] . '%')->first();

        if ($akademik !== null) {
            $this->tahun_akademik_id = $akademik->id;
        } else {
            $this->tahun_akademik_id = '-1';
        }
        $row['tahun_akademik'] = $this->tahun_akademik_id;
        return $row;
    }
    public function rules(): array
    {
        return [
            'nama_kelas' => 'required|unique:kelas,nama,NULL,id,tahun_akademik_id,' . $this->tahun_akademik_id,
            'tahun_akademik' => 'required|exists:kelas,id|unique:kelas,tahun_akademik_id,NULL,id,nama,' . $this->nama
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_kelas.required' => 'Nama kelas wajib diisi !',
            'nama_kelas.unique' => 'Nama kelas pada tahun akademik ini sudah ada !',
            'tahun_akademik.required' => 'Tahun akademik wajib diisi !',
            'tahun_akademik.unique' => 'Nama kelas pada tahun akademik ini sudah ada !',
            'tahun_akademik.exists' => 'Nama tahun akademik tidak ada didalam sistem !',
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Kelas::create([
                'nama' => $this->nama,
                'tahun_akademik_id' => $this->tahun_akademik_id
            ]);
        }
    }
}
