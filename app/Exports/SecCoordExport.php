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

class SecCoordExport implements FromCollection, WithHeadings, WithStyles
{
    public function __construct($region)
    {
        $this->region = $region;
    }

    public function collection()
    {

        $people = DB::table('people')
            ->where('type', Person::COORD_SECCION);

        if ( $this->region != 0 )
            $people = $people->where('region', $this->region);
            
        $people = $people->selectRaw('people.*, date_format(people.created_at, "%d/%m/%Y %H:%i:%s") as f_created_at')
            ->whereNull('deleted_at')
            ->get();

        $people = Tools::setOrder($people);

        $table = [];

        foreach ( $people as $i => $person ) {
            
            $table[$i][0] = $person->f_created_at;
            $table[$i][1] = $person->order;
            $table[$i][2] = Person::getFullName($person); 
            $table[$i][3] = $person->phone;
            $table[$i][4] = $person->address;
            $table[$i][5] = $person->coord_type;
            $table[$i][6] = $person->region;
            $table[$i][7] = $person->zone;
            $table[$i][8] = $person->section;
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
                'Tipo',
                'Región',
                'Zona',
                'Sección',
            ]
           
        ];
    }


    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
    }
}
