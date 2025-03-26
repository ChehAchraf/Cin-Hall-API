<?php

namespace App\Repositories;

use App\Models\Reservation;
use App\Models\Seat;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservationRepository implements ReservationRepositoryInterface
{
    protected $model;

    public function __construct(Reservation $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['seats', 'session', 'user'])->get();
    }

    public function find($id)
    {
        return $this->model->with(['seats', 'session', 'user'])->find($id);
    }

    public function create(array $data)
    {
        try {
            DB::beginTransaction();

            // Create the reservation
            $reservation = $this->model->create([
                'user_id' => $data['user_id'],
                'session_id' => $data['session_id'],
                'status' => $data['status']
            ]);

            // Attach seats to the reservation
            $reservation->seats()->attach($data['seats']);

            // Create tickets for each seat
            foreach ($data['seats'] as $seatId) {
                $reservation->tickets()->create([
                    'seat_id' => $seatId,
                    'qr_code' => Str::random(32)
                ]);
            }

            DB::commit();

            return $reservation->load(['seats', 'session', 'user', 'tickets']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        $reservation = $this->model->find($id);
        if ($reservation) {
            $reservation->update($data);
        }
        return $reservation;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function getUserReservations($userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->with(['seats', 'session', 'user'])
            ->get();
    }

    public function getReservationSeats($id)
    {
        return $this->model
            ->find($id)
            ->seats()
            ->get();
    }

    public function checkSessionExists($sessionId)
    {
        return $this->model->where('session_id', $sessionId)->exists();
    }

    public function getSeatsByIds(array $seatIds)
    {
        return $this->model->where('seats.id', $seatIds)->get();
    }

    public function areSeatsAvailable(array $seatIds, $sessionId)
    {
        return $this->model->where('seats.id', $seatIds)
            ->where('session_id', $sessionId)
            ->exists();
    }
}
