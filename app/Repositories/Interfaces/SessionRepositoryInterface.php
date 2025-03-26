<?php 

namespace App\Repositories\Interfaces;

interface SessionRepositoryInterface{
    public function index();
    public function show($id);
    public function store(array $data);
    public function update($id, array $data);
    public function destroy($id);
}