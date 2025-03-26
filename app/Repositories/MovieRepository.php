<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use Illuminate\Support\Facades\Validator;

class MovieRepository implements MovieRepositoryInterface
{
    protected $model;

    public function __construct(Movie $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer',
            'min_age' => 'required|integer',
            'trailer_url' => 'required|string',
            'genre' => 'required',
            'image' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ];
        }

        $movie = $this->model->create($data);

        return [
            'status' => true,
            'message' => 'Movie created successfully',
            'data' => $movie
        ];
    }

    public function update($id, array $data)
    {
        $validator = Validator::make($data, [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'duration' => 'sometimes|integer',
            'min_age' => 'sometimes|integer',
            'trailer_url' => 'sometimes|string',
            'genre' => 'sometimes|string',
            'image' => 'sometimes|string'
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ];
        }

        $movie = $this->model->findOrFail($id);
        $movie->update($data);

        return [
            'status' => true,
            'message' => 'Movie updated successfully',
            'data' => $movie
        ];
    }

    public function delete($id)
    {
        $movie = $this->model->findOrFail($id);
        $movie->delete();

        return [
            'status' => true,
            'message' => 'Movie deleted successfully'
        ];
    }

    public function getMoviesByHall($hallId)
    {
        return $this->model->where('hall_id', $hallId)->get();
    }
} 