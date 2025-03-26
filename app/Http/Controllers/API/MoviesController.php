<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\MovieRepository;

class MoviesController extends Controller
{
    private $movieRepository;

    public function __construct(MovieRepository $movieRepository){
        $this->movieRepository = $movieRepository;
    }

    public function index(){
        $movies = $this->movieRepository->all();
        return response()->json($movies);
    }

    public function find($id){
        $movie = $this->movieRepository->find($id);
        return response()->json($movie);
    }

    public function create(Request $request){
        $movie = $this->movieRepository->create($request->all());
        return response()->json($movie, 201);
    }

    public function update(Request $request, $id){
        $movie = $this->movieRepository->update($id, $request->all());
        return response()->json($movie);
    }

    public function delete($id){
        $movie = $this->movieRepository->delete($id);
        return response()->json($movie);
    }

    public function getMoviesByHall($hallId){
        $movies = $this->movieRepository->getMoviesByHall($hallId);
        return response()->json($movies);
    }
}
