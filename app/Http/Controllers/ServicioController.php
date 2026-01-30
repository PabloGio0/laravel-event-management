<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServicioRequest;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ServicioController extends Controller
{
    public function index(Request $request){
        $perPage = $request->query("per_page", 5);
        /*$page = $request->query("page", 0);
        $offset = $perPage * $page;
        $servicios = Servicio::skip($offset)->take($perPage)
        ->orderBy('nombre')
        ->get();*/

        $servicios = Servicio::orderBy('nombre')->paginate($perPage);
        return response()->json($servicios);
    }

    public function store(ServicioRequest $request){
        try{
            $servicio =  Servicio::create($request->validated());
            return response()->json(['message'=>'servicio agregado correctamente', 'servicio'=>$servicio], Response::HTTP_CREATED);
        }catch(ValidationException $e){
            return response()->json(['error' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        };
    }

    public function update(ServicioRequest $request, Servicio $servicio){
        try{
            $servicio->update($request->validated());
            return response()->json(['message'=>'servicio actualizado correctamente', 'servicio'=>$servicio], Response::HTTP_CREATED);
        }catch(ValidationException $e){
            return response()->json(['error' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        };
    }
    public function destroy(Servicio $servicio){
        $servicio->delete();

        return response()->json(["message" => "servicio eliminado correctamente"]);
    }
}
