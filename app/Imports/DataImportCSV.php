<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class DataImportCSV implements ToCollection
{

    protected $isValid = true;
    protected $data;
    protected $headers;
    // protected $requiredFields;
    protected $missingFields = [];
    protected $invalidFields = [];

    public function __construct(){
        // $this->requiredFields = $requiredFields;
    }

    public function collection(Collection $rows) {
        $headerRow = $rows->first()->toArray();
        $this->data = $rows;
        $this->headers = array_map( 'trim', $headerRow );
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
