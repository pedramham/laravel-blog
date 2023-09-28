<?php

namespace Admin\ApiBolg\Services;

use Admin\ApiBolg\Models\Post;
use Admin\ApiBolg\Models\Setting;
use Admin\ApiBolg\Models\Tag;
use Admin\ApiBolg\Traits\ModelTrait;

class SettingService
{
    use ModelTrait;

    public function storeSetting(array $request): array
    {
        app()->setLocale($request['local'] ?? 'en');
        $record = Setting::where(['id' => 1]);
        //if record exists update it so we have only one record
        if ($record->exists()) {
            Setting::findOrFail(1)->update($request);
        } else {
            Setting::create($request);
        }
        return $request;
    }
}
