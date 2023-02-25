<?php

namespace App\Exports;

use DateTime;
use App\Models\Guru;
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

class RekapGuruExport extends DefaultValueBinder implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, WithCustomValueBinder
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $tanggalAwal;
    public $tanggalAkhir;
    public $kelasAktif;
    public function __construct($tanggalAwal, $tanggalAkhir, $kelasAktif)
    {
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->kelasAktif = $kelasAktif;
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_TEXT,
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
        $guru =  Guru::select('id', 'nama', 'kode_guru')->with(['jadwalPelajarans' => function ($query) {
            $query->select('id', 'waktu_mulai', 'waktu_berakhir', 'guru_id', 'mata_pelajaran_id')->whereIn('kelas_id', $this->kelasAktif)->with(['monitoringPembelajarans' => function ($query) {
                $query->select('id', 'status_validasi', 'jadwal_pelajaran_id', 'waktu_mulai', 'waktu_berakhir')->where('tanggal', '>=', $this->tanggalAwal)->where('tanggal', '<=', $this->tanggalAkhir);
            }])->with(['mataPelajaran' => function ($query) {
                $query->select('id', 'nama');
            }]);
        }])->get();
        return $guru;
    }

    public function map($guru): array
    {
        $diff = new DateTime('00:00');
        foreach ($guru->jadwalPelajarans as $j) {
            $date1 = new DateTime(substr($j->waktu_mulai, 0, -3));
            $date2 = new DateTime(substr($j->waktu_berakhir, 0, -3));
            $diff = $diff->sub($date2->diff($date1));
        }
        foreach ($guru->jadwalPelajarans as $j) {
            $jml = new DateTime('00:00');
            if (count($j->monitoringPembelajarans) !== 0) {
                foreach ($j->monitoringPembelajarans as $m) {
                    if ($m->status_validasi === 'tidak valid') {
                        $date1 = new DateTime(substr($m->waktu_mulai, 0, -3));
                        $date2 = new DateTime(substr($m->waktu_berakhir, 0, -3));
                        $jml = $jml->sub($date2->diff($date1));
                    }
                }
            }
        }
        if ($jml->format('g.i') === '12.00') {
            $persen = '100%';
        } else {
            $data1 = $jml->format('g.i');
            $data2 = $diff->format('g.i');
            $data1int = (int) $data1;
            $data2int = (int) $data2;
            $total = (($data2int - $data1int) / $data2int) * 100;
            $persen = $total . '%';
        }
        return [
            //data yang dari kolom tabel database yang akan diambil
            $guru->nama,
            $guru->kode_guru,
            $guru->jadwalPelajarans->first()->mataPelajaran->nama,
            $diff->format('g.i'),
            $jml->format('g.i') === '12.00' ? '0' : $jml->format('g.i'),
            $persen
        ];
    }

    public function headings(): array
    {
        return ['Nama', 'Kode Guru', 'Mata Pelajaran', 'Jam Wajib', 'Tidak Terlaksana', '% Terlaksana'];
    }
}
