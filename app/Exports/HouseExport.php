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


class HouseExport implements FromCollection, WithHeadings, WithStyles
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
            $table[$i][2] = Person::getFullName($person); 
            $table[$i][3] = $person->phone;
            $table[$i][4] = $person->address;
            $table[$i][5] = $person->section;
            $table[$i][6] = $person->region;
            $table[$i][7] = $person->lat;
            $table[$i][8] = $person->lng;
            $table[$i][9] = $person->note;
        }

        return collect([ $table ]);
    }
   

    public function headings(): array
    {
        return [
            [
                'Fecha de Captura',
                '#',
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
        $sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
    }

   

    
   
}
