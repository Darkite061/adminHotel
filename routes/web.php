<?php
use App\Http\Controllers\ComentariosController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ReservasController;
use App\Http\Controllers\DisponiblesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactoController;

Route::get('/', function () {
    return view('/auth/login');
});

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Ruta para mostrar la página de opciones donde se seleccionan las fechas
Route::get('/reservar/opciones', [ReservasController::class, 'showOptionsForm'])->name('reservar.opciones');

// Ruta para procesar las fechas seleccionadas y mostrar las habitaciones disponibles
Route::post('/reservar/disponibles', [ReservasController::class, 'showAvailableRooms'])->name('reservar.disponibles');

// Ruta para procesar la selección de habitaciones y mostrar el resumen
Route::post('/reservar/resumen', [ReservasController::class, 'showResumenForm'])->name('reservar.resumen');

// Ruta para mostrar la vista de pago con el monto total y detalles de las habitaciones seleccionadas
Route::post('/reservar/pago', [ReservasController::class, 'showPagoForm'])->name('reservar.pago');

// Ruta para confirmar el pago y registrar las habitaciones en la reserva
Route::post('/reservar/confirmar-pago', [ReservasController::class, 'confirmarPago'])->name('reservar.confirmarPago');

Route::get('/reserva', [ReservasController::class, 'index'])->name('reservas');



// Ruta para ver la lista de contactos
Route::get('/contactos', [ContactoController::class, 'index'])->name('contactos.index');

// Ruta para obtener la información de un contacto específico
Route::get('/contactos/{id}', [ContactoController::class, 'show'])->name('contactos.show');

// Ruta para marcar un contacto como atendido
Route::post('/contactos/{id}/atendida', [ContactoController::class, 'marcarComoAtendida'])->name('contactos.atendida');