<?php

namespace Admin\ApiBolg\Traits;

use Admin\ApiBolg\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\App;
use Session;
use Log;

trait ModelTrait
{

    public function store(array $input, $model)
    {
        app()->setLocale($input['local'] ?? 'en');
        /* @var Model $model * */
        return $model::create($input);
    }

    public function edit(array $input, $model)
    {
        app()->setLocale($input['local'] ?? 'en');
        /* @var Model $model * */
        return $model::findOrFail($input['id'])->update($input);
    }

    public function show(array $input, $model)
    {

        return $model::find($input['id']);
    }

    public function listPagination($model, $input): LengthAwarePaginator
    {
        /* @var Model $model * */
        return $model::listPagination($input);
    }

    public function softDelete(array $input, $model): bool
    {
        $id = $model::find(($input['id']));

        if ($id) {
            return $id->delete();
        }
        return false;
    }

    public function delete(array $input, $model): bool
    {
        /* @var Model $model * */
        $id = $model::withTrashed('deleted_at')->find($input['id']);

        if ($id) {
            return $id->forceDelete();
        }
        return false;
    }

    public function restoreDelete(array $input, $model): bool
    {
        /* @var Model $model * */
        $id = $model::withTrashed()->find($input['id']);
        if ($id) {
            return $id->restore();
        }
        return false;
    }
}
