<?php

namespace Admin\ApiBolg\Traits;

use Illuminate\Database\Eloquent\Collection;

trait ModelTrait
{

    public function store(array $input, $model)
    {
        return $model::create($input);
    }

    public function edit(array $input, $model)
    {
        return  $model::findOrFail($input['id'])->update($input);
    }

    public function show(array $input, $model)
    {
        return  $model::find($input['id']);
    }

    public function listPagination($model, $input): Collection
    {
        return  $model::listPagination($input);
    }

    public function softDelete(array $input, $model): bool
    {
        $id =  $model::find(($input['id']));
        if ($id) {
           return  $id->delete();
        }
        return false;
    }

    public function delete(array $input, $model): bool
    {
        $id =  $model::withTrashed()->find($input['id']);
        if ($id) {
            return  $id->forceDelete();
        }
        return false;
    }

    public function restoreDelete(array $input, $model): bool
    {
        $id =  $model::withTrashed()->find($input['id']);
        if ($id) {
            return  $id->restore();
        }
        return false;
    }
}
