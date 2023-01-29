<?php

namespace App\Imports;

use App\Models\Guru;
use Illuminate\Support\Carbon;
use App\Models\JadwalGuruPiket;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportJadwalPiket implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public $hari;
    public $guru_id;

    public function prepareForValidation(array $row)
    {
        $row['hari'] = ucfirst($row['hari']);
        $this->hari = $row['hari'];
        if (Guru::where('kode_guru', $row['kode_guru'])->first()) {
            $row['guru_id'] = Guru::where('kode_guru', $row['kode_guru'])->first()->id;
        } else {
            $row['guru_id'] = -1;
        }

        $this->guru_id = $row['guru_id'];
        return $row;
    }


    public function rules(): array
    {
        return [
            'guru_id' => 'required|exists:gurus,id',
            'waktu_mulai' => 'required|date_format:H:i|unique:jadwal_guru_pikets,waktu_mulai,NULL,id,hari,' . $this->hari,
            'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu|unique:jadwal_guru_pikets,hari,NULL,id,guru_id,' . $this->guru_id
        ];
    }

    public function customValidationMessages()
    {
        return [
            'guru_id.required' => 'Field guru wajib diisi !',
            'guru_id.exists' => 'Kode guru tidak terdaftar!',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi !',
            'waktu_mulai.date_format' => 'Hanya diperbolehkan format waktu !',
            'waktu_mulai.unique' => 'Jadwal piket pada waktu ini sudah ada !',
            'waktu_berakhir.required' => 'Waktu berakhir wajib diisi !',
            'waktu_berakhir.date_format' => 'Hanya diperbolehkan format waktu !',
            'waktu_berakhir.after' => 'Waktu berakhir harus lebih besar dari waktu mulai !',
            'hari.required' => 'Hari wajib diisi !',
            'hari.unique' => 'Guru telah piket pada hari yang dipilih',
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            JadwalGuruPiket::create([
                'guru_id' => $row['guru_id'],
                'hari' => $row['nama_siswa'],
                'waktu_mulai' => $row['waktu_mulai'],
                'waktu_berakhir' => $row['waktu_berakhir']
            ]);
        }
    }
}
