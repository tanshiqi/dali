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
            try {
                if ($response->json('conclusionType') == 1) {
                    info([
                        'message' => '图片审核通过',
                        'image' => $image,
                        'response' => $response->json(),
                    ]);

                    return true;

                } else {
                    logger()->warning([
                        'message' => '图片审核不通过',
                        'image' => $image,
                        'response' => $response->json(),
                    ]);
                    // Storage::disk('s3')->delete(pathinfo($image)['basename']);

                    return false;
                }
            } catch (\Throwable $th) {
                logger()->error([
                    'message' => '图片审核错误',
                    'image' => $image,
                    'response' => $response->json(),
                ]);

                return false;
            }
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

        $result = $argusManager->censorImage($body);

        if (data_get($result, '0.result.suggestion') == 'pass') {
            // 审核通过
            info([
                'message' => '图片审核通过',
                'image' => $image,
                'response' => $result,
            ]);

            return true;
        } else {
            // 审核不通过
            logger()->warning([
                'message' => '图片审核不通过',
                'image' => $image,
                'response' => $result,
            ]);
            // 移动图片至block文件夹
            // Storage::disk('disk')->delete(parse_url($image, PHP_URL_PATH));
            Storage::disk('qiniu')->move(parse_url($image, PHP_URL_PATH), 'block'.parse_url($image, PHP_URL_PATH));

            return false;
        }
    }
}
