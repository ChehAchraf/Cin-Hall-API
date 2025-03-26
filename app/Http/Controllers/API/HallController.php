<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Hall;
use App\Repositories\Interfaces\SeatRepositoryInterface;
use Illuminate\Http\Request;

class HallController extends Controller
{
    protected $seatRepository;

    public function __construct(SeatRepositoryInterface $seatRepository)
    {
        $this->seatRepository = $seatRepository;
    }

    public function index()
    {
        $halls = Hall::all();
        return response()->json($halls);
    }

    public function show($id)
    {
        $hall = Hall::findOrFail($id);
        return response()->json($hall);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'total_seats' => 'required|integer|min:1',
            'type' => 'required|string',
        ]);

        $hall = Hall::create($validated);
        return response()->json($hall, 201);
    }

    public function update(Request $request, $id)
    {
        $hall = Hall::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'capacity' => 'sometimes|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $hall->update($validated);
        return response()->json($hall);
    }

    public function destroy($id)
    {
        $hall = Hall::findOrFail($id);
        $hall->delete();
        return response()->json(null, 204);
    }

    public function availableSeats($id)
    {
        $seats = $this->seatRepository->getAvailableSeats($id);
        return response()->json($seats);
    }

    public function reservedSeats($id)
    {
        $seats = $this->seatRepository->getReservedSeats($id);
        return response()->json($seats);
    }
}
