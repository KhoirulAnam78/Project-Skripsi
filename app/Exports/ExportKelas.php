<?php

namespace App\Exports;

use App\Models\Kelas;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class ExportKelas extends DefaultValueBinder implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, WithCustomValueBinder
{
    public $id;
    public function __construct($id)
    {
        $this->id = $id;
        // dd($this->id);
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
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
        if ($this->id === '') {
            return Kelas::all();
        } else {
            return Kelas::where('tahun_akademik_id', $this->id)->get();
        }
    }

    public function map($kelas): array
    {
        return [
            //data yang dari kolom tabel database yang akan diambil
            $kelas->nama,
        ];
    }

    public function headings(): array
    {
        return ['Nama Kelas'];
    }
}
