<?php

/*
 * This file is part of the sysoce/laravel-translation package.
 *
 * (c) Sysoce <sysoce@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/sysoce/laravel-translation
 */

namespace Sysoce\Translation;

use Sysoce\Translation\Models\Translation;

class Translation
{
    public function translate($data)
    {
        if(empty($data['source']) || empty('target') || empty('input')) {
            throw new Exception("Error Processing Request", 1);
        }
        $string = $data['source'] . $data['target'] . $data['input'];
        $hash_id = Translation::generateHash($string);
        $translation = Translation::where('hash_id', $hash_id)->first();

        if(!$translation) {
            if(!isset($data['text'])) {
                $data['text'] = app('App\Contracts\Translator')->translate($data);
            }
            $translation = Translation::create($data);
        }
        return $translation;
    }

    public function translateBatch($data)
    {
        $translations = [];
        foreach ($data as $translation_data) {
            $translations[] = $this->translate($translation_data);
        }
        return $translations;
    }
}
