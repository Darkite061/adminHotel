<?php

namespace App\Http\Controllers;

use App\Models\reservas; // Asegúrate de que estás usando el modelo correcto
use Illuminate\Http\Request;
use App\Models\habitaciones; 
use App\Models\cliente; 


class ReservasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtiene todos los registros de la tabla 'reservas'
        $reservas = reservas::all();

        // Retorna la vista 'menu/reserva' y pasa los datos de 'reservas' a la vista
        return view('menu.reserva', compact('reservas'));
    }
         
    public function showOptionsForm()
    {
        return view('reserva.opciones');
    }

    // Mostrar las habitaciones disponibles según las fechas seleccionadas
    public function showAvailableRooms(Request $request)
    {
        $fechaEntrada = $request->input('fecha_entrada');
        $fechaSalida = $request->input('fecha_salida');

        // Obtener los IDs de las habitaciones ocupadas en el rango de fechas seleccionado
        $reservasOcupadas = Reservas::where(function ($query) use ($fechaEntrada, $fechaSalida) {
            $query->whereBetween('fecha_entrada', [$fechaEntrada, $fechaSalida])
                ->orWhereBetween('fecha_salida', [$fechaEntrada, $fechaSalida]);
        })->pluck('habitacion_id')->toArray();

        // Obtener las habitaciones disponibles que no están en la lista de habitaciones ocupadas y ordenarlas
        $habitacionesDisponibles = Habitaciones::whereNotIn('id', $reservasOcupadas)
                                            ->orderByRaw("FIELD(tipo, 'Suite', 'Doble', 'Simple')")
                                            ->get();

        return view('reserva.opciones', compact('habitacionesDisponibles', 'fechaEntrada', 'fechaSalida'));
    }

    // Mostrar el resumen de la reserva después de seleccionar las habitaciones
    public function showResumenForm(Request $request)
{
    $habitacionesSeleccionadas = Habitaciones::whereIn('id', $request->input('habitaciones'))->get();
    $fechaEntrada = $request->input('fecha_entrada');
    $fechaSalida = $request->input('fecha_salida');

    // Calcular el número de noches
    $startDate = new \DateTime($fechaEntrada);
    $endDate = new \DateTime($fechaSalida);
    $noches = $startDate->diff($endDate)->days;

    // Añadir límites de huéspedes para cada tipo de habitación
    $limitesHuespedes = [
        'Simple' => 2,
        'Doble' => 4,
        'Suite' => 5
    ];

    // Solo necesitamos asignar el precio total base de cada habitación por el número de noches
    $habitacionesConPrecios = $habitacionesSeleccionadas->map(function ($habitacion) use ($noches, $limitesHuespedes) {
        $habitacion->precio_total = $habitacion->precio * $noches;
        $habitacion->limite_huespedes = $limitesHuespedes[$habitacion->tipo] ?? 1;
        return $habitacion;
    });

    return view('reserva.reservar', compact('habitacionesConPrecios', 'fechaEntrada', 'fechaSalida', 'noches'));
}


    public function showPagoForm(Request $request)
    {
        // Obtener los IDs de las habitaciones desde el campo `huespedes`
        $habitacionesSeleccionadas = array_keys($request->input('huespedes', []));
        $numHuespedes = $request->input('huespedes', []);

        if (empty($habitacionesSeleccionadas)) {
            return redirect()->back()->with('status', 'Debe seleccionar al menos una habitación.');
        }

        // Obtener las habitaciones seleccionadas de la base de datos
        $habitaciones = Habitaciones::whereIn('id', $habitacionesSeleccionadas)->get();
        $total = 0;

        foreach ($habitaciones as $habitacion) {
            $habitacionId = $habitacion->id;
            $huespedes = $numHuespedes[$habitacionId];
            $precioBase = $habitacion->precio;

            // Calcular precio total para la habitación
            if ($habitacion->tipo === 'Doble' && $huespedes > 2) {
                $precioBase += ($huespedes - 2) * 250;
            } else if ($habitacion->tipo === 'Suite' && $huespedes > 4) {
                $precioBase += ($huespedes - 4) * 250;
            }

            $total += $precioBase * $request->input('noches');
        }

        // Obtener otras variables del request
        $fechaEntrada = $request->input('fecha_entrada');
        $fechaSalida = $request->input('fecha_salida');
        $noches = $request->input('noches');

        // Pasar los datos a la vista de pago
        return view('reserva.pago', compact('habitaciones', 'total', 'fechaEntrada', 'fechaSalida', 'noches', 'numHuespedes'));
    }

    // Confirmar el pago y registrar la reserva en la base de datos
    public function confirmarPago(Request $request)
    {
        // Registrar el cliente
        $cliente = Cliente::create([
            'nombre' => $request->input('nombre'),
            'email' => $request->input('email'),
            'telefono' => $request->input('telefono'),
        ]);

        // Proceder con la reserva
        foreach ($request->input('habitaciones') as $habitacionId) {
            Reservas::create([
                'cliente_id' => $cliente->id,
                'fecha_entrada' => $request->input('fecha_entrada'),
                'fecha_salida' => $request->input('fecha_salida'),
                'habitacion_id' => $habitacionId,
                'num_huespedes' => $request->input('num_huespedes')[$habitacionId],
                'fecha_reserva' => now(),
            ]);
        }

        return redirect()->route('reservar.opciones')->with('success', 'Reserva confirmada con éxito.');
    }
   
}
