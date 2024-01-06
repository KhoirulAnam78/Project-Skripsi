<?php

namespace App\Exports;

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

class DaftarPertemuanExport extends DefaultValueBinder implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, WithCustomValueBinder
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $kelas_id;
    public $mapel_id;
    public $jml_siswa;
    public $tanggalAwal;
    public $tanggalAkhir;
    public function __construct($kelas_id, $mapel_id, $jml_siswa, $tanggalAwal, $tanggalAkhir)
    {
        $this->kelas_id = $kelas_id;
        $this->mapel_id = $mapel_id;
        $this->jml_siswa = $jml_siswa;
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DATETIME,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_NUMBER,
            'K' => NumberFormat::FORMAT_NUMBER,
            'L' => NumberFormat::FORMAT_TEXT,
            'M' => NumberFormat::FORMAT_TEXT
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
        $mapel =  $monitoring = DB::table('monitoring_pembelajaran_news as a')
        ->where('a.kelas_id',$this->kelas_id)
        ->where('a.tanggal', '>=', $this->tanggalAwal)
        ->where('a.tanggal', '<=', $this->tanggalAkhir)
        ->where('a.mata_pelajaran_id',$this->mapel_id)
        ->leftJoin('kehadiran_pembelajarans as b','b.monitoring_pembelajaran_id','a.monitoring_pembelajaran_id','left outer')
        ->leftJoin('gurus as c', 'c.id','a.guru_id')
        ->leftjoin('gurus as d','d.id','a.guru_piket_id')
        ->select('a.monitoring_pembelajaran_id','a.tanggal','a.waktu_mulai','a.waktu_berakhir','a.topik','a.status_validasi','c.nama as guru','d.nama as piket', 'a.keterangan',
        DB::raw("SUM(CASE WHEN b.status = 'hadir' THEN 1 ELSE 0 END) AS hadir"),
        DB::raw("SUM(CASE WHEN b.status = 'izin' THEN 1 ELSE 0 END) AS izin"),
        DB::raw("SUM(CASE WHEN b.status = 'sakit' THEN 1 ELSE 0 END) AS sakit"),
        DB::raw("SUM(CASE WHEN b.status = 'alfa' THEN 1 ELSE 0 END) AS alfa"),
        DB::raw("SUM(CASE WHEN b.status = 'dinas dalam' THEN 1 ELSE 0 END) AS dd"),
        DB::raw("SUM(CASE WHEN b.status = 'dinas luar' THEN 1 ELSE 0 END) AS dl"),
        DB::raw("COUNT(b.id) AS total")
        )
        ->groupBy('a.monitoring_pembelajaran_id')
        ->orderBy('a.tanggal','asc')
        ->distinct()
        ->get();
        return $monitoring;
    }

    public function map($monitoring): array
    {
        return [
            //data yang dari kolom tabel database yang akan diambil
            $monitoring->tanggal,
            $monitoring->topik,
            $monitoring->guru,
            substr($monitoring->waktu_mulai, 0, -3) . '-' . substr($monitoring->waktu_berakhir, 0, -3),
            $monitoring->total,
            $monitoring->hadir,
            $monitoring->izin,
            $monitoring->sakit,
            $monitoring->alfa,
            $monitoring->dd,
            $monitoring->dl,
            $monitoring->status_validasi,
            ($monitoring->piket === null) ? 'Admin' : $monitoring->piket,
            $monitoring->keterangan
        ];
    }

    public function headings(): array
    {
        return ['Tanggal', 'Topik', 'Guru', 'Waktu', 'Jml Siswa', 'Hadir', 'Izin', 'Sakit', 'Alfa', 'DD', 'DL', 'Status','Validator', 'Keterangan'];
    }
}
