<!-- Incluye el CSS y JS de Flatpickr desde el CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="{{ asset('css/inicio.css') }}"> 
<link rel="stylesheet" href="{{ asset('css/habitaciones.css') }}">
@extends('home')
@section('tablas')
<div class="container">
<div class="pure-g">
  <div id="barra-estado-widget-1" class="pure-g no-seleccionable barra-estado-widget">
    <!-- Paso 1: Elige Fechas y Habitaciones -->
    <div class="pure-u-1-3 paso active">
      <div class="num">1</div>
      <div class="txt">Elige Fechas y Habitaciones</div>
    </div>
    <!-- Paso 2: Selecciona Habitación -->
    <div class="pure-u-1-3 paso">
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

    <h1>Selecciona tus Fechas</h1>
    <form action="{{ route('reservar.disponibles') }}" method="POST" id="fechas-form">
        @csrf
        <label for="fecha_rango">Seleccione fechas de entrada y salida</label>
        <input type="text" id="fecha_rango" class="form-control" placeholder="Selecciona el rango de fechas" required>
        <input type="hidden" name="fecha_entrada" id="fecha_entrada">
        <input type="hidden" name="fecha_salida" id="fecha_salida">
        <button type="submit" class="btn btn-primary" id="buscar-habitaciones-btn" disabled>Buscar Habitaciones Disponibles</button>
    </form>

    @if(isset($habitacionesDisponibles))
        <h2>Habitaciones Disponibles</h2>
        <form action="{{ route('reservar.resumen') }}" method="POST" id="form-seleccion-habitaciones">
            @csrf
            <input type="hidden" name="fecha_entrada" value="{{ $fechaEntrada }}">
            <input type="hidden" name="fecha_salida" value="{{ $fechaSalida }}">

            @foreach ($habitacionesDisponibles as $habitacion)
                <div class="card mb-3">
                    <img src="{{ $habitacion->imagen_url }}" class="card-img-top" alt="Imagen de habitación">
                    <div class="card-body">
                        <h5 class="card-title">{{ ucfirst($habitacion->tipo) }}</h5>
                        <p class="card-text">{{ $habitacion->descripcion }}</p>
                        <p class="card-text"><strong>Precio: </strong>${{ $habitacion->precio }} por noche</p>
                        <button type="button" class="btn btn-secondary agregar-habitacion" data-id="{{ $habitacion->id }}">
                            Agregar
                        </button>
                    </div>
                </div>
            @endforeach

            <!-- Sección de desglose (derecha) -->
            <div class="col-md-4">
                <div class="sticky-top" style="top: 20px;">
                <button type="submit" class="btn btn-primary mt-3">Continuar</button>
                </div>
            </div>
        </form>
    @endif
</div>

<!-- Flatpickr JS desde el CDN -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // Configuración de Flatpickr
    flatpickr("#fecha_rango", {
        mode: "range",
        minDate: "today",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                document.getElementById('fecha_entrada').value = selectedDates[0].toISOString().slice(0, 10);
                document.getElementById('fecha_salida').value = selectedDates[1].toISOString().slice(0, 10);
                document.getElementById('buscar-habitaciones-btn').disabled = false;
            } else {
                document.getElementById('buscar-habitaciones-btn').disabled = true;
            }
        }
    });

    // Script para manejar la selección y deselección de habitaciones
    const habitacionesSeleccionadas = new Set();

    document.querySelectorAll('.agregar-habitacion').forEach(button => {
        button.addEventListener('click', function () {
            const habitacionId = this.getAttribute('data-id');
            const form = document.getElementById('form-seleccion-habitaciones');

            if (habitacionesSeleccionadas.has(habitacionId)) {
                // Deseleccionar habitación
                habitacionesSeleccionadas.delete(habitacionId);
                
                // Eliminar el input oculto correspondiente
                const input = form.querySelector(`input[name="habitaciones[]"][value="${habitacionId}"]`);
                if (input) form.removeChild(input);

                // Cambiar el texto y el estilo del botón
                this.innerText = 'Agregar';
                this.classList.add('btn-primary');
                this.classList.remove('btn-secondary');
            } else {
                // Seleccionar habitación
                habitacionesSeleccionadas.add(habitacionId);
                
                // Crear un input oculto para enviar la habitación seleccionada
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'habitaciones[]';
                input.value = habitacionId;
                form.appendChild(input);

                // Cambiar el texto y el estilo del botón
                this.innerText = 'Seleccionado';
                this.classList.add('btn-secondary');
                this.classList.remove('btn-primary');
            }
        });
    });
</script>
@endsection
