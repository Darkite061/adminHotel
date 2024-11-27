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
    <div class="pure-u-1-3 paso active">
      <div class="num">2</div>
      <div class="txt">Selecciona Numero de huespedes</div>
    </div>
    <!-- Paso 3: Datos del Huésped -->
    <div class="pure-u-1-3 paso">
      <div class="num">3</div>
      <div class="txt">Pago y confirmacion</div>
    </div>
  </div>
</div>
    <h1>Selecciona Numero de huespedes</h1>

    <div class="row">
        <!-- Sección de habitaciones (izquierda) -->
        <div class="col-md-8">
        <form id="reservaForm" action="{{ route('reservar.pago') }}" method="POST">
            @csrf
            <input type="hidden" name="fecha_entrada" value="{{ $fechaEntrada }}">
            <input type="hidden" name="fecha_salida" value="{{ $fechaSalida }}">
            <input type="hidden" name="noches" value="{{ $noches }}">

            @foreach ($habitacionesConPrecios as $habitacion)
                <div class="card mb-3">
                    <img src="{{ $habitacion->imagen_url }}" class="card-img-top" alt="Imagen de habitación">
                    <div class="card-body">
                        <h5 class="card-title">{{ ucfirst($habitacion->tipo) }}</h5>
                        <p class="card-text">{{ $habitacion->descripcion }}</p>
                        <p class="card-text"><strong>Precio por noche: </strong>${{ $habitacion->precio }}</p>
                        <p class="card-text"><strong>Total por {{ $noches }} noches: </strong><span id="precio_total_{{ $habitacion->id }}">${{ $habitacion->precio * $noches }}</span></p>

                        <h6>Huéspedes</h6>
                        <div class="input-group mb-3">
                            <button type="button" class="btn btn-secondary" onclick="actualizarHuespedes('{{ $habitacion->id }}', '{{ $habitacion->tipo }}', -1)">-</button>
                            <input type="number" id="num_huespedes_{{ $habitacion->id }}" name="huespedes[{{ $habitacion->id }}]" value="1" min="1" max="{{ $habitacion->num_huespedes }}" readonly class="form-control text-center">
                            <button type="button" class="btn btn-secondary" onclick="actualizarHuespedes('{{ $habitacion->id }}', '{{ $habitacion->tipo }}', 1)">+</button>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Update button to submit form directly -->
            <button type="submit" class="btn btn-primary mt-4">Proceder al Pago</button>
        </form>


        </div>

        <!-- Sección de desglose (derecha) -->
        <div class="col-md-4">
            <div class="sticky-top" style="top: 20px;">
                <h4>Desglose Total</h4>
                <p><strong>Total noches:</strong> {{ $noches }}</p>
                <p><strong>Desglose de precio:</strong> <span id="desglose_precio_total">$0</span></p>
            </div>
        </div>

        <div class="pure-g politicas-hotel">
        <div class="separador-horizontal"><div></div></div>

        <a id="hotel-habitaciones-terminos-condiciones" href="#">Términos y Condiciones del Hotel</a>
        <div id="terminos-condiciones-habitaciones" style="">
				<p><strong>Hora de Entrada</strong> ( check in ):&nbsp;&nbsp;15 hrs.</p>				
                <p><strong>Hora de Salida</strong> ( check out ):&nbsp;&nbsp;12 hrs.</p>												

                <p><strong>Cancelaciones:</strong> No se realizará ningún cargo o penalidad si la reservación se cancela o modifica con 3 días de anticipación. Si cancelas o modificas la reservación con menos de 3 días de anticipación a la fecha de tu llegada, o no te presentas, el establecimiento cargará 1 noche de estancia como penalización.</p>
				<p id="zt-formas-de-pago"><strong>Formas de Pago:</strong>
                <span>Visa, Master Card, Depósito Bancario o Transferencia					</span></p>
				<p id="politicas_ninios"><strong class="txt-ca">niños:</strong>
				<span>Este Hotel considera niños  hasta 12 años</span>				</p>
			</div>
        </div>
    </div>
    </div>
</div>

<script>
        function actualizarDesglosePrecio() {
            let precioTotalGlobal = 0;
            @foreach ($habitacionesConPrecios as $habitacion)
                habitacionId = {{ $habitacion->id }};
                precioBase = {{ $habitacion->precio }};
                numHuespedes = parseInt(document.getElementById(`num_huespedes_${habitacionId}`).value);
                precioTotal = precioBase;

                // Aplicar cargos adicionales si hay más huéspedes de los permitidos
                if ("{{ $habitacion->tipo }}" === 'Doble' && numHuespedes > 2) {
                    precioTotal += (numHuespedes - 2) * 250;
                } else if ("{{ $habitacion->tipo }}" === 'Suite' && numHuespedes > 4) {
                    precioTotal += (numHuespedes - 4) * 250;
                }

                // Multiplicar el precio total por el número de noches
                precioTotal *= {{ $noches }};
                document.getElementById(`precio_total_${habitacionId}`).textContent = `$${precioTotal}`;
                precioTotalGlobal += precioTotal;
            @endforeach

            document.getElementById('desglose_precio_total').textContent = precioTotalGlobal;
        }

        // Actualiza la cantidad de huéspedes
        window.actualizarHuespedes = function(habitacionId, tipoHabitacion, cambio) {
            const input = document.getElementById(`num_huespedes_${habitacionId}`);
            const maxHuespedes = { 'Simple': 2, 'Doble': 4, 'Suite': 5 }[tipoHabitacion];
            let numHuespedes = parseInt(input.value) + cambio;

            if (numHuespedes > maxHuespedes) {
                numHuespedes = maxHuespedes;
            } else if (numHuespedes < 1) {
                numHuespedes = 1;
            }
            input.value = numHuespedes;
            actualizarDesglosePrecio();
        };

        // Inicializa el desglose de precio al cargar la página 
        document.addEventListener("DOMContentLoaded", function() { 
            let precioTotalGlobal = 0;
            @foreach ($habitacionesConPrecios as $habitacion)
                habitacionId = {{ $habitacion->id }};
                precioBase = {{ $habitacion->precio }};
                numHuespedes = parseInt(document.getElementById(`num_huespedes_${habitacionId}`).value);
                precioTotal = precioBase;

                // Aplicar cargos adicionales si hay más huéspedes de los permitidos
                if ("{{ $habitacion->tipo }}" === 'Doble' && numHuespedes > 2) {
                    precioTotal += (numHuespedes - 2) * 250;
                } else if ("{{ $habitacion->tipo }}" === 'Suite' && numHuespedes > 4) {
                    precioTotal += (numHuespedes - 4) * 250;
                }

                // Multiplicar el precio total por el número de noches
                precioTotal *= {{ $noches }};
                document.getElementById(`precio_total_${habitacionId}`).textContent = `$${precioTotal}`;
                precioTotalGlobal += precioTotal;
            @endforeach

            document.getElementById('desglose_precio_total').textContent = precioTotalGlobal;
        });
</script>
@endsection
