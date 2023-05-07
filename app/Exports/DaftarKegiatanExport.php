<?php

namespace App\Exports;

use App\Models\Kelas;
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

class DaftarKegiatanExport extends DefaultValueBinder implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, WithCustomValueBinder
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $filterAngkatan;
    public $tglAwal;
    public $tglAkhir;
    public $jml_siswa;
    public $kegiatan_id;
    public function __construct($filterAngkatan, $jml_siswa, $tglAwal, $tglAkhir, $kegiatan_id)
    {
        $this->filterAngkatan = $filterAngkatan;
        $this->tglAwal = $tglAwal;
        $this->tglAkhir = $tglAkhir;
        $this->jml_siswa = $jml_siswa;
        $this->kegiatan_id = $kegiatan_id;
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DATETIME,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_NUMBER,
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
        $daftar =  MonitoringKegiatan::where('tanggal', '>=', $this->tglAwal)->where('tanggal', '<=', $this->tglAkhir)->with('kehadiranKegiatan')->whereRelation('jadwalKegiatan', 'angkatan_id', $this->filterAngkatan)->whereRelation('jadwalKegiatan', 'kegiatan_id', $this->kegiatan_id)->get();
        return $daftar->sortBy('tanggal');
    }

    public function map($daftar): array
    {
        return [
            //data yang dari kolom tabel database yang akan diambil
            $daftar->tanggal,
            substr($daftar->waktu_mulai, 0, -3) . '-' . substr($daftar->waktu_berakhir, 0, -3),
            $this->jml_siswa,
            count($daftar->kehadiranKegiatan->where('status', 'hadir')),
            count($daftar->kehadiranKegiatan->where('status', 'izin')),
            count($daftar->kehadiranKegiatan->where('status', 'sakit')),
            count($daftar->kehadiranKegiatan->where('status', 'alfa')),
            count($daftar->kehadiranKegiatan->where('status', 'dinas dalam')),
            count($daftar->kehadiranKegiatan->where('status', 'dinas luar')),
        ];
    }

    public function headings(): array
    {
        return ['Tanggal', 'Waktu', 'Jml Siswa', 'Hadir', 'Izin', 'Sakit', 'Alfa', 'DD', 'DL'];
    }
}
