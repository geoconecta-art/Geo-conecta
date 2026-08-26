<?php

namespace App\Imports;

use App\Models\Dependence;
use App\Models\Support;
use App\Models\SupportCatalog;
use App\Models\SupportType;
use App\Tools\Tools;
use Exception;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class SupportsImport implements OnEachRow
{


    public function onRow(Row $row)
    {
        try{
        $rowIndex = $row->getIndex();
        $row = $row->toArray();

        Log::info('row: ' . $rowIndex);

        if(str_contains($row[0], 'ORGANISMO')){
            return;
        }
        
        //Log::info($row);
        $rowDependence = trim($row[0]);
        $slugDependence = Tools::convertToSlug($rowDependence);
        $dependence = Dependence::where('slug', $slugDependence)->first();

        if(!$dependence){
            $dependence = new Dependence();
            $dependence->name = $rowDependence;
            $dependence->slug = $slugDependence;
            $dependence->uuid = (string) Str::orderedUuid();
            $dependence->save();

            Log::info('Nueva dependencia:');
            Log::info(json_encode($dependence));
        }

        $rowType = trim($row[1]);
        $slugType = Tools::convertToSlug($rowType);
        $type = SupportType::where('slug', $slugType)->first();

        if(!$type){
            $type = new SupportType();
            $type->name = $rowType;
            $type->slug = $slugType;
            $type->uuid = (string) Str::orderedUuid();
            $type->save();

            Log::info('Nuevo tipo de apoyo:');
            Log::info(json_encode($type));
        }

        $rowSupportCatalog = trim($row[2]);
        $slugSupportCatalog = Tools::convertToSlug($rowSupportCatalog);
        $supportCatalog = SupportCatalog::where('slug', $slugSupportCatalog)->first();

        if(!$supportCatalog){
            $supportCatalog = new SupportCatalog();
            $supportCatalog->name = $rowSupportCatalog;
            $supportCatalog->slug = $slugSupportCatalog;
            $supportCatalog->uuid = (string) Str::orderedUuid();
            $supportCatalog->dependence_id = $dependence->id;
            $supportCatalog->dependence_name = $dependence->name;
            $supportCatalog->support_type_id = $type->id;
            $supportCatalog->support_type_name = $type->name;
            $supportCatalog->save();

            Log::info('Nuevo soporte en catalogo:');
            Log::info(json_encode($supportCatalog));
        }

        $support = new Support();
        $support->name = $rowSupportCatalog;
        $support->slug = $slugSupportCatalog;
        $support->uuid = (string) Str::orderedUuid();
        $support->dependence_id = $dependence->id;
        $support->dependence_name = $dependence->name;
        $support->support_type_id = $type->id;
        $support->support_type_name = $type->name;
        $support->support_catalog_id = $supportCatalog->id;
        $support->support_catalog_name = $supportCatalog->name;
        $support->beneficiary_father_last_name = trim($row[3]);
        $support->beneficiary_mother_last_name = trim($row[4]);
        $support->beneficiary_first_name = trim($row[5]);
        $support->beneficiary_street = trim($row[6]);
        $support->beneficiary_street_number = trim($row[8]);

        $support->beneficiary_colony = trim($row[9]);
        $support->beneficiary_zipcode = trim($row[10]);

        $support->beneficiary_phone = trim($row[11]);
        $support->beneficiary_gender = trim($row[12]);
        $support->beneficiary_curp = trim($row[13]);
        $support->beneficiary_age = trim($row[14]);
        $support->beneficiary_quantity = intval(trim($row[15]));

        $lat = trim($row[16]);
        $long = trim($row[17]);

        Log::info('Lat:' .$lat );
        Log::info('Long:' .$long );

        if(isset($lat) && isset($long)){
            $support->geo_lat = doubleval(trim($row[16]));
            $support->geo_lng = doubleval(trim($row[17]));
        }

        $support->save();

        }catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    // public function collection(Collection $collection)
    // {
    //     $counter = 0;
    //     $consecutive = 0;
    //     foreach ($collection as $row) 
    //     {
    //         $counter = $counter + 1;
    //         //if(str_contains($row[0], 'ORGANISMO')){
    //         if($counter == 1){
    //             Log::info('se ignora por que trai organismo');
    //             continue;
    //         }
    
    //         //Log::info($row);
    //         $rowDependence = trim($row[0]);
    //         $slugDependence = Tools::convertToSlug($rowDependence);
    //         $dependence = Dependence::where('slug', $slugDependence)->first();
    
    //         Log::info('Dependencia:');
    //         Log::info(json_encode($dependence));
    
    //         if(!$dependence){
    //             $dependence = new Dependence();
    //             $dependence->name = $rowDependence;
    //             $dependence->slug = $slugDependence;
    //             $dependence->uuid = (string) Str::orderedUuid();
    //             $dependence->save();
    
    //             Log::info('Nueva dependencia:');
    //             Log::info(json_encode($dependence));
    //         }
    
    //         $rowType = trim($row[1]);
    //         $slugType = Tools::convertToSlug($rowType);
    //         $type = SupportType::where('slug', $slugType)->first();
    
    //         if(!$type){
    //             $type = new SupportType();
    //             $type->name = $rowDependence;
    //             $type->slug = $slugDependence;
    //             $type->uuid = (string) Str::orderedUuid();
    //             $type->save();
    
    //             Log::info('Nuevo tipo de apoyo:');
    //             Log::info(json_encode($type));
    //         }
    
    //         $rowSupportCatalog = trim($row[2]);
    //         $slugSupportCatalog = Tools::convertToSlug($rowSupportCatalog);
    //         $supportCatalog = SupportType::where('slug', $slugSupportCatalog)->first();
    
    //         if(!$supportCatalog){
    //             $supportCatalog = new SupportCatalog();
    //             $supportCatalog->name = $rowDependence;
    //             $supportCatalog->slug = $slugDependence;
    //             $supportCatalog->uuid = (string) Str::orderedUuid();
    //             $supportCatalog->dependence_id = $dependence->id;
    //             $supportCatalog->dependence_name = $dependence->name;
    //             $supportCatalog->support_type_id = $type->id;
    //             $supportCatalog->support_type_name = $type->name;
    //             $supportCatalog->save();
    
    //             Log::info('Nuevo soporte en catalogo:');
    //             Log::info(json_encode($supportCatalog));
    //         }
    //     }
    // }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $row)
    // {

    //     if(str_contains($row[0], 'ORGANISMO')){
    //         Log::info('se ignora por que trai organismo');
    //         return null;
    //     }

    //     //Log::info($row);
    //     $rowDependence = trim($row[0]);
    //     $slugDependence = Tools::convertToSlug($rowDependence);
    //     $dependence = Dependence::where('slug', $slugDependence)->first();

    //     Log::info('Dependencia:');
    //     Log::info(json_encode($dependence));

    //     if(!$dependence){
    //         $dependence = new Dependence();
    //         $dependence->name = $rowDependence;
    //         $dependence->slug = $slugDependence;
    //         $dependence->uuid = (string) Str::orderedUuid();
    //         $dependence->save();

    //         Log::info('Nueva dependencia:');
    //         Log::info(json_encode($dependence));
    //     }

    //     $rowType = trim($row[1]);
    //     $slugType = Tools::convertToSlug($rowType);
    //     $type = SupportType::where('slug', $slugType)->first();

    //     if(!$type){
    //         $type = new SupportType();
    //         $type->name = $rowDependence;
    //         $type->slug = $slugDependence;
    //         $type->uuid = (string) Str::orderedUuid();
    //         $type->save();

    //         Log::info('Nuevo tipo de apoyo:');
    //         Log::info(json_encode($type));
    //     }

    //     $rowSupportCatalog = trim($row[2]);
    //     $slugSupportCatalog = Tools::convertToSlug($rowSupportCatalog);
    //     $supportCatalog = SupportType::where('slug', $slugSupportCatalog)->first();

    //     if(!$supportCatalog){
    //         $supportCatalog = new SupportCatalog();
    //         $supportCatalog->name = $rowDependence;
    //         $supportCatalog->slug = $slugDependence;
    //         $supportCatalog->uuid = (string) Str::orderedUuid();
    //         $supportCatalog->dependence_id = $dependence->id;
    //         $supportCatalog->dependence_name = $dependence->name;
    //         $supportCatalog->support_type_id = $type->id;
    //         $supportCatalog->support_type_name = $type->name;
    //         $supportCatalog->save();

    //         Log::info('Nuevo soporte en catalogo:');
    //         Log::info(json_encode($supportCatalog));
    //     }

    //     return new Support([
    //         //
    //     ]);
    // }
}
