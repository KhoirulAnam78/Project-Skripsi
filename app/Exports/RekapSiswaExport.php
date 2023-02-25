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

class RekapSiswaExport extends DefaultValueBinder implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, WithCustomValueBinder
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $kelas_id;
    public $tanggalAwal;
    public $tanggalAkhir;
    public function __construct($kelas_id, $tanggalAwal, $tanggalAkhir)
    {
        $this->kelas_id = $kelas_id;
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
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
        $siswa =  Siswa::whereRelation('kelas', 'kelas_id', $this->kelas_id)->with(['kehadiranPembelajarans' => function ($query) {
            $query->whereRelation('monitoringPembelajaran', 'tanggal', '>=', $this->tanggalAwal)->whereRelation('monitoringPembelajaran', 'tanggal', '<=', $this->tanggalAkhir);
        }])->get();
        return $siswa;
    }

    public function map($siswa): array
    {
        return [
            //data yang dari kolom tabel database yang akan diambil
            $siswa->nisn,
            $siswa->nama,
            count($siswa->kehadiranPembelajarans->where('status', 'hadir')),
            count($siswa->kehadiranPembelajarans->where('status', 'izin')),
            count($siswa->kehadiranPembelajarans->where('status', 'sakit')),
            count($siswa->kehadiranPembelajarans->where('status', 'alfa')),
            count($siswa->kehadiranPembelajarans->where('status', 'dinas dalam')),
            count($siswa->kehadiranPembelajarans->where('status', 'dinas luar')),
        ];
    }

    public function headings(): array
    {
        return ['NISN', 'Nama', 'Hadir', 'Izin', 'Sakit', 'Alfa', 'DD', 'DL'];
    }
}
