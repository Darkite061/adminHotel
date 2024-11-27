<?php

// App/Http/Controllers/PagoController.php

namespace App\Http\Controllers;

use App\Models\pagos;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    // Función para mostrar la lista de pagos
    public function index()
    {
        // Obtiene todos los pagos
        $pagos = Pago::all();

        // Retorna la vista con los pagos
        return view('menu/pago', compact('pagos'));
    }

    // Función para actualizar el estado de 'completado'
    public function updateCompletado($id)
    {
        // Encuentra el pago por su ID
        $pago = Pago::find($id);

        // Si no se encuentra el pago, muestra un error o redirige
        if (!$pago) {
            return redirect()->back()->with('error', 'Pago no encontrado');
        }

        // Cambia el estado de 'completado' (si es true, lo cambia a false y viceversa)
        $pago->completado = !$pago->completado;

        // Guarda los cambios en la base de datos
        $pago->save();

        // Redirige de nuevo a la lista de pagos
        return redirect()->route('pagos.index')->with('success', 'Estado del pago actualizado');
    }
}