@extends('home')
@section('tablas')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Reservas</title>

    <!-- Incluir Bootstrap desde la CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-4">
        <h1 class="mb-4">Lista de Reservas</h1>

        <!-- Botón para redirigir a la página de reservas -->
        <a href="{{ route('reservar.opciones') }}"  class="btn btn-primary ml-auto">Reserva Ahora</a>

        <!-- Tabla de reservas con estilo Bootstrap -->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Usuario ID</th>
                    <th>Fecha Entrada</th>
                    <th>Fecha Salida</th>
                    <th>ID Habitacion</th>
                    <th>Num Huespedes</th>
                    <th>Fecha Reserva</th>
                    <th>Fecha Creación</th>
                    <th>Fecha Actualización</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
@foreach($reservas as $reserva)
<tr>
    <td>{{ $reserva->id }}</td>
    <td>{{ $reserva->cliente_id }}</td>
    <td>{{ $reserva->fecha_entrada }}</td>
    <td>{{ $reserva->fecha_salida }}</td>
    <td>{{ $reserva->habitacion_id }}</td>
    <td>{{ $reserva->num_huespedes }}</td>
    <td>{{ $reserva->fecha_reserva }}</td>
    <td>{{ $reserva->created_at }}</td>
    <td>{{ $reserva->updated_at }}</td>
    <td>
        <!-- Mostrar "Aceptada" o "No aceptada" -->
        @if($reserva->aceptada)
            <span class="badge badge-success">Aceptada</span>
        @else
            <span class="badge badge-danger">No aceptada</span>
        @endif
    </td>
</tr>
@endforeach
</tbody>

        </table>
    </div>

    <!-- Incluir los scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

@endsection
