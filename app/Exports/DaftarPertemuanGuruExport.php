<?php

namespace App\Exports;

use App\Models\Kelas;
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

class DaftarPertemuanGuruExport extends DefaultValueBinder implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, WithCustomValueBinder
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $kelas_id;
    public $mapel_id;
    public $jml_siswa;
    public $tanggalAwal, $tanggalAkhir;
    public function __construct($kelas_id, $mapel_id, $jml_siswa, $tanggalAwal, $tanggalAkhir)
    {
        $this->kelas_id = $kelas_id;
        $this->mapel_id = $mapel_id;
        $this->jml_siswa = $jml_siswa;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->tanggalAwal = $tanggalAwal;
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DATETIME,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_NUMBER,
            'K' => NumberFormat::FORMAT_TEXT,
            'L' => NumberFormat::FORMAT_TEXT
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
        $mapel =  MonitoringPembelajaran::where('tanggal', '>=', $this->tanggalAwal)->where('tanggal', '<=', $this->tanggalAkhir)->with('kehadiranPembelajarans')->whereRelation('jadwalPelajaran', 'mata_pelajaran_id', $this->mapel_id)->whereRelation('jadwalPelajaran', 'kelas_id', $this->kelas_id)->get();
        return $mapel->sortBy('tanggal');
    }

    public function map($mapel): array
    {
        return [
            //data yang dari kolom tabel database yang akan diambil
            $mapel->tanggal,
            $mapel->topik,
            substr($mapel->waktu_mulai, 0, -3) . '-' . substr($mapel->waktu_berakhir, 0, -3),
            $this->jml_siswa,
            count($mapel->kehadiranPembelajarans->where('status', 'hadir')),
            count($mapel->kehadiranPembelajarans->where('status', 'izin')),
            count($mapel->kehadiranPembelajarans->where('status', 'sakit')),
            count($mapel->kehadiranPembelajarans->where('status', 'alfa')),
            count($mapel->kehadiranPembelajarans->where('status', 'dinas dalam')),
            count($mapel->kehadiranPembelajarans->where('status', 'dinas luar')),
            $mapel->status_validasi,
            $mapel->keterangan
        ];
    }

    public function headings(): array
    {
        return ['Tanggal', 'Topik', 'Waktu', 'Jml Siswa', 'Hadir', 'Izin', 'Sakit', 'Alfa', 'DD', 'DL', 'Status', 'Keterangan'];
    }
}
