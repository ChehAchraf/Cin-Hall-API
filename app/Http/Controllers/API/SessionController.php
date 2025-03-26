<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Repositories\SessionRepository;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    protected $sessionRepository;

    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    public function index()
    {
        $sessions = $this->sessionRepository->getAll();
        return response()->json($sessions);
    }

    public function show($id)
    {
        $session = $this->sessionRepository->find($id);
        return response()->json($session);
    }

    public function store(Request $request, $movie_id, $hall_id)
    {
        $validatedData = $request->validate([
            'start_time' => 'required|date',
            'language' => 'required|string|in:FR,EN,AR',
            'price' => 'required|numeric|min:0', 
            'type' => 'required|string|in:normal,vip',
        ]);
        
        $sessionData = array_merge($validatedData, [
            'movie_id' => $movie_id,
            'hall_id' => $hall_id,
        ]);
    
        $session = $this->sessionRepository->create($sessionData);
        return response()->json($this->sessionRepository->find($session->id), 201);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'start_time' => 'sometimes|required|date',
            'language' => 'sometimes|required|string|in:FR,EN,AR',
            'price' => 'sometimes|required|numeric|min:0', 
            'type' => 'sometimes|required|string|in:normal,vip',
        ]);

        $this->sessionRepository->update($id, $validatedData);
        return response()->json($this->sessionRepository->find($id));
    }

    public function destroy($id)
    {
        $this->sessionRepository->delete($id);
        return response()->json(null, 204);
    }
}
