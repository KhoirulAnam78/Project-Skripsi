<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\MataPelajaran;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class JadwalPelajaranImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public $hari;
    public $kelas;
    public $waktu_mulai;
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
        if (MataPelajaran::where('nama', $row['mata_pelajaran'])->first()) {
            $row['mata_pelajaran_id'] = MataPelajaran::where('nama', $row['mata_pelajaran'])->first()->id;
        } else {
            $row['mata_pelajaran_id'] = -1;
        }
        $this->waktu_mulai = $row['waktu_mulai'];
        return $row;
    }

    public function __construct($kelas)
    {
        $this->kelas = $kelas;
    }

    public function rules(): array
    {
        return [
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'guru_id' => 'required|exists:gurus,id',
            'waktu_mulai' => 'required|date_format:H:i|unique:jadwal_pelajarans,waktu_mulai,NULL,id,hari,' . $this->hari . ',kelas_id,' . $this->kelas,
            'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu|unique:jadwal_pelajarans,hari,NULL,id,kelas_id,' . $this->kelas . ',waktu_mulai,' . $this->waktu_mulai
        ];
    }

    public function customValidationMessages()
    {
        return [
            'mata_pelajaran_id.required' => 'Field mata pelajaran wajib diisi !',
            'mata_pelajaran_id.exists' => 'Mata pelajaran tidak ditemukan disistem !',
            'guru_id.required' => 'Field guru wajib diisi !',
            'guru_id.exists' => 'Kode guru tidak terdaftar !',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi !',
            'waktu_mulai.date_format' => 'Waktu mulai hanya diperbolehkan format waktu !',
            'waktu_mulai.unique' => 'Telah ada jadwal pada hari, waktu dan kelas yang diimport !',
            'waktu_berakhir.required' => 'Waktu berakhir wajib diisi !',
            'waktu_berakhir.date_format' => 'Waktu berakhir hanya diperbolehkan format waktu !',
            'waktu_berakhir.after' => 'Waktu berakhir harus lebih besar dari waktu mulai !',
            'hari.required' => 'Hari wajib diisi !',
            'hari.unique' => 'Telah ada jadwal pada hari, waktu dan kelas yang diimport !',
            'file.required' => 'File tidak boleh kosong',
            'file.mimes' => 'File harus memiliki format excel(.xlxs/.xls)'
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            JadwalPelajaran::create([
                'kelas_id' => $this->kelas,
                'mata_pelajaran_id' => $row['mata_pelajaran_id'],
                'guru_id' => $row['guru_id'],
                'hari' => $row['hari'],
                'waktu_mulai' => $row['waktu_mulai'],
                'waktu_berakhir' => $row['waktu_berakhir']
            ]);
        }
    }
}
