<?php

namespace App\Exports;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\MonitoringPembelajaran;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class RekapPembelajaranSiswa extends DefaultValueBinder implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, WithCustomValueBinder
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $jadwalId;
    public $tanggalAwal;
    public $tanggalAkhir;
    public $siswaId;
    public function __construct($jadwalId, $tanggalAwal, $tanggalAkhir, $siswaId)
    {
        $this->jadwalId = $jadwalId;
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->siswaId = $siswaId;
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function collection()
    {
        $rekap =  MonitoringPembelajaran::whereIn('jadwal_pelajaran_id', $this->jadwalId)->where('tanggal', '>=', $this->tanggalAwal)->where('tanggal', '<=', $this->tanggalAkhir)->select('id', 'topik', 'jadwal_pelajaran_id', 'tanggal', 'waktu_mulai', 'waktu_berakhir')->with(['kehadiranPembelajarans' => function ($query) {
            $query->where('siswa_id', $this->siswaId)->select('status', 'monitoring_pembelajaran_id', 'siswa_id')->get();
        }])->get();
        return $rekap;
    }

    public function map($rekap): array
    {
        return [
            //data yang dari kolom tabel database yang akan diambil
            $rekap->tanggal,
            $rekap->jadwalPelajaran->mataPelajaran->nama,
            substr($rekap->waktu_mulai, 0, -3) . '-' . substr($rekap->waktu_berakhir, 0, -3),
            $rekap->topik,
            $rekap->kehadiranPembelajarans->first() ? $rekap->kehadiranPembelajarans->first()->status : '-'
        ];
    }

    public function headings(): array
    {
        return ['Tanggal', 'Mata Pelajaran', 'Jam Pelajaran', 'Topik', 'Presensi'];
    }
}
