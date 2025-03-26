<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MoviesController;
use App\Http\Controllers\API\HallController;
use App\Http\Controllers\API\ReservationController;
use App\Http\Controllers\API\SessionController;
use App\Http\Controllers\API\SeatController;
use App\Http\Controllers\PayPalController;
use App\Models\Reservation;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return response()->json($request->user());
});
Route::post('/signin', [\App\Http\Controllers\AuthController::class, 'login']);
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

// the movies routing

Route::middleware('auth:api')->prefix('movies')->group(function () {
    Route::get('/', [MoviesController::class, 'index']);
    Route::get('/{id}', [MoviesController::class, 'show']);
    Route::post('/add', [MoviesController::class, 'create'])->middleware('role:admin');
    Route::put('/update/{id}', [MoviesController::class, 'update'])->middleware('role:admin');
    Route::delete('/delete/{id}', [MoviesController::class, 'delete'])->middleware('role:admin');
    Route::get('/hall/{hallId}', [MoviesController::class, 'getMoviesByHall']);
});

// Hall Management Routes
Route::middleware('auth:api')->prefix('halls')->group(function () {
    Route::get('/', [HallController::class, 'index']);
    Route::get('/show/{id}', [HallController::class, 'show']);
    Route::post('/add', [HallController::class, 'store'])->middleware('role:admin');
    Route::put('/update/{id}', [HallController::class, 'update'])->middleware('role:admin');
    Route::delete('/delete/{id}', [HallController::class, 'destroy'])->middleware('role:admin');
    Route::get('/{id}/available-seats', [HallController::class, 'availableSeats']);
    Route::get('/{id}/reserved-seats', [HallController::class, 'reservedSeats']);
});

Route::middleware('auth:api')->prefix('reservations')->group(function () {
    Route::get('/', [ReservationController::class, 'index']);
    Route::get('/{id}', [ReservationController::class, 'show']);
    Route::post('/add/{session_id}/{seat_ids}', [ReservationController::class, 'store']);
    Route::put('/update/{id}', [ReservationController::class, 'update']);
    Route::delete('/delete/{id}', [ReservationController::class, 'destroy']);
    Route::get('/user/{userId}', [ReservationController::class, 'getUserReservations']);
    Route::get('/{id}/seats', [ReservationController::class, 'getReservationSeats']);
});
Route::get('create-transaction', [ReservationController::class, 'processTransaction'])->name('createTransaction');

// the sessions routing

Route::middleware('auth:api')->prefix('session')->group(function () {
    Route::get('', [SessionController::class, 'index']);
    Route::post('add/{movie_id}/{hall_id}', [SessionController::class, 'store'])->middleware('role:admin');
    Route::put('update/{id}', [SessionController::class, 'update'])->middleware('role:admin');
    Route::delete('delete/{id}', [SessionController::class, 'destroy'])->middleware('role:admin');
});

// Seat Management Routes
Route::middleware('auth:api')->prefix('seats')->group(function () {
    Route::get('/', [SeatController::class, 'index']);
    Route::get('/{id}', [SeatController::class, 'show']);
    Route::post('/add', [SeatController::class, 'store'])->middleware('role:admin');
    Route::put('/update/{id}', [SeatController::class, 'update'])->middleware('role:admin');
    Route::delete('/delete/{id}', [SeatController::class, 'destroy'])->middleware('role:admin');
    Route::get('/hall/{hallId}', [SeatController::class, 'getSeatsByHall']);
    Route::get('/hall/{hallId}/available', [SeatController::class, 'getAvailableSeats']);
    Route::get('/hall/{hallId}/reserved', [SeatController::class, 'getReservedSeats']);
    Route::get('/reservation/{reservationId}', [SeatController::class, 'getSeatsByReservation']);
    Route::get('/session/{sessionId}', [SeatController::class, 'getSeatsWithSessionAndHall']);
});

Route::middleware(['auth:api', 'role:admin'])->get('/admin/data', function () {
    return response()->json(['message' => 'Welcome, Admin!']);
});

Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);

// Route::get('create-transaction', [PayPalController::class, 'createTransaction'])->name('createTransaction');
// Route::get('process-transaction', [PayPalController::class, 'processTransaction'])->name('processTransaction');
// Route::get('success-transaction', [PayPalController::class, 'successTransaction'])->name('successTransaction');
// Route::get('cancel-transaction', [PayPalController::class, 'cancelTransaction'])->name('cancelTransaction');

// Payment Routes

    Route::get('payment/success/{reservation_id}', [ReservationController::class, 'handlePaymentSuccess'])->name('payment.success');
    Route::get('payment/cancel/{reservation_id}', [ReservationController::class, 'handlePaymentCancel'])->name('payment.cancel');
