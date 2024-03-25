<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\RESTServiceInterface;
use App\Http\Requests\MovieRequest;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Movie $movie, RESTServiceInterface $crudService, Request $request)
    {
        return $crudService->index($movie, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Movie $movie, MovieRequest $request, RESTServiceInterface $crudService)
    {
        return $crudService->store($movie, $request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie, RESTServiceInterface $crudService)
    {
        return $crudService->show($movie, $movie->id);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MovieRequest $request, Movie $movie, RESTServiceInterface $crudService)
    {
        return $crudService->update($movie, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie, RESTServiceInterface $crudService)
    {
        return $crudService->destroy($movie);
    }
}
