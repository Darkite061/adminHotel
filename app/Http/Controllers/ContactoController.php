<?php

namespace App\Http\Controllers;

use App\Models\contacto;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    // Mostrar todos los contactos
    public function index()
    {
        $contactos = contacto::all(); // Recupera todos los contactos
        return view('menu.contacto', compact('contactos')); // Retorna la vista con los contactos
    }

    // Mostrar detalles de un contacto (para modal)
    public function show($id)
    {
        $contacto = contacto::findOrFail($id); // Busca el contacto por ID
        return response()->json($contacto);   // Devuelve los datos como JSON
    }

    // Marcar un contacto como atendido
    public function marcarComoAtendida($id)
    {
        $contacto = contacto::findOrFail($id); // Busca el contacto por ID
        $contacto->atendida = true;           // Actualiza el estado a "atendida"
        $contacto->save();                    // Guarda los cambios

        return response()->json(['message' => 'El contacto ha sido marcado como atendido.']);
    }
}
