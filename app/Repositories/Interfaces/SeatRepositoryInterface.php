<?php

namespace App\Repositories\Interfaces;

interface SeatRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function createBulk(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findByHall($hallId);
    public function getSeatsByStatus($hallId, $status = null);
    public function getSeatsByReservation($reservationId);
    public function getSeatsWithSessionAndHall($sessionId);
} 