<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\RESTServiceInterface;
use App\Http\Requests\ScreeningRequest;
use App\Models\Screening;
use Illuminate\Http\Request;

class ScreeningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Screening $screening, RESTServiceInterface $crudService, Request $request)
    {
        return $crudService->index($screening, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Screening $screening, ScreeningRequest $request, RESTServiceInterface $crudService)
    {
        return $crudService->store($screening, $request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Screening $screening, RESTServiceInterface $crudService)
    {
        return $crudService->show($screening, $screening->id);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScreeningRequest $request, Screening $screening, RESTServiceInterface $crudService)
    {
        return $crudService->update($screening, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Screening $screening, RESTServiceInterface $crudService)
    {
        return $crudService->destroy($screening);
    }
}
