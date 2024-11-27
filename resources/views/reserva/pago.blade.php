<link rel="stylesheet" href="{{ asset('css/inicio.css') }}"> 
<link rel="stylesheet" href="{{ asset('css/habitaciones.css') }}">
@extends('home')
@section('tablas')
<div class="container">
    <div class="pure-g">
        <div id="barra-estado-widget-1" class="pure-g no-seleccionable barra-estado-widget">
            <!-- Paso 1: Elige Fechas y Habitaciones -->
            <div class="pure-u-1-3 paso">
                <div class="num">1</div>
                <div class="txt">Elige Fechas y Habitaciones</div>
            </div>
            <!-- Paso 2: Selecciona Habitación -->
            <div class="pure-u-1-3 paso">
                <div class="num">2</div>
                <div class="txt">Selecciona Número de huéspedes</div>
            </div>
            <!-- Paso 3: Datos del Huésped -->
            <div class="pure-u-1-3 paso active">
                <div class="num">3</div>
                <div class="txt">Pago y confirmación</div>
            </div>
        </div>
    </div>
    <h1>Pago de Reserva</h1>

    <div class="row">
        <!-- Información de la reserva (separador a la derecha) -->
        <div class="col-md-4 order-md-2">
            <div class="card border-dark mb-3">
                <div class="card-header text-center bg-success text-white"><strong>Detalles de la Reserva</strong></div>
                <div class="card-body">
                    <p><strong>Fecha de Entrada:</strong> {{ $fechaEntrada }}</p>
                    <p><strong>Fecha de Salida:</strong> {{ $fechaSalida }}</p>
                    <p><strong>Noches:</strong> {{ $noches }}</p>

                    @foreach ($habitaciones as $habitacion)
                        @php
                            $huespedes = $numHuespedes[$habitacion->id];
                            $precioBase = $habitacion->precio;
                            $costoAdicional = 0;

                            if ($habitacion->tipo === 'Doble' && $huespedes > 2) {
                                $costoAdicional = ($huespedes - 2) * 250;
                            } else if ($habitacion->tipo === 'Suite' && $huespedes > 4) {
                                $costoAdicional = ($huespedes - 4) * 250;
                            }

                            $totalPorNoche = $precioBase + $costoAdicional;
                            $totalPorHabitacion = $totalPorNoche * $noches;
                        @endphp
                        <p><strong>Habitación {{ ucfirst($habitacion->tipo) }}:</strong></p>
                        <p>Precio base: ${{ $habitacion->precio }}</p>
                        @if($costoAdicional > 0)
                            <p>Costo adicional: ${{ $costoAdicional }}</p>
                        @endif
                        <p>Total por {{ $noches }} noches: ${{ $totalPorHabitacion }}</p>
                    @endforeach
                    <p><strong>Total:</strong> ${{ $total }}</p>
                </div>
            </div>
        </div>

        <!-- Sección de habitaciones -->
        <div class="col-md-8 order-md-1">
            <div class="card border-dark mb-3">
                <div class="card-header bg-success text-white d-flex align-items-center">
                    <i class="fas fa-bed me-2"></i><strong>Habitaciones Seleccionadas</strong>
                </div>
                <div class="card-body">
                    <!-- Información detallada de cada habitación -->
                    @foreach ($habitaciones as $habitacion)
                    @php
                    $huespedes = $numHuespedes[$habitacion->id];
                    $precioBase = $habitacion->precio;
                    $costoAdicional = 0;

                    if ($habitacion->tipo === 'Doble' && $huespedes > 2) {
                        $costoAdicional = ($huespedes - 2) * 250;
                    } else if ($habitacion->tipo === 'Suite' && $huespedes > 4) {
                        $costoAdicional = ($huespedes - 4) * 250;
                    }

                    $totalPorNoche = $precioBase + $costoAdicional;
                    $totalPorHabitacion = $totalPorNoche * $noches;
                @endphp
                        <div class="card mb-3 border-secondary">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="{{ $habitacion->imagen_url }}" class="img-fluid rounded-start" alt="Imagen de habitación">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                    <h5 class="card-title">{{ ucfirst($habitacion->tipo) }}</h5>
                                    <p class="card-text">{{ $habitacion->descripcion }}</p>
                                    <p class="card-text"><strong>Precio base por noche: </strong>${{ $habitacion->precio }}</p>
                                    @if($costoAdicional > 0)
                                        <p class="card-text"><strong>Costo adicional por huéspedes extras: </strong>${{ $costoAdicional }}</p>
                                    @endif
                                    <p class="card-text"><strong>Número de huéspedes: </strong>{{ $huespedes }}</p>
                                    <p class="card-text"><strong>Total por {{ $noches }} noches: </strong>${{ $totalPorHabitacion }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Sección de datos del huésped -->
            <div class="card border-dark mb-3">
                <div class="card-header bg-success text-white d-flex align-items-center">
                    <i class="fas fa-user me-2"></i><strong>Datos del Huésped</strong>
                </div>
                <div class="card-body">
                    <form action="{{ route('reservar.confirmarPago') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>
                </div>
            </div>

            <!-- Sección de pago -->
            <div class="card border-dark mb-3">
                <div class="card-header bg-success text-white d-flex align-items-center">
                    <i class="fas fa-credit-card me-2"></i><strong>Formulario de Pago</strong>
                </div>
                <div class="card-body">
                        <input type="hidden" name="fecha_entrada" value="{{ $fechaEntrada }}">
                        <input type="hidden" name="fecha_salida" value="{{ $fechaSalida }}">
                        <input type="hidden" name="total" value="{{ $total }}">
                        @foreach ($habitaciones as $habitacion)
                            <input type="hidden" name="habitaciones[]" value="{{ $habitacion->id }}">
                            <input type="hidden" name="num_huespedes[{{ $habitacion->id }}]" value="{{ $numHuespedes[$habitacion->id] }}">
                        @endforeach
                        <input type="hidden" name="cliente_id" value="{{ session('cliente_id') }}">
                        <button type="submit" class="btn btn-primary mt-4">Pagar y Confirmar Reserva</button>
                    </form>
                </div>
            </div>
            <div class="pure-g politicas-hotel">
                <div class="separador-horizontal"><div></div></div>
                <div id="terminos-condiciones-habitaciones" style="">											
                        <p><strong>Condiciones de pago:</strong> Después de efectuar la reservación el Hotel cargará el importe de 1 noche.
                        Tarjetas de Crédito Aceptadas: Mastercard, Visa.</p>
                        <p id="zt-formas-de-pago"><strong>Políticas de Cancelación:</strong>
                        <span>No se realizará ningún cargo o penalidad si la reservación se cancela o modifica con 3 días de anticipación. Si cancelas o modificas la reservación con menos de 3 días de anticipación a la fecha de tu llegada, o no te presentas, el establecimiento cargará 1 noche de estancia como penalización.</span></p>
                    </div>
                </div>
            </div>
    </div>
</div>
@endsection
