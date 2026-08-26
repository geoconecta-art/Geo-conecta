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

class BoothsExport implements FromCollection, WithHeadings, WithStyles
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
            $table[$i][3] = strval($b->pan);
            $table[$i][4] = strval($b->pri);
            $table[$i][5] = strval($b->prd);
            $table[$i][6] = strval($b->verde);
            $table[$i][7] = strval($b->pt);
            $table[$i][8] = strval($b->mc);
            $table[$i][9] = strval($b->morena);
            $table[$i][10] = strval($b->na);

            $table[$i][11] = strval($b->pan_pri_prd_na);
            $table[$i][12] = strval($b->pan_pri_prd);
            $table[$i][13] = strval($b->pan_pri_na);
            $table[$i][14] = strval($b->pan_prd_na);

            $table[$i][15] = strval($b->pan_pri);
            $table[$i][16] = strval($b->pan_prd);
            $table[$i][17] = strval($b->pan_na);

            $table[$i][18] = strval($b->pri_prd_na);
            $table[$i][19] = strval($b->pri_prd);
            $table[$i][20] = strval($b->pri_na);
            $table[$i][21] = strval($b->prd_na);

            $table[$i][22] = strval($b->morena_verde_pt);
            $table[$i][23] = strval($b->verde_pt);
            $table[$i][24] = strval($b->verde_morena);
            $table[$i][25] = strval($b->pt_morena);
            $table[$i][26] = strval($b->otros);
            $table[$i][27] = strval($b->nulos);

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
                'PAN PRI PRD NA',
                'PAN PRI PRD',
                'PAN PRI NA',
                'PAN PRD NA',

                'PAN PRI',
                'PAN PRD',

                'PAN NA',
                'PRI PRD NA',
                'PRI PRD',
                'PRI NA',
                'PRD NA',
                'MORENA VERDE PT',
                'VERDE PT',
                'VERDE MORENA',
                'PT MORENA',
                'OTROS',
                'NULOS',
            ]
           
        ];
    }


    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:AB1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:AB1')->getFont()->setBold(true);
    }



   
}
