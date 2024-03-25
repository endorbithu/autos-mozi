<?php
declare(strict_types=1);

namespace App\Services;

use App\Contracts\RESTModelInterface;
use App\Contracts\RESTServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Az egyes REST Controller műveleteket egységesítjük itt,
 * A Controllerek action methodjaiban egy az egyben a RESTService-ugyanolyan nevű methodjának adjuk át a paramétereket,
 * és az action method visszatérési értéke is egy az egyben a RESTService megfelelő methodjának a
 * visszatérési értéke lesz. Ha eltérne egy művelet a RESTService-től, akkor mivel minden entitásnak
 * saját Controllere van, ki lehet ott ez(eke)t a különbségeket dolgozni.
 */
class RESTService implements RESTServiceInterface
{
    public function index(RESTModelInterface $entity, Request $request): JsonResponse
    {
        //Az entitás specifikus előkészített query betöltése
        $q = $entity::getItemsQuery();

        if (isset($request->where) && is_array($request->where)) {
            $q->where($request->where);
        }

        if (isset($request->orderBy) && !empty($request->orderBy)) {
            if (is_array($request->orderBy)) {
                $field = array_key_first($request->orderBy);
                $q->orderBy($field, $request->orderBy[$field]);
            } elseif (is_string($request->orderBy)) {
                $q->orderBy($request->orderBy);
            }
        }

        $pageLimit = $entity::getPageLimit();

        $count = $q->count();
        $q->limit($pageLimit);

        $maxPage = intval(ceil($count / $pageLimit));

        $page = abs(intval($request->query('page', 1)));

        $offset = (($page ?: 1) - 1) * $pageLimit;
        $q->offset($offset);

        $out = [
            'message' => 'Retrieved',
            'count' => $count,
            'page_limit' => $pageLimit,
            'last_page' => $maxPage,
            'current_page' => $page,
        ];

        if ($page < $maxPage) {
            $out['next_page'] = $page + 1;
        }

        if ($page > 1) {
            $out['prev_page'] = ($page - 1);
        }

        $out['data'] = $q->get()->toArray();

        return response()->json($out);
    }

    public function store(Model $entity, FormRequest $request): JsonResponse
    {
        $screening = $entity::query()->make($request->validated());
        $screening->save();
        return response()->json(['message' => 'Created', 'data' => ['id' => $screening->id]], 201);
    }

    public function show(RESTModelInterface $entity, int $id): JsonResponse
    {
        $q = $entity::getItemsQuery();
        $q->where('id', $id);

        return response()->json(['message' => 'Retrieved', 'data' => $q->first()->toArray()]);
    }

    public function update(Model $entity, FormRequest $request): JsonResponse
    {
        $entity->update($request->validated());
        return response()->json(['message' => 'Updated']);
    }

    public function destroy(Model $entity): JsonResponse
    {
        $entity->delete();
        return response()->json(['message' => 'Deleted']);
    }

}
