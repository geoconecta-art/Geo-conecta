<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Validators\Failure;

class RegisterImport implements ToCollection
{
    protected $isValid = true;
    protected $data;
    protected $headers;
    protected $requiredFields;
    protected $missingFields = [];
    protected $invalidFields = [];
    protected $optionalNoExtFields = [
        "No. Ext.",
        "No Ext",
        "No. Ext",
        "No Ext.",
        "No. Exterior",
        "Número Exterior",
        "Numero Exterior",
        "Número Ext.",
    ];

    public function __construct(array $requiredFields){
        $this->requiredFields = $requiredFields;
    }

    public function collection(Collection $rows) {
        $headerRow = $rows->first()->toArray();
        // dd( $headerRow );

        foreach ($this->requiredFields as $field) {
            if (!in_array($field, $headerRow)) {
                $this->isValid = false;
                $this->missingFields[] = $field;
            }
        }

        if( $this->isValid ){
            $this->data = $rows;
            $this->headers = array_map( 'trim', $headerRow );
        }
    }

    public function isValid() {
        return $this->isValid;
    }

    public function getData(){
        return $this->data;
    }

    public function getHeaders(){
        return $this->headers;
    }

    public function getMissingFields(){
        return $this->missingFields;
    }
}
