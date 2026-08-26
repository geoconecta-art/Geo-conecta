<?php

namespace App\Exports;

use App\Models\Person;
use App\Models\Section;
use App\Tools\Tools;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class HouseImageExport implements FromCollection, WithHeadings, WithStyles, WithDrawings, WithEvents
{
    public function __construct()
    {
        $this->people = [];
    }

    public function collection()
    {
        $people = DB::table('people')
                    ->whereNull('people.deleted_at')
                    ->where('people.type', Person::CASAD)
                    ->selectRaw('people.*, date_format(people.created_at, "%d/%m/%Y %H:%i:%s") as f_created_at')
                    ->get();

        $people = Tools::setOrder($people);

        $this->people = $people;

        $table = [];

        foreach ( $people as $i => $person ) {

            $table[$i][0] = $person->f_created_at;
            $table[$i][1] = $person->order;
            $table[$i][2] = '';
            $table[$i][3] = Person::getFullName($person); 
            $table[$i][4] = $person->phone;
            $table[$i][5] = $person->address;
            $table[$i][6] = $person->section;
            $table[$i][7] = $person->region;
            $table[$i][8] = $person->lat;
            $table[$i][9] = $person->lng;
            $table[$i][10] = $person->note;
        }

        return collect([ $table ]);
    }
   

    public function headings(): array
    {
        return [
            [
                'Fecha de Captura',
                '#',
                'Imagen',
                'Responsable',
                'Teléfono',
                'Domicilio',
                'Sección',
                'Región',
                'Latitud',
                'Longitud',
                'Notas'
            ]
           
        ];
    }


    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
    }

    public function drawings()
    {
        $drawings = [];

        foreach ( $this->people as $key => $p ) {
            $drawing = new Drawing();
            $drawing->setName('Imagen');
            $drawing->setDescription('Imagen de la casa');
            $drawing->setPath(public_path($p->image)); // Ajusta la ruta según tu configuración
            $drawing->setHeight(100);
            $drawing->setCoordinates('C' . ($key + 2)); // Ajusta la celda según tu necesidad
            $drawings[] = $drawing;
        }

        return $drawings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                foreach ( $this->people as $key => $p ) {
                    $row = $key + 2; // Ajusta el índice según corresponda
                    $event->sheet->getDelegate()->getRowDimension($row)->setRowHeight(100); // Ajusta la altura de la fila

                    // Centrar verticalmente el contenido de la celda
                    $event->sheet->getDelegate()->getStyle('A' . $row . ':K' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
               
                }
            },
        ];
    }

   
}
