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

class QuickBoothsExport implements FromCollection, WithHeadings, WithStyles
    /*, WithDrawings, WithEvents*/
{
    public function __construct()
    {
        $this->booths = [];
    }

    public function collection()
    {
        $this->booths = DB::table('booths')->get();

        $table = [];

        foreach ( $this->booths as $i => $b ) {

            $table[$i][0] = $b->section;
            $table[$i][1] = $b->type;
            $table[$i][2] = $b->letters;
            $table[$i][3] = strval($b->pan_2);
            $table[$i][4] = strval($b->pri_2);
            $table[$i][5] = strval($b->prd_2);
            $table[$i][6] = strval($b->verde_2);
            $table[$i][7] = strval($b->pt_2);
            $table[$i][8] = strval($b->mc_2);
            $table[$i][9] = strval($b->morena_2);
            $table[$i][10] = strval($b->na_2);
            $table[$i][11] = strval($b->nulos_2);

        }

        return collect([ $table ]);
    }
   

    public function headings(): array
    {
        return [
            [
                'SECION',
                'TIPO',
                'APELLIDOS',
                'PAN',
                'PRI',
                'PRD',
                'VERDE',
                'PT',
                'MC',
                'MORENA',
                'NA',
                'NULOS',
            ]
           
        ];
    }


    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:L1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:L1')->getFont()->setBold(true);
    }



   
}
