<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    protected $movieRepository;

    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function index()
    {
        $movies = $this->movieRepository->all();
        return response()->json([
            'status' => true,
            'data' => $movies
        ]);
    }

    public function show($id)
    {
        $movie = $this->movieRepository->find($id);
        return response()->json([
            'status' => true,
            'data' => $movie
        ]);
    }

    public function store(Request $request)
    {
        $result = $this->movieRepository->create($request->all());
        return response()->json($result);
    }

    public function update(Request $request, $id)
    {
        $result = $this->movieRepository->update($id, $request->all());
        return response()->json($result);
    }

    public function destroy($id)
    {
        $result = $this->movieRepository->delete($id);
        return response()->json($result);
    }

    public function getMoviesByHall($hallId)
    {
        $movies = $this->movieRepository->getMoviesByHall($hallId);
        return response()->json([
            'status' => true,
            'data' => $movies
        ]);
    }
} 