<?php

namespace App;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Qiniu\Auth;
use Qiniu\Config;
use Qiniu\Storage\ArgusManager;

class Censor
{
    public static function censorImageViaBaidu($image)
    {
        $url = 'https://aip.baidubce.com/rest/2.0/solution/v1/img_censor/v2/user_defined?access_token='.cache('censor_token');
        $params = [
            'imgUrl' => $image,
            'strategyId' => 32056, // 策略ID：https://ai.baidu.com/censoring#/strategylist
        ];

        $response = Http::asForm()->post($url, $params);

        if ($response->ok()) {

            if ($response->json('conclusionType') == 1) {
                return self::censorPass($image, $response->json());

            }

            return self::censorBlock($image, $response->json());

        }
    }

    public static function censorImageViaQiniu($image)
    {
        $auth = new Auth(env('AWS_ACCESS_KEY_ID'), env('AWS_SECRET_ACCESS_KEY'));

        $config = new Config();
        $argusManager = new ArgusManager($auth, $config);

        $body = '{
            "data":{
                "uri":"'.$image.'"
            },
            "params":{
                "scenes":[
                    "pulp",
                    "terror",
                    "politician"
                ]
            }
        }';

        $response = $argusManager->censorImage($body);

        if (data_get($response, '0.result.suggestion') == 'pass') {
            return self::censorPass($image, $response);
        }

        return self::censorBlock($image, $response);
    }

    public static function censorPass($image, $response)
    {
        info([
            'message' => '图片审核通过',
            'image' => $image,
            'response' => $response,
        ]);

        return true;
    }

    public static function censorBlock($image, $response)
    {
        $blockPlace = 'block'.parse_url($image, PHP_URL_PATH);
        // 移动图片至block文件夹，不删除
        Storage::disk('qiniu')->move(parse_url($image, PHP_URL_PATH), $blockPlace);

        logger()->warning([
            'message' => '图片审核不通过',
            'image' => Storage::disk('qiniu')->url($blockPlace).'?'.parse_url($image, PHP_URL_QUERY),
            'response' => $response,
        ]);

        return false;
    }
}
