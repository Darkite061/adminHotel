@extends('home')

@section('tablas')
<meta name="csrf-token" content="{{ csrf_token() }}">

    <h1>Lista de Contactos</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Asunto</th>
                <th>Mensaje</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contactos as $contacto)
                <tr>
                    <td>{{ $contacto->nombre }}</td>
                    <td>{{ $contacto->correo }}</td>
                    <td>{{ $contacto->asunto }}</td>
                    <td>{{ Str::limit($contacto->mensaje, 50) }}</td>
                    <td>{{ $contacto->atendida ? 'Atendida' : 'Pendiente' }}</td>
                    <td>
                        <button class="btn btn-info" onclick="mostrarModalContacto({{ $contacto->id }})">
                            Ver
                        </button>
                        @if (!$contacto->atendida)
                            <button class="btn btn-success" onclick="marcarComoAtendida({{ $contacto->id }})">
                                Atender
                            </button>
                        @else
                            <span>Atendido</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="contactoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Información del Contacto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="contactoInfo">
                    <!-- Aquí se cargará la información del contacto -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="confirmAtender">Marcar como Atendido</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function mostrarModalContacto(id) {
            fetch(`/contactos/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('contactoInfo').innerHTML = `
                        <p><strong>Nombre:</strong> ${data.nombre}</p>
                        <p><strong>Correo:</strong> ${data.correo}</p>
                        <p><strong>Asunto:</strong> ${data.asunto}</p>
                        <p><strong>Mensaje:</strong> ${data.mensaje}</p>
                    `;
                    document.getElementById('confirmAtender').onclick = () => marcarComoAtendida(id);
                    new bootstrap.Modal(document.getElementById('contactoModal')).show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('No se pudo cargar la información del contacto.');
                });
        }

        function marcarComoAtendida(id) {
            if (confirm('¿Estás seguro de que quieres marcar este contacto como atendido?')) {
                fetch(`/contactos/${id}/atendida`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload(); // Recargar para reflejar cambios
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un error al procesar la solicitud.');
                });
            }
        }
        
    </script>
@endsection
