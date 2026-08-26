<?php

namespace App\Exports;

use App\Models\Person;
use App\Models\Region;
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

class AttendanceExport implements FromCollection, WithHeadings, WithStyles
{
    public function __construct($day)
    {
        $this->day = $day;
    }

    public function collection()
    {
        $people = [];

        // Las regiones según la fecha
        switch ( $this->day ) {
            case 1:
                $dates = [ '2024-04-29', '2024-05-06', '2024-05-13', '2024-05-20', '2024-05-27' ];
                break;

            case 2:
                $dates = [ '2024-04-30', '2024-05-07', '2024-05-14', '2024-05-21', '2024-05-28' ];
                break;

            case 3:
                $dates = [ '2024-05-01', '2024-05-08', '2024-05-15', '2024-05-22', '2024-05-29' ];
                break;

            case 4:
                $dates = [ '2024-05-02', '2024-05-09', '2024-05-16', '2024-05-23', '2024-05-30' ];
                break;

            case 5:
                $dates = [ '2024-05-03', '2024-05-10', '2024-05-17', '2024-05-24', '2024-05-31' ];
                break;
            default:
                $dates = [];
        }

        $regions = Region::where('date', 'like', '%' . $dates[0] . '%')
            ->pluck('region')
            ->toArray();

        // Todas las personas que se espera asistencia
        $all_people = Person::whereIn('region', $regions)
                    ->whereIn('type', [ Person::MOVILIZADOR, Person::COORD_ZONA, Person::COORD_SECCION, Person::COORD_REGIONAL ] )
                    ->select('id', 'first_name', 'last_name_1', 'last_name_2', 'created_at', 'phone', 'address', 'region', 'zone', 'section', 'mr', 'type')
                    ->get();
       
        foreach ( $all_people as $p ) {
            $promoted = DB::table('people')
                            ->whereNull('deleted_at')
                            ->where('type', Person::PROMOVIDO)
                            ->where('person_id', $p->id)
                            ->count();

            if ( $promoted > 0 ) {
                $p->promoted = $promoted;
                $people[] = $p;
            }
        }


        $table = [];

        foreach ( $people as $i => $person ) {

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
            $table[$i][10] = $person->promoted; 
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
                'Promovidos',
            ]
           
        ];
    }


    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
    }
}
