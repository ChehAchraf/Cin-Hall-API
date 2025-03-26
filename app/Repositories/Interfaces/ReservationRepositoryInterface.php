<?php

namespace App\Repositories\Interfaces;

interface ReservationRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getUserReservations($userId);
    public function getReservationSeats($id);
    public function checkSessionExists($sessionId);
    public function getSeatsByIds(array $seatIds);
    public function areSeatsAvailable(array $seatIds, $sessionId);
} 