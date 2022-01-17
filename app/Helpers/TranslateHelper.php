<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Google\Cloud\Translate\V2\TranslateClient;
use App\Models\Translation;


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
                function () use ($string,$options,$target) {
                    $translation = Translation::where('target',$target)->where('string',$string)->first();

                    if(! $translation){
                        $translator = app(TranslateClient::class);
                        $result = $translator->translate($string, $options)['text'];
                        $translation = Translation::create(compact($string,$target,$result));
                    }

                    return $translation->result;
                }
        );
    }
}