<?php
declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface RESTServiceInterface
{
    public function index(RESTModelInterface $entity, Request $request): JsonResponse;

    public function store(Model $entity, FormRequest $request): JsonResponse;

    public function show(RESTModelInterface $entity, int $id): JsonResponse;

    public function update(Model $entity, FormRequest $request): JsonResponse;

    public function destroy(Model $entity): JsonResponse;
}
