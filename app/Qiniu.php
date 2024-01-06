<?php

namespace App;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Qiniu
{
    protected static $disk = null;

    protected static function getDisk()
    {
        return self::$disk ?? Storage::disk('qiniu');
    }

    public static function fetch($fetchUrl)
    {
        $disk = self::getDisk();
        $filename = Str::random(16).'.png';
        $disk->getAdapter()->fetch($filename, $fetchUrl);
        $result = self::censor($filename);
        info([
            'message' => '七牛转存成功',
            'response' => $result,
        ]);

        return $result;
    }

    public static function put64($imageBase64)
    {
        $disk = self::getDisk();
        $filename = Str::random(16).'.png';
        $disk->put($filename, base64_decode($imageBase64));
        $result = self::censor($filename);
        info([
            'message' => '七牛转存Base64成功',
            'response' => $result,
        ]);

        return $result;
    }

    // 审核图片
    public static function censor($image)
    {
        $disk = self::getDisk();
        $imgUrl = $disk->url($image);

        // 审核图片
        if (Censor::censorImageViaBaidu($imgUrl)) {
            $info = Http::get($disk->url($image).'?imageInfo')->json();

            return array_merge([
                'key' => $image,
            ], $info);
        } else {
            return [
                'key' => 'block.png',
                'width' => 400,
                'height' => 400,
            ];
        }
    }
}
