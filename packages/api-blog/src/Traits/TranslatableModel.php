<?php

namespace Admin\ApiBolg\Traits;

use Illuminate\Support\Str;
use Spatie\Translatable\Events\TranslationHasBeenSetEvent;

trait TranslatableModel
{

    public function setTranslation(string $key, string $locale, $value): self
    {
        $this->guardAgainstNonTranslatableAttribute($key);

        $translations = $this->getTranslations($key);

        $oldValue = $translations[$locale] ?? '';

        if ($this->hasSetMutator($key)) {
            $method = 'set' . Str::studly($key) . 'Attribute';

            $this->{$method}($value, $locale);

            $value = $this->attributes[$key];
        } elseif ($this->hasAttributeSetMutator($key)) { // handle new attribute mutator
            $this->setAttributeMarkedMutatedAttributeValue($key, $value);

            $value = $this->attributes[$key];
        }

        $translations[$locale] = $value;
        $this->attributes[$key] = json_encode($translations, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        event(new TranslationHasBeenSetEvent($this, $key, $locale, $oldValue, $value));
        return $this;
    }
}
