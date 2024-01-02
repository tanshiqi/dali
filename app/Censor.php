<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Qiniu\Auth;
use Qiniu\Config;
use Qiniu\Storage\ArgusManager;

class Censor
{
    public static function censorImage($image)
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
            return true;
        } else {
            // 审核不通过
            logger()->warning([
                'message' => '图片审核不通过',
                'image' => $image,
                'response' => $result,
            ]);
            // 删除图片
            Storage::disk('s3')->delete(pathinfo($image)['basename']);

            return false;
        }
    }
}
