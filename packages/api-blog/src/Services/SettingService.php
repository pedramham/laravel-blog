<?php

namespace Admin\ApiBolg\Services;

use Admin\ApiBolg\Models\Post;
use Admin\ApiBolg\Models\Setting;
use Admin\ApiBolg\Models\Tag;
use Admin\ApiBolg\Traits\ModelTrait;

class SettingService
{
    use ModelTrait;

    public function show(array $input, $model)
    {
        return $model::with('tags', 'category')->get()->find($input['id']);
    }

    public function storeSetting(array $request): array
    {
        $record = Setting::where(['id' => 1]);
        //if record exists update it so we have only one record
        if ($record->exists()) {
            Setting::findOrFail(1)->update($request);
        }

        Setting::create($request);

        return $request;
    }

}
