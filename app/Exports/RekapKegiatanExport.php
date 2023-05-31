<?php

namespace App\Exports;

use App\Models\Kelas;
use App\Models\Siswa;
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

class RekapKegiatanExport extends DefaultValueBinder implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, WithCustomValueBinder
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $kelas_id;
    public $tanggalAwal;
    public $tanggalAkhir;
    public $kegiatan_id;
    public $monitoringArray, $tahunAkademik;
    public function __construct($kelas_id, $tanggalAwal, $tanggalAkhir, $kegiatan_id, $tahunAkademik)
    {
        $this->kelas_id = $kelas_id;
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->kegiatan_id = $kegiatan_id;
        $this->tahunAkademik = $tahunAkademik;
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
        $angkatan_id = Kelas::find($this->kelas_id)->angkatan_id;

        $monitoring = MonitoringKegiatan::where('tanggal', '>=', $this->tanggalAwal)->where('tanggal', '<=', $this->tanggalAkhir)->whereRelation('jadwalKegiatan', 'kegiatan_id', $this->kegiatan_id)->whereRelation('jadwalKegiatan', 'angkatan_id', $angkatan_id)->whereRelation('jadwalKegiatan', 'tahun_akademik_id', $this->tahunAkademik)->get();
        $this->monitoringArray = [];
        if (count($monitoring) !== 0) {
            foreach ($monitoring as $m) {
                array_push($this->monitoringArray, $m->id);
            }
        }
        $presensi = Siswa::whereRelation('kelas', 'kelas_id', $this->kelas_id)->with(['kehadiranKegiatan' => function ($query) {
            $query->whereIn('monitoring_kegiatan_id', $this->monitoringArray);;
        }])->orderBy('nama', 'asc')->get();
        return $presensi;
    }

    public function map($siswa): array
    {
        return [
            //data yang dari kolom tabel database yang akan diambil
            $siswa->nisn,
            $siswa->nama,
            count($siswa->kehadiranKegiatan->where('status', 'hadir')),
            count($siswa->kehadiranKegiatan->where('status', 'izin')),
            count($siswa->kehadiranKegiatan->where('status', 'sakit')),
            count($siswa->kehadiranKegiatan->where('status', 'alfa')),
            count($siswa->kehadiranKegiatan->where('status', 'dinas dalam')),
            count($siswa->kehadiranKegiatan->where('status', 'dinas luar')),
        ];
    }

    public function headings(): array
    {
        return ['NISN', 'Nama', 'Hadir', 'Izin', 'Sakit', 'Alfa', 'DD', 'DL'];
    }
}
