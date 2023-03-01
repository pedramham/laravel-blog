<?php

namespace Admin\ApiBolg\Traits;

use Illuminate\Database\Eloquent\Collection;

trait ModelTrait
{

    public function store(array $input, $model)
    {
        return $model::create($input);
    }

    public function edit(array $input, $model): bool
    {
        return  $model::whereId($input['id'])->update($input);
    }

    public function show(array $input, $model)
    {
        return  $model::find($input['id']);
    }

    public function list($model): Collection
    {
        return  $model::all();
    }
}
