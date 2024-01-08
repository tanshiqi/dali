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

    public static function fetch($fetchUrl, $censor = true)
    {
        $disk = self::getDisk();
        $filename = Str::random(16).'.png';
        $disk->getAdapter()->fetch($filename, $fetchUrl);
        if ($censor) {
            $filename = self::censor($filename);
        }
        info([
            'message' => '七牛转存成功',
            'response' => $filename,
        ]);

        return $filename;
    }

    public static function put64($imageBase64, $censor = true)
    {
        $disk = self::getDisk();
        $filename = Str::random(16).'.png';
        $disk->put($filename, base64_decode($imageBase64));
        if ($censor) {
            $filename = self::censor($filename);
        }
        info([
            'message' => '七牛转存Base64成功',
            'response' => $filename,
        ]);

        return $filename;
    }

    // 审核图片
    public static function censor($image)
    {
        $disk = self::getDisk();
        // 使用小尺寸审核
        $imgUrl = $disk->url($image).'?imageView2/0/w/800/format/jpg';

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
