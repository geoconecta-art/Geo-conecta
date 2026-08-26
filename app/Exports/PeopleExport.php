<?php

namespace App\Exports;

use App\Models\Person;
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

class PeopleExport implements FromCollection, WithHeadings, WithStyles
{
    public function __construct($date)
    {
        $this->date = $date;
    }

    public function collection()
    {

        $people = DB::table('people')
            ->where('type', Person::PROMOVIDO)
            ->where('created_at', '>=', "$this->date . ' 00:00:00'")
            ->where('created_at', '<=', "$this->date . ' 23:59:59'")
            ->whereNotNull('person_id')
            ->select('person_id', DB::raw('COUNT(*) as promoted_number'))
            ->groupBy('person_id')
            ->get();

        $people = Tools::setOrder($people);

        $table = [];

        foreach ( $people as $i => $p ) {

            $person = Person::find($p->person_id);
            
            $table[$i][0] = Tools::formatTimeYmdToDmy($person->created_at);
            $table[$i][1] = ( $i + 1 );
            $table[$i][2] = Person::getFullName($person); 
            $table[$i][3] = $person->phone;
            $table[$i][4] = $person->address;
            $table[$i][5] = $person->region;
            $table[$i][6] = $person->zone;
            $table[$i][7] = $person->section;
            $table[$i][8] = $person->mr;
            $table[$i][9] = Person::getType($person->type); 
            $table[$i][10] = $p->promoted_number; 
        }
        

        return collect([ $table ]);
    }
   

    public function headings(): array
    {
        return [
            [
                'Fecha de Captura',
                '#',
                'Nombre',
                'Teléfono',
                'Domicilio',
                'Región',
                'Zona',
                'Sección',
                'Microregión',
                'Estructura',
                'Promovidos'
            ]
           
        ];
    }


    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
    }
}
