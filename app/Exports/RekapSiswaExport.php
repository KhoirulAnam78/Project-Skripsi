<?php

namespace App\Exports;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
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
        $dataSiswa = DB::table('siswas as a')
                    ->leftjoin('kelas_siswa as b','a.id','b.siswa_id')
                    ->where('b.kelas_id',$this->kelas_id)
                    ->leftjoin('kehadiran_pembelajarans as c','c.siswa_id','a.id')
                    ->leftjoin('monitoring_pembelajaran_news as d','d.monitoring_pembelajaran_id','c.monitoring_pembelajaran_id')
                    ->where('d.tanggal', '>=', $this->tanggalAwal)
                    ->where('d.tanggal', '<=', $this->tanggalAkhir)
                    ->groupBy('a.id')
                    ->select('a.nisn','a.nama',
                        DB::raw("SUM(CASE WHEN c.status = 'hadir' THEN 1 ELSE 0 END) AS hadir"),
                        DB::raw("SUM(CASE WHEN c.status = 'izin' THEN 1 ELSE 0 END) AS izin"),
                        DB::raw("SUM(CASE WHEN c.status = 'sakit' THEN 1 ELSE 0 END) AS sakit"),
                        DB::raw("SUM(CASE WHEN c.status = 'alfa' THEN 1 ELSE 0 END) AS alfa"),
                        DB::raw("SUM(CASE WHEN c.status = 'dinas dalam' THEN 1 ELSE 0 END) AS dd"),
                        DB::raw("SUM(CASE WHEN c.status = 'dinas luar' THEN 1 ELSE 0 END) AS dl"),
                    )
                    ->orderBy('a.nama')
                    ->distinct()
                    ->get();
        return $dataSiswa;
    }

    public function map($s): array
    {
        return [
            //data yang dari kolom tabel database yang akan diambil
            $s->nisn,
            $s->nama,
            $s->hadir,
            $s->izin,
            $s->sakit,
            $s->alfa,
            $s->dd,
            $s->dl
        ];
    }

    public function headings(): array
    {
        return ['NISN', 'Nama', 'Hadir', 'Izin', 'Sakit', 'Alfa', 'DD', 'DL'];
    }
}
