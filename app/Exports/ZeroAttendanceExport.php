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

class ZeroAttendanceExport implements FromCollection, WithHeadings, WithStyles
{
    public function __construct($day, $weeks)
    {
        $this->day = $day;
        $this->weeks = $weeks;
    }

    public function collection()
    {
        $zero_people = [];

        $weeks = explode(',', $this->weeks);

        // Obtiene las fechas
        $dates = Region::getDatesByDay($this->day);

        // Obtiene las regiones
        $regions = Region::where('date', 'like', '%' . $dates[0] . '%')
            ->pluck('region')
            ->toArray();
       
        foreach (  $dates as $i => $date ) {
            
            $week = $i + 1;

            if ( in_array($week, $weeks)  ) { // Si la semana fue seleccionada
                
                $initial_date = $date . ' 00:00:00';
                $final_date = date('Y-m-d', strtotime('+6 days', strtotime($date)));
                $final_date = $final_date . ' 23:59:59';

                // Obtener asistentes en el rango de la semana
                $assistants_by_region = Person::getAssistantsByRegions($initial_date, $final_date, $regions)->toArray();

                $assistants_by_date[] = $assistants_by_region;

                $assistants[] = count($assistants_by_region);

                $person_id[] = array_column($assistants_by_region, 'person_id');
            }
        }

        // Encontrar las coincidencias
        $coincidences = call_user_func_array('array_intersect', $person_id);

        $all_assistants = array_merge(...$person_id);

         // Todas las personas que se espera asistencia
        $all_people = Person::whereIn('region', $regions)
                        ->whereIn('type', [ Person::MOVILIZADOR, Person::COORD_ZONA, Person::COORD_SECCION, Person::COORD_REGIONAL ] )
                        ->pluck('id')
                        ->toArray();

        $inactives_id = array_diff($all_people, $all_assistants);

        $inactives = Person::whereIn('id', $inactives_id)
            ->select('created_at', 'first_name', 'last_name_1', 'last_name_2', 'phone', 'address', 'region', 'zone', 'section', 'mr', 'type')
            ->get();

        $table = [];

        foreach ( $inactives as $i => $person ) {

            $table[$i][0] = Tools::formatTimeYmdToDmy($person->created_at);
            $table[$i][1] = ( $i + 1 );
            $table[$i][2] = Person::getFullName($person); 
            $table[$i][3] = $person->phone;
            $table[$i][4] = $person->address;
            $table[$i][5] = $person->region;
            $table[$i][6] = $person->zone;
            $table[$i][7] = $person->section;
            $table[$i][8] = Person::getType($person->type); 
            $table[$i][9] = '0'; 
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
