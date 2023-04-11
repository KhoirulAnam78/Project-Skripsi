<?php

namespace App\Exports;

use App\Models\Narasumber;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class NarasumberExport extends DefaultValueBinder implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, WithCustomValueBinder
{
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
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
        return Narasumber::all();
    }

    public function map($narasumber): array
    {
        return [
            //data yang dari kolom tabel database yang akan diambil
            $narasumber->nama,
            $narasumber->no_telp,
            $narasumber->instansi,
        ];
    }

    public function headings(): array
    {
        return ['Nama', 'Nomor Telepon', 'Instansi'];
    }
}
