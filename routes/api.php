<?php

use App\Http\Controllers\BackendController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\ServicioController;
use Illuminate\Support\Facades\Route;


Route::get("/test", function(){
    return "EL backend funciona correctamente";
});

Route::get("/backend/{id}", [BackendController::class, "get"]);

Route::get("/backend", [BackendController::class, "getAll"]);

Route::post("/backend", [BackendController::class, "create"]);

Route::put("/backend/{id}", [BackendController::class, "update"]);

Route::delete("/backend/{id}", [BackendController::class, "delete"]);


Route::get("/cotizaciones", [CotizacionController::class, "index"]);

Route::get("/cotizaciones/{id}", [CotizacionController::class, "show"]);

Route::post("/cotizaciones", [CotizacionController::class, "store"]);

Route::put("/cotizaciones/{id}", [CotizacionController::class, "update"]);

Route::delete("/cotizaciones/{id}", [CotizacionController::class, "delete"]);

Route::get("/mostrarEvento", [CotizacionController::class, "eventList"]);
Route::get("/mostrarEventoWith/{id}", [CotizacionController::class, "eventListWith"]);
Route::get("/mostrarEvento/{id}", [CotizacionController::class, "findEvent"]);
Route::get("/mostrarEvento/cliente/{id}", [CotizacionController::class, "indexClient"]);

Route::get("/servicios/{value}", [CotizacionController::class, "serviceSearch"])->middleware(middleware: 'role:admin');
Route::get("/servicios/precio/{value}", [CotizacionController::class, "filtro"]);
Route::post("/servicios/busquedaAvanzada", [CotizacionController::class, "filtroAvanzado"]);
Route::get("/servicios/busqueda/avanzada/eventos", [CotizacionController::class, "advancedSearch"]);

//Route::apiResource('/servicios', ServicioController::class)->middleware(["registerInfo", "headerInfo"]);