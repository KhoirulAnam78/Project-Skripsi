<?php

namespace App\Exports;

use App\Models\JadwalKegiatan;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class JadwalKegiatanExport extends DefaultValueBinder implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, WithCustomValueBinder
{
    public $tahun_akademik_id, $angkatan_id;
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT
        ];
    }

    public function __construct($tahun_akademik_id, $angkatan_id)
    {
        $this->tahun_akademik_id = $tahun_akademik_id;
        $this->angkatan_id = $angkatan_id;
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
        return JadwalKegiatan::where('angkatan_id', 'like', '%' . $this->angkatan_id . '%')->where('tahun_akademik_id', $this->tahun_akademik_id)->latest()->get();
        // JadwalKegiatan::with('angkatan')->with('kegiatan')->get();
    }

    public function map($jadwal): array
    {
        return [
            //data yang dari kolom tabel database yang akan diambil
            $jadwal->angkatan->nama,
            $jadwal->kegiatan->nama,
            $jadwal->hari,
            substr($jadwal->waktu_mulai, 0, -3),
            substr($jadwal->waktu_berakhir, 0, -3),
        ];
    }

    public function headings(): array
    {
        return ['Angkatan', 'Kegiatan', 'Hari', 'Waktu Mulai', 'Waktu Berakhir'];
    }
}
