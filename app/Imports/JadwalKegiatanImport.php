<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\Angkatan;
use App\Models\Kegiatan;
use App\Models\MataPelajaran;
use App\Models\JadwalKegiatan;
use App\Models\JadwalPelajaran;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class JadwalKegiatanImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public $kegiatan_id;
    public $waktu_mulai;
    public $angkatan_id;

    public function prepareForValidation(array $row)
    {
        $row['hari'] = ucwords($row['hari']);
        if (Kegiatan::where('nama', $row['kegiatan'])->first()) {
            $this->kegiatan_id = Kegiatan::where('nama', $row['kegiatan'])->first()->id;
            $row['kegiatan_id'] = $this->kegiatan_id;
        } else {
            $this->kegiatan_id = -1;
            $row['kegiatan_id'] = $this->kegiatan_id;
        }
        if (Angkatan::where('nama', $row['angkatan'])->first()) {
            $this->angkatan_id = Angkatan::where('nama', $row['angkatan'])->first()->id;
            $row['angkatan_id'] = $this->angkatan_id;
        } else {
            $this->angkatan_id = -1;
            $row['angkatan_id'] = $this->angkatan_id;
        }
        $this->waktu_mulai = $row['waktu_mulai'];
        return $row;
    }

    public function rules(): array
    {
        return [
            'angkatan_id' => 'required|exists:angkatans,id',
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Setiap Hari|unique:jadwal_kegiatans,hari,NULL,id,angkatan_id,' . $this->angkatan_id . ',kegiatan_id,' . $this->kegiatan_id
        ];
    }

    public function customValidationMessages()
    {
        return [
            'angkatan_id.required' => 'Field Angkatan wajib diisi !',
            'angkatan_id.exists' => 'Angkatan tidak ditemukan disistem !',
            'kegiatan_id.required' => 'Field guru wajib diisi !',
            'kegiatan_id.exists' => 'Kode guru tidak terdaftar !',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi !',
            'waktu_mulai.date_format' => 'Waktu mulai hanya diperbolehkan format waktu !',
            'waktu_berakhir.required' => 'Waktu berakhir wajib diisi !',
            'waktu_berakhir.date_format' => 'Waktu berakhir hanya diperbolehkan format waktu !',
            'waktu_berakhir.after' => 'Waktu berakhir harus lebih besar dari waktu mulai !',
            'hari.required' => 'Hari wajib diisi !',
            'hari.in' => 'Hari hanya dapat diisi (Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Setiap Hari)',
            'hari.unique' => 'Telah ada jadwal pada hari, waktu dan angkatan yang diimport !',
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            JadwalKegiatan::create([
                'angkatan_id' => $this->angkatan_id,
                'kegiatan_id' => $this->kegiatan_id,
                'hari' => $row['hari'],
                'waktu_mulai' => $row['waktu_mulai'],
                'waktu_berakhir' => $row['waktu_berakhir']
            ]);
        }
    }
}
