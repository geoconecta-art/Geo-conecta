<?php

use App\Http\Controllers\ColoniasController;
use App\Http\Controllers\DirectionsController;
use App\Http\Controllers\GeovisorController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PlanRegisterController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SubdirectionsController;
use App\Http\Controllers\UserController;
use App\Models\PlanRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::view('login', 'admin.auth.login')->name('login')->middleware('guest');

Route::post('login', [LoginController::class, 'authenticate']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::redirect('/', '/admin/inventarios');
Route::redirect('/admin', '/admin/inventarios');
Route::redirect('/home', '/admin/inventarios');

Route::group(['prefix' => '/admin'], function(){

    // RUTAS PARA ESTADISTICAS
    Route::group(['prefix' => '/estadisticas'], function(){
        Route::get('/', [StatsController::class, 'index'])->name('stats.index');
        Route::get('/error', [StatsController::class, 'error'])->name('stats.error');
        Route::get('/direccion/{id_direction}/stats', [StatsController::class, 'getInventoryStats'])->name('stats.direction-info');
        Route::post('/{id_area}/export-pdf', [StatsController::class, 'exportPdfStats'])->name('stats.area.export');
    });

    // RUTAS PARA CRUD USUARIOS
    Route::group(['prefix' => '/usuarios'], function() {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        // CERAR USUARIOS
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/store', [UserController::class, 'store'])->name('users.store');
        // EDITAR USUARIOS
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/update/{user}',[UserController::class, 'update'])->name('users.update');
        // ELIMINAR USUARIOS
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // RUTAS PARA CRUD COLONIAS
    Route::group(['prefix' => '/colonias'], function() {
        // LEER COLONIAS
        Route::get('/', [ColoniasController::class, 'index'])->name('suburb.index');
        Route::get('/update-table', [ColoniasController::class, 'updateTable'])->name('suburb.update-table');
        // CREAR COLONIAS
        Route::get('/open-modal', [ColoniasController::class, 'showModal'])->name('suburb.open-modal');
        Route::post('/create-suburb', [ColoniasController::class, 'createSuburb'])->name('suburb.create');
        // ACTUALIZAR COLONIAS
        Route::post('/{col}/update-suburb', [ColoniasController::class, 'updateSuburb'])->name('suburb.update');
        // ELIMINAR COLONIAS
        Route::delete('/{col}/delete-suburb', [ColoniasController::class, 'deleteSuburb'])->name('suburb.delete');
    });

    // RUTAS PARA DIRECCIONES
    Route::group(['prefix' => '/direcciones'], function(){
        // INDEX
        Route::get('/', [DirectionsController::class, 'index'])->name('directions.index');
        Route::get('/get-directions', [DirectionsController::class, 'getAreas'])->name('directions.get.areas');
        // CREATE
        Route::get('/open-modal', [DirectionsController::class, 'openModal'])->name('directions.open-modal');
        Route::post('/create-direction', [DirectionsController::class, 'create'])->name('directions.create');
        // EDIT
        Route::get('/edit/{id}', [DirectionsController::class, 'edit'])->name('directions.edit-modal');
        Route::post('/{id}/update-direction', [DirectionsController::class, 'update'])->name('directions.update');
        // DELETE
        Route::get('/{id}/delete/warning', [DirectionsController::class, 'confirmDelete'])->name('directions.confirm-delete');
        Route::delete('/{id}/delete', [DirectionsController::class, 'delete'])->name('directions.delete');
    });

    // RUTAS PARA SUBDIRECCIONES
    Route::group(['prefix' => '/subdirecciones'], function(){
        // INDEX
        Route::get('/', [SubdirectionsController::class, 'index'])->name('subdirections.index');
        Route::get('/update-table', [SubdirectionsController::class, 'updateTable'])->name('subdirections.update.table');
        // CREATE
        Route::get('/open-modal', [SubdirectionsController::class, 'openModal'])->name('subdirections.open-modal');
        Route::post('/create-direction', [SubdirectionsController::class, 'create'])->name('subdirections.create');
        // EDIT
        Route::get('/{id}/edit', [SubdirectionsController::class, 'edit'])->name('subdirections.open-edit');
        Route::post('/{id}/update-direction', [SubdirectionsController::class, 'update'])->name('subdirections.update');
        // DELETE
        Route::get('/{id}/delete/warning', [SubdirectionsController::class, 'confirmDelete'])->name('subdirections.confirm-delete');
        Route::delete('/{id}/delete', [SubdirectionsController::class, 'delete'])->name('subdirections.delete');
    });


    // RUTAS PARA INVENTARIOS
    Route::group(['prefix' => '/inventarios'], function () {
        Route::get('/', [PlanController::class, 'index'])->name('plans.index');
        Route::get('/update-table', [PlanController::class, 'updateTable'])->name('plans.update.table');
        // CREAR INVENTARIO
        Route::get('/open-modal', [PlanController::class, 'openModal'])->name('plans.modal');
        Route::post('/create-plan', [PlanController::class, 'create'])->name('plans.create');
        Route::post('/formualario/{id}/add-address-fields', [PlanController::class, 'addAddressField'])->name('plans.create.add-address');
        Route::get('/formulario/{id}/', [PlanController::class, 'createForm'])->name('plans.form.create');
        Route::post('/formulario/{id}/add-catastral-key-field', [PlanController::class, 'addCatastralField'])->name('plans.create.add-catastral-key');

        Route::get('/open-modal-csv', [PlanController::class, 'openModalCSV'])->name('plans.modal-csv');
        Route::post('/get-headers', [PlanController::class, 'getHeadersFromCSV'])->name('plans.import-get-headers');
        Route::post('/import-csv', [PlanController::class, 'createPlanFromCSV'])->name('plans.create-from-csv');
        // EDITA INFORMACIÓN BÁSCIA DEL FORMULARIO
        Route::get('/{id}/edit', [PlanController::class, 'getInfoEdit'])->name('plans.edit');   // Abrir modal en vista para crear el formulario
        Route::post('/{id}/update-planeacion', [PlanController::class, 'updateBasicInfo'])->name('plan.update');
        // ELIMINAR FORMULARIO
        Route::get('/{id}/delete-form/warning', [PlanController::class, 'confirmDelete'])->name('plan.confirm-delete');
        Route::delete('/{id}/delete-form', [PlanController::class, 'deleteForm'])->name('plan.delete');

        // CRUD INPUTS
        Route::get('/{id}/edit-form/', [PlanController::class, 'editForm'])->name('plans.form.edit');   // Debe llevar a la pantalla de edición de formulario
        // CREAR 
        Route::post('/{id}/create-field', [PlanController::class, 'createFormField'])->name('plans.form.field.create');
        // EDITAR
        Route::post('/{id}/update-field/{index}', [PlanController::class, 'updateFormField'])->name('plans.form.field.update'); // Actualiza un campo del formulario
        Route::post('/{id}/update-order-fields', [PlanController::class, 'updateFormFieldOrder'])->name('plans.form.field.update-order');   // Actualizar el orden del formulario
        // ELIMINAR ELEMENTOS
        Route::delete('/{id}/delete-field/{index}' , [PlanController::class, 'deleteFormField'])->name('plans.form.field.delete');  //Eliminar un campo del formulario

        // Lleva a la vista previa del formulario
        Route::get('/formulario/{id}/vista-previa', [PlanController::class, 'preview'])->name('plans.preview');
        Route::post('/formulario/{id}/vista-previa/validacion', [PlanController::class, 'previewValidateForm'])->name('plans.preview.validation');
        Route::post('/validate-catastral-key', [PlanController::class, 'valitadateCatastralKey'])->name('plans.validate-catastral-key');
    });

    // RUTAS PARA MODULO DATOS
    Route::group(['prefix' => '/datos'], function () {
        Route::get('/', [PlanRegisterController::class, 'index'])->name('register.index');
        Route::get('/filter-inventaries', [PlanRegisterController::class, 'filterInfo'])->name('register.filter-info');
        Route::get('/inventario/{id}', [PlanRegisterController::class, 'inventoryIndex'])->name('register.inventory.index');    // Muestra todos los registros de dicho inventario

        // AGREGAR REGISTRO
        Route::get('/inventario/{id}/nuevo-registro', [PlanRegisterController::class, 'inventoryFormIndex'])->name('register.inventory.plan-form'); // Lleva a la pantalla del formulario para crear un nuevo registro
        Route::post('/inventario/{id}/guardar-registro', [PlanRegisterController::class, 'inventoryStore'])->name('register.inventory.store');  // Almacena un nuevop registro en la base de datos

        //Importar desde un archivo
        Route::get('/inventario/{id}/importar-registros/modal-{type}', [PlanRegisterController::class, 'showImportRowsFromFileModal'])->name('register.inventory.import-modal');
        Route::post('/inventario/{id}/importar-registros/obtener-encabezados', [PlanRegisterController::class, 'getHeadersFromFile'])->name('register.inventory.import-get-headers');
        Route::post('/inventario/{id}/importar-registros/file-{type}/guardar', [PlanRegisterController::class, 'saveImportRowsFromFile'])->name('register.inventory.import-save');

        // Exportar Excel
        Route::get('/inventario/{id}/exportar-registros/excel', [PlanRegisterController::class, 'exportRegisterExcel'])->name('register.inventory.export-excel');

        // Exportar PDF
        Route::get('/inventario/{id}/exportar-registros/abrir-modal', [PlanRegisterController::class, 'openPdfExportModal'])->name('register.inventory.export-open-modal');
        Route::POST('/inventario/{id}/exportar-registros/', [PlanRegisterController::class, 'exportRegisterPdf'])->name('register.inventory.export-pdf');
        Route::get('/inventario/{id}/exportar-registros/detalles/pdf', [PlanRegisterController:: class, 'exportDetailsRegisterPdf'])->name('register.inventory.export-details-pdf');
        Route::get('/inventario/{id}/exportar-registros/listado/pdf', [PlanRegisterController::class, 'exportListRegisterPdf'])->name('register.inventory.export-list-pdf');
        Route::get('/inventario/{id}/exportar-registros/mapa/pdf', [PlanRegisterController::class, 'exportMapRegisterPdf'])->name('register.inventory.export-map-pdf');

        // Exportar en KML
        Route::get('/inventario/{id}/exportar-registros/kml', [PlanRegisterController::class, 'exportKML'])->name('register.inventory.export-kml');

        // Exportar en GeoJSON
        Route::get('/inventario/{id}/exportar-registros/geojson', [PlanRegisterController::class, 'exportGeoJSON'])->name('register.inventory.export-gejson');

        // EDITAR REGISTRO
        Route::get('/inventario/{id}/editar-registro/{row}', [PlanRegisterController::class, 'editInventoryData'])->name('register.inventory.edit-row');    //Lleva a la pantalla de edición del registro
        Route::post('/inventario/{id}/update-registro/{row}', [PlanRegisterController::class, 'updateInventoryData'])->name('register.inventory.update-row');   //Actualiza los datos en el servidor

        // ELIIMINAR REGISTRO
        Route::get('/inventario/{id_plan}/delete-row/{id_row}/warning', [PlanRegisterController::class, 'deleteRowWarning'])->name('register.inventory.delete-warning');
        Route::delete('/inventario/{id_plan}/delete-row/{id_row}', [PlanRegisterController::class, 'deleteRow'])->name('register.inventory.delete');
    });

    // MÓDULO MAPA
    Route::group(['prefix' => '/mapa'], function() {
        Route::get('/', [MapController::class, 'index'])->name('map.index'); //Muestra la vista del mapa
        Route::get('/inventario/{id_plan}/registros', [MapController::class, 'getInventoryInfo'])->name('map.inventory-info');
        Route::get('/{map}/inventories', [MapController::class, 'getInventoriesId'])->name('map.info');
        Route::get('/{map}/exportar-pdf', [MapController::class, 'exportMap'])->name('map.export');
        Route::get('/show-modal', [MapController::class, 'showModal'])->name('map.modal');
        Route::post('/map/create', [MapController::class, 'createMap'])->name('map.create');
        Route::post('/{map}/edit', [MapController::class, 'editMap'])->name('map.edit');

        Route::get('/get-polygon-by-lote', [MapController::class, 'getLotePolygon'])->name('map.get-lote');
        Route::get('/get-lotes', [MapController::class, 'getCatastroData'])->name('map.catastro');
        Route::get('/search-lotes', [MapController::class, 'searchLote'])->name('map.search.lote');
        Route::get('/rows-by-lote', [MapController::class, 'rowsByLote'])->name('map.rowsByLote');

        // Compartir Mapa
        Route::get('/{id}/share-map', [MapController::class, 'shareMap'])->name('map.share-map');
    });

    // MÓDULO GEOVISOR
    Route::group(['prefix' => '/geovisor'], function() {
        Route::get('/', [GeovisorController::class, 'index'])->name('geovisor.index');
        Route::get('/generar-mapa', [GeovisorController::class, 'createMapIndex'])->name('geovisor.make-index');
        Route::post('/pre-load-map', [GeovisorController::class, 'preLoadMap'])->name('geovisor.pre-load');
        Route::get('/imprimir-mapa', [GeovisorController::class, 'printMapIndex'])->name('geovisor.print-index');
        Route::get('/imprimir-mapa-vista', [GeovisorController::class, 'printMap'])->name('geovisor.print-view');
    });

    Route::group(['prefix' => '/search'], function(){
        Route::get('/', [SearchController::class, 'search'])->name('search.get-info');
    });
});


/********************** APARTADO DE RUTAS PÚBLICAS *********************/

// Manipula el enlace que se encuentra en los archivos Excel, es un enlace abierto a todo público
Route::get('descargar/archivo/', [PublicController::class, 'downloadFileFromExcel'])->name('download.file.from.excel');

// Mapa compartido
Route::get('/mapa/{id_map}/vista-publica', [PublicController::class, 'openPublicMap'])->name('public.view-map');