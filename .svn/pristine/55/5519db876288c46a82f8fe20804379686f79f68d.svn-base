<?php
/**
 * Created by PhpStorm.
 * User: varro
 * Date: 2018/4/28
 * Time: 11:51
 */

namespace Ku;

class Sms
{
    //短信API地址
    protected static $smsApi = 'http://v.juhe.cn/sms/send';
    //短信发送秘钥
    protected static $appKey = '9d3a798c3bf0e3a55a3a9544ad54b438';

    /**
     * 调用短信发送API
     *
     * @param string $phone 手机号
     * @param string $template 模板ID
     * @param string $content 模板变量
     * @return bool
     */
    public static function sendSms($phone, $template, $content)
    {
        $sendArray = [
            'mobile' => $phone,
            'tpl_id' => $template,
            'tpl_value' => $content,
            'key' => self::$appKey
        ];

        $url = self::$smsApi . '?' . http_build_query($sendArray);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if ($result['error_code'] != 0) {
            return false;
        }

        return true;
    }
}