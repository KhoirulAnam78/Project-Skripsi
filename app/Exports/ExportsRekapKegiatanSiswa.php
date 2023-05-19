<?php

namespace App\Exports;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\MonitoringKegnas;
use App\Models\MonitoringKegiatan;
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

class ExportsRekapKegiatanSiswa extends DefaultValueBinder implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, WithCustomValueBinder
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
        $rekap =  MonitoringKegiatan::whereIn('jadwal_kegiatan_id', $this->jadwalId)->where('tanggal', '>=', $this->tanggalAwal)->where('tanggal', '<=', $this->tanggalAkhir)->with('narasumber')->with(['kehadiranKegiatan' => function ($query) {
            if ($query) {
                $query->where('siswa_id', $this->siswaId);
            } else {
                $query;
            }
        }])->get();
        return $rekap;
    }

    public function map($rekap): array
    {
        return [
            //data yang dari kolom tabel database yang akan diambil
            $rekap->tanggal,
            substr($rekap->waktu_mulai, 0, -3) . '-' . substr($rekap->waktu_berakhir, 0, -3),
            $rekap->kehadiranKegnas->first()->status
        ];
    }

    public function headings(): array
    {
        return ['Tanggal', 'Waktu', 'Presensi'];
    }
}
