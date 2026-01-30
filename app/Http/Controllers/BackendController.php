<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class BackendController extends Controller
{
    private $names = [
        1 => ['name' => 'Ana', 'age' => 31],
        2 => ['name' => 'Ana2', 'age' => 32],
        3 => ['name' => 'Ana3', 'age' => 33],
    ];

    public function get(int $id = 0){
        if(isset($this->names[$id])){
            return response()->json([$this->names[$id]]);
        }
        return response()->json(['message' => "nombre no encontrado"], RESPONSE::HTTP_NOT_FOUND);
    }

    public function getAll(){
        return response()->json($this->names);
    } 

    public function create(Request $request){
        $person = [
                'id' => count($this->names) + 1, 
                'name' => $request->input("name"),
                'age' => $request->input("age")
        ];

        $this->names[$person["id"]] = $person;

        return response()->json(["message" => "Persona creada", "Persona" => $person], response::HTTP_CREATED);
    }

    public function update(Request $request, int $id){
        if(isset( $this->names[$id])){
            $this->names[$id]['name'] = $request->input('name', $this->names[$id]["name"]);
            $this->names[$id]['age'] = $request->input('age', $this->names[$id]["age"]);

            return response()->json(['message' => "datos actualizados", 'person' => $this->names[$id]]);
        }

        return response()->json(['message' => "nombre no encontrado"], RESPONSE::HTTP_NOT_FOUND);
    }

    public function delete (int $id){
        if (isset($this->names[$id])){

            unset($this->names[$id]);

            return response()->json(['message'=> "Persona eliminada correctamente"]);
        }
        return response()->json(['message' => "nombre no encontrado"], RESPONSE::HTTP_NOT_FOUND);
    }
}
