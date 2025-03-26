<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\SeatRepositoryInterface;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    protected $seatRepository;

    public function __construct(SeatRepositoryInterface $seatRepository)
    {
        $this->seatRepository = $seatRepository;
    }

    public function index()
    {
        $seats = $this->seatRepository->all();
        return response()->json($seats);
    }

    public function show($id)
    {
        $seat = $this->seatRepository->find($id);
        if (!$seat) {
            return response()->json(['message' => 'Seat not found'], 404);
        }
        return response()->json($seat);
    }

    public function store(Request $request)
    {
        $request->validate([
            'hall_id' => 'required|exists:halls,id',
            'start_number' => 'required|integer|min:1',
            'end_number' => 'required|integer|gt:start_number',
            'status' => 'required|in:available,reserved,maintenance'
        ]);

        try {
            $seats = $this->seatRepository->createBulk([
                'hall_id' => $request->hall_id,
                'start_number' => $request->start_number,
                'end_number' => $request->end_number,
                'status' => $request->status
            ]);
            
            return response()->json([
                'message' => 'Seats created successfully',
                'data' => $seats
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating seats',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'hall_id' => 'exists:halls,id',
            'seat_number' => 'sometimes|integer|min:1',
            'status' => 'sometimes|in:available,reserved,maintenance'
        ]);

        $seat = $this->seatRepository->update($id, $validated);
        if (!$seat) {
            return response()->json(['message' => 'Seat not found'], 404);
        }
        return response()->json([
            'message' => 'Seat updated successfully',
            'data' => $seat
        ]);
    }

    public function destroy($id)
    {
        $result = $this->seatRepository->delete($id);
        if (!$result) {
            return response()->json(['message' => 'Seat not found'], 404);
        }
        return response()->json(['message' => 'Seat deleted successfully'], 204);
    }

    public function getSeatsByHall($hallId)
    {
        $seats = $this->seatRepository->findByHall($hallId);
        return response()->json($seats);
    }

    public function getAvailableSeats($hallId)
    {
        $seats = $this->seatRepository->getSeatsByStatus($hallId, 'available');
        return response()->json($seats);
    }

    public function getReservedSeats($hallId)
    {
        $seats = $this->seatRepository->getSeatsByStatus($hallId, 'reserved');
        return response()->json($seats);
    }

    public function getSeatsByReservation($reservationId)
    {
        $seats = $this->seatRepository->getSeatsByReservation($reservationId);
        return response()->json($seats);
    }

    public function getSeatsWithSessionAndHall($sessionId)
    {
        $seats = $this->seatRepository->getSeatsWithSessionAndHall($sessionId);
        return response()->json($seats);
    }
}
