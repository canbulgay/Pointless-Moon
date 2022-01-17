<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Google\Cloud\Translate\V2\TranslateClient;


class TranslateHelper
{
    public function translate($string,array $options = [] ){

        if (isset($options['target'])) {
            $target = $options['target'];
        }else{
            $target = config('services.google_cloud.translation_default_target');
        }

        return Cache::rememberForever(
            'translation: '. $target . ":" .$string, 
                function () use ($string,$options) {
                        $translator = app(TranslateClient::class);
                        return $translator->translate($string, $options)['text'];
        });

    }
}