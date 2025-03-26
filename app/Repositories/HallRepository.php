<?php

namespace App\Repositories;

use App\Models\Hall;
use App\Models\Seat;
use App\Repositories\Interfaces\HallRepositoryInterface;
use Illuminate\Support\Facades\Validator;

class HallRepository implements HallRepositoryInterface
{
    public function index()
    {
        return Hall::all();
    }

    public function show($id)
    {   
        return Hall::findOrFail($id);
    }

    public function store(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'type' => 'required|in:normal,vip',
            'total_seats' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ];
        }

        $hall = Hall::create($data);

        for ($i = 1; $i <= $data['total_seats']; $i++) {
            Seat::create([
                'hall_id' => $hall->id,
                'seat_number' => $i,
                'status' => 'available'
            ]);
        }

        return [
            'status' => true,
            'message' => 'Hall created successfully',
            'data' => $hall->load('seats')
        ];
    }

    public function update($id, array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:normal,vip',
            'total_seats' => 'sometimes|integer|min:1',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ];
        }

        $hall = Hall::findOrFail($id);
        $hall->update($data);

        return [
            'status' => true,
            'message' => 'Hall updated successfully',
            'data' => $hall->load('seats')
        ];
    }

    public function delete($id)
    {
        $hall = Hall::findOrFail($id);
        $hall->seats()->delete();
        $hall->delete();

        return [
            'status' => true,
            'message' => 'Hall deleted successfully'
        ];
    }

    public function AvailableSeats($hallId)
    {
        $hall = Hall::findOrFail($hallId);
        return $hall->seats()->where('status', 'available')->get();
    }

    public function reservedSeats($hallId)
    {
        $hall = Hall::findOrFail($hallId);
        return $hall->seats()->where('status', 'reserved')->get();
    }
}
