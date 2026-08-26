<?php

namespace App\Exports;

use App\Models\Plan;
use App\Models\PlanRegister;
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
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class InventaryExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $id_plan;
    protected $headers;
    protected $numCols;

    public function __construct($id_plan, $headers, $numCols){
        $this->id_plan = $id_plan;
        $this->headers = $headers;
        $this->numCols = $numCols;
    }

    public function collection() {
        $rows = PlanRegister::whereNull('deleted_at')->where('id_plan', $this->id_plan)->get();
        $plan = Plan::find($this->id_plan);
        $table = [];

        foreach ( $rows as $row) {
            $data = self::getRowValues( $row['attributes'], $row, $plan->attributes, $plan, "gen" );
            $table[] = $data;
        }

        return collect([ $table ]);
    }

    private function getRowValues( $rowVal, $row, $attrs, $plan, $suffix ){
        $rowValues = [];
        
        foreach ($attrs as $attrKey => $attrVal) {
            if( is_array( $attrVal ) ){
                if( $attrVal['type'] != 'button' && !isset( $attrVal['deleted_at'] ) ){
                    $index = trim( str_replace(" ", "", str_replace(".", "_", $attrVal['title'] ) ) . '_' . $attrVal['type'] . '_' . $attrKey . '_' . $suffix . '_attr' );
                    $rowValues[ $index ] = $rowVal[ $index ] ?? "";
                }
            } else {
                if( $attrKey != "editable"){

                    $matches = explode("_", $attrVal);
                    $geoItems = [];
                    $geoValues = [];

                    if( count($matches) == 2 ){
                        $geoKey = $matches[0];
                        $geoIndex = '_' . $matches[1];
                        $geoItems = $plan->$geoKey[$geoIndex];
                        $geoValues = $row->$geoKey[$geoIndex];
                    } else {
                        $geoItems = $plan->$attrVal;
                        $geoValues = $row->$attrVal;
                    }

                    $rowValues = array_merge( $rowValues, self::getRowValues( $geoValues, $row, $geoItems, $plan, $attrVal) );
                }
            }
        }

        return $rowValues;
    }

    public function headings(): array {
        return $this->headers;
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function( AfterSheet $event ){
                $this->insertImages( $event->sheet->getDelegate() );
            }
        ];
    }

    // Genera los estilos para los enlaces y encabezados
    public function styles(Worksheet $sheet) {
        $collection = $this->collection();
        $rowIndex = 2;
    
        foreach ($collection as $rows) {
            foreach ($rows as $row) {
                $colIndex = 1;
                foreach ($row as $key => $cellValue) {
                    $aux = "";
    
                    if( str_contains($key, "_file_") ){
                        $aux = asset( rawurlencode($cellValue) );
                    }
    
                    if ( filter_var($aux, FILTER_VALIDATE_URL) ) {
                        $aux = route( 'download.file.from.excel') . "?file_path=" . rawurlencode($cellValue);
                        // Establece el texto "Ver archivo" en la celda
                        $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex, 'Ver archivo');
                        
                        // Establece el hipervínculo en la celda
                        $sheet->getCellByColumnAndRow($colIndex, $rowIndex)
                            ->getHyperlink()
                            ->setUrl($aux )
                            ->setTooltip('Descargar archivo');
                    } else {
                        // Si no es un enlace, solo se coloca el valor de la celda
                        $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex, $cellValue);
                    }
    
                    $colIndex++;
                }
                $rowIndex++;
            }
        }
    
        // Estilo para la primera fila (encabezados)
        $range = "A1:" . $this->getSheetCoordinates() . "1";
        $sheet->getStyle($range)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($range)->getFont()->setBold(true);
    }
    
    public function insertImages(Worksheet $sheet){
        $collection = $this->collection();
        $rowIndex = 2;

        foreach ($collection as $rows) {
            foreach ($rows as $row) {
                $colIndex = 0;
                foreach ($row as $key => $cellValue) {
                    if (str_contains($key, "_image_")) {
                        $coor = self::numToCol( $colIndex ) . $rowIndex;

                        $sheet->setCellValue( $coor , '' );

                        $drawing = new Drawing();
                        $drawing->setName($key);
                        $drawing->setDescription($key);
                        $drawing->setPath(public_path($cellValue));
                        $drawing->setHeight(80);
                        $drawing->setCoordinates( $coor );

                        $sheet->getRowDimension($rowIndex)->setRowHeight(80);
                        $sheet->getColumnDimensionByColumn($colIndex + 1)->setWidth(20);

                        $drawing->setWorksheet($sheet);
                    }
                    $colIndex++;
                }
                $rowIndex++;
            }
        }
    }
    
    private function numToCol( $num ){
        $letters = '';

        while( $num >= 0 ){
            $letters = chr( ($num % 26) + 65 ) . $letters;
            $num = intval( $num / 26 ) - 1;
        }

        return $letters;
    }

    public function getSheetCoordinates() {
        $coordinate = "";

        if( $this->numCols < 26 )
            $coordinate = chr(65 + ( $this->numCols - 1 ));
        else if( $this->numCols > 26 && $this->numCols <= 702 ){
            $coordinate = chr(64 + (intval( ($this->numCols - 1) / 26) ) ) . chr(65 + ( $this->numCols % 26 - 1) );
        }

        return $coordinate;
    }
}
