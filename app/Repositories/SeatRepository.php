<?php

namespace App\Repositories;

use App\Models\Seat;
use App\Repositories\Interfaces\SeatRepositoryInterface;
use Illuminate\Support\Facades\DB;

class SeatRepository implements SeatRepositoryInterface
{
    protected $model;

    public function __construct(Seat $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findByHall($hallId)
    {
        return $this->model->where('hall_id', $hallId)->get();
    }

    public function getSeatsByStatus($hallId, $status = null)
    {
        $query = $this->model->where('hall_id', $hallId);

        if ($status === 'available') {
            $query->whereDoesntHave('reservations', function ($query) {
                $query->where('status', 'active');
            });
        } elseif ($status === 'reserved') {
            $query->whereHas('reservations', function ($query) {
                $query->where('status', 'active');
            });
        }

        return $query->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function createBulk(array $data)
    {
        try {
            DB::beginTransaction();

            $seats = [];
            for ($i = $data['start_number']; $i <= $data['end_number']; $i++) {
                $seats[] = [
                    'hall_id' => $data['hall_id'],
                    'seat_number' => $i,
                    'status' => $data['status'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            $createdSeats = $this->model->insert($seats);
            
            DB::commit();
            
            return $this->model
                ->where('hall_id', $data['hall_id'])
                ->whereBetween('seat_number', [$data['start_number'], $data['end_number']])
                ->get();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        $seat = $this->model->find($id);
        if ($seat) {
            $seat->update($data);
        }
        return $seat;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function getSeatsByReservation($reservationId)
    {
        return $this->model
            ->whereHas('reservations', function ($query) use ($reservationId) {
                $query->where('reservations.id', $reservationId);
            })
            ->get();
    }

    public function getSeatsWithSessionAndHall($sessionId)
    {
        return $this->model
            ->select('seats.*', 
                    'sessions.id as session_id', 
                    'sessions.start_time',
                    'sessions.movie_id',
                    'halls.id as hall_id', 
                    'halls.name as hall_name')
            ->join('halls', 'seats.hall_id', '=', 'halls.id')
            ->join('sessions', 'halls.id', '=', 'sessions.hall_id')
            ->where('sessions.id', $sessionId)
            ->with(['reservations' => function($query) use ($sessionId) {
                $query->where('session_id', $sessionId);
            }])
            ->get()
            ->map(function ($seat) {
                $seat->is_reserved = $seat->reservations->isNotEmpty();
                unset($seat->reservations);
                return $seat;
            });
    }
} 