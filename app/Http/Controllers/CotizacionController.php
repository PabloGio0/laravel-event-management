<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function Laravel\Prompts\select;


class CotizacionController extends Controller
{
    private $cotizaciones = [
        1 => [
            'cliente' => 'Juan Pérez',
            'evento' => 'Boda',
            'servicios' => [
                ['nombre' => 'Audio', 'precio' => 3500],
                ['nombre' => 'Iluminación', 'precio' => 2500],
            ],
            'total' => 6000
        ],
        2 => [
            'cliente' => 'Juan Pedro',
            'evento' => 'Salida',
            'servicios' => [
                ['nombre' => 'Audio', 'precio' => 4500],
                ['nombre' => 'Iluminación', 'precio' => 1500],
            ],
            'total' => 6000
        ],
    ];

    public function index()
    {
        return response()->json(['Cotizaciones' => $this->cotizaciones]);
    }

    public function show(int $id)
    {
        if (isset($this->cotizaciones[$id])) {
            return response()->json(['evento' => $this->cotizaciones[$id]]);
        }
        return response()->json(['error' => "ID no existente"], Response::HTTP_NOT_FOUND);
    }

    public function store(Request $request)
    {

        $evento = [
            'id' => count($this->cotizaciones) + 1,
            'cliente' => $request->input("cliente"),
            'evento' => $request->input("evento"),
            'servicios' => $request->input("servicios"),
            'total' => $this->calcularPrecio($request->input('servicios')),
            'fecha_creacion' => now()->toDateTimeString(),
        ];

        if ($evento['cliente'] != null) {

            $this->cotizaciones[$evento['id']] = $evento;

            return response()->json(['message' => "Evento creado correctamente", 'evento' => $evento]);
        }

        return response()->json(['error' => "Sin cliente"], Response::HTTP_BAD_REQUEST);
    }

    public function update(Request $request, int $id){
        if(isset($this->cotizaciones[$id])){
            $this->cotizaciones[$id]['cliente'] = $request->input('cliente', $this->cotizaciones[$id]['cliente']);
            $this->cotizaciones[$id]['evento'] = $request->input('evento');
            $this->cotizaciones[$id]['servicios']  = $request->input('servicios');
            $this->cotizaciones[$id]['total'] = $this->calcularPrecio($request->input('servicios'));

            return response()->json(['message' => "datos actualizados", 'evento' => $this->cotizaciones[$id]]);
        }

        return response()->json(['error' => "ID inexistente"], Response::HTTP_NOT_FOUND);
        
    }

    public function delete(int $id){
        if(isset($this->cotizaciones[$id])){

            unset($this->cotizaciones[$id]);

            return response()->json(['message'=> "Evento eliminado correctamente"]);
        }

        return response()->json(['message' => "evento no encontrado"], RESPONSE::HTTP_NOT_FOUND);
    }

    private function calcularPrecio (array $servicios){
        $total = 0;
        foreach ($servicios as $servicio) {
            $total += $servicio['precio'];
        }
        return $total;
    }

    //Obtener todas las cotizaciones con cliente y servicios
    public function eventList (){
        $evento = Cotizacion::join("clientes", "cotizaciones.cliente_id", "=", "clientes.id")
        ->join("cotizacion_servicio", "cotizaciones.id", "=", "cotizacion_servicio.cotizacion_id")
        ->join("servicios", "cotizacion_servicio.servicio_id", "=", "servicios.id")
        ->select("clientes.nombre as cliente", "cotizaciones.evento", "servicios.nombre as servicio", "cotizacion_servicio.cantidad", "cotizaciones.total")
        ->get();
        return response()->json($evento);
    }

    public function findEvent (int $id){
        $evento = Cotizacion::join("clientes", "cotizaciones.cliente_id", "=", "clientes.id")
        ->join("cotizacion_servicio", "cotizaciones.id", "=", "cotizacion_servicio.cotizacion_id")
        ->join("servicios", "cotizacion_servicio.servicio_id", "=", "servicios.id")
        ->where("clientes.id", $id)
        ->orderBy("servicios.nombre")
        ->select("clientes.nombre as cliente", "cotizaciones.evento", "servicios.nombre as servicio", "cotizacion_servicio.cantidad", "cotizaciones.total")
        ->get();
        return response()->json($evento);
    }

    public function eventListWith(int $id){
        $eventos = Cotizacion::with(['cliente', 'servicio'])
        ->findOrFail($id);

        return response()->json($eventos);
    }

    public function indexClient(int $id){
        $cotizaciones =  Cotizacion::with(['cliente', 'servicio'])
        ->where("cliente_id", $id)
        ->orderBy("fecha_evento", "desc")
        ->get();

        return response()->json($cotizaciones);
    }

    public function serviceSearch (string $value){
        $services = Servicio::where("nombre", "like", "%{$value}%")
        ->orderBy("precio", "desc")
        ->get();

        return response()->json($services);
    }

    public function filtro (float $price){
        $service = Servicio::where("nombre", "like", "%LED%")
        ->orWhere("precio", "<", $price)
        ->get();

        return response()->json($service);
    }

    public function filtroAvanzado(Request $request){

        $services = Servicio::where(function($query) use ($request){
            if($request->input("nombre")){
                $query->where("nombre", "like", "%{$request->input("nombre")}%");
            }
        })
        ->where(function($query) use ($request){
            if($request->input("precio_max")){
                $query->where("precio", ">=", $request->input("precio_max"));
            }
        })
        ->where(function($query) use ($request){
            if($request->input("precio_min")){
                $query->where("precio", "<=", $request->input("precio_min"));
            }
        })
        ->get();

        return response()->json($services);
    }

    public function advancedSearch(Request $request){
        $query = Servicio::query();

        $query->when($request->filled("nombre"), function ($q) use($request) {
            $q->where("nombre", "like", "%{$request->input('nombre')}%");
        });

        $query->when($request->filled("precio_min"), function ($q) use($request) {
            $q->where("precio", ">=", "$request->input('precio_min')");
        });

        $query->when($request->filled("precio_max"), function ($q) use($request) {
            $q->where("precio", "<=", "$request->input('precio_max')");
        });

        $query->when($request->filled("orden_por") && $request->filled("orden") , function ($q) use($request) {
            $q->orderBy($request->orden_por, $request->orden);
        });

        if($request->filled("por_pagina")){
            return response()->json(
                $query->paginate($request->por_pagina)
            );
        }

        return response()->json($query->get());
    }
}
