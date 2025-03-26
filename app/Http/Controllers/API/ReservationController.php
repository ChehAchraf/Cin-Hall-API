<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Seat;
use App\Models\Session;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    protected $reservationRepository;

    public function __construct(ReservationRepositoryInterface $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function index()
    {
        $reservations = $this->reservationRepository->all();
        return response()->json($reservations);
    }

    public function show($id)
    {
        $reservation = $this->reservationRepository->find($id);
        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
        return response()->json($reservation);
    }

    public function store(Request $request, $session_id, $seat_ids)
    {
        try {
            // Validate the request
            $validatedData = $request->validate([
                'status' => 'required|string|in:pending,confirmed,cancelled'
            ]);

            // Get the authenticated user
            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // Check if session exists
            $session = Session::find($session_id);
            if (!$session) {
                return response()->json(['message' => 'Session not found'], 404);
            }

            // Validate seat IDs
            $seatIds = explode(',', $seat_ids);
            if (empty($seatIds)) {
                return response()->json(['message' => 'No seats selected'], 400);
            }

            // Check if seats exist
            $seats = Seat::whereIn('id', $seatIds)->get();
            if ($seats->count() !== count($seatIds)) {
                return response()->json(['message' => 'One or more seats not found'], 404);
            }

            // Prepare the data for the repository
            $data = array_merge($validatedData, [
                'user_id' => $user->id,
                'session_id' => $session_id,
                'seats' => $seatIds
            ]);

            // Create the reservation using the repository
            $reservation = $this->reservationRepository->create($data);
            
            return response()->json($reservation, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, $id)
    {
        $reservation = $this->reservationRepository->update($id, $request->all());
        return response()->json($reservation);
    }

    public function destroy($id)
    {
        $reservation = $this->reservationRepository->delete($id);
        return response()->json(null, 204);
    }

    public function getUserReservations($userId)
    {
        $reservations = $this->reservationRepository->getUserReservations($userId);
        return response()->json($reservations);
    }

    public function getReservationSeats($id)
    {
        $reservation = $this->reservationRepository->find($id);
        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
        $seats = $reservation->seats;
        return response()->json($seats);
    }
}
