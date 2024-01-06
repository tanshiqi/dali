<?php

namespace App;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Qiniu
{
    //
    public static function fetch($fetchUrl)
    {
        $disk = Storage::disk('qiniu');
        $filename = Str::random(16).'.png';
        $disk->getAdapter()->fetch($filename, $fetchUrl);

        $result = Http::get($disk->url($filename).'?imageInfo')->json();

        return array_merge([
            'key' => $filename,
        ], $result);

    }
}
