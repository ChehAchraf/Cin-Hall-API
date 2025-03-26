<?php 

namespace App\Repositories;

use App\Models\Session;

class SessionRepository
{
    protected $model;

    public function __construct(Session $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->with(['movie', 'hall'])->get()->map(function ($session) {
            return [
                'id' => $session->id,
                'movie_name' => $session->movie->title,
                'hall_name' => $session->hall->name,
                'start_time' => $session->start_time,
                'language' => $session->language,
                'type' => $session->type,
                'created_at' => $session->created_at,
                'updated_at' => $session->updated_at
            ];
        });
    }

    public function find($id)
    {
        $session = $this->model->with(['movie', 'hall'])->findOrFail($id);
        return [
            'id' => $session->id,
            'movie_name' => $session->movie->title,
            'hall_name' => $session->hall->name,
            'start_time' => $session->start_time,
            'language' => $session->language,
            'type' => $session->type,
            'created_at' => $session->created_at,
            'updated_at' => $session->updated_at
        ];
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $session = $this->model->findOrFail($id);
        $session->update($data);
        return $session;
    }

    public function delete($id)
    {
        $session = $this->model->findOrFail($id);
        return $session->delete();
    }
}
