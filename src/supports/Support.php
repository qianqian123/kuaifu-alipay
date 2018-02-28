<?php

namespace kuaifu\openalipay\supports;

class Support
{
    /**
     * Instance.
     *
     * @var Support
     */
    private static $instance;


    /**
     * Get instance.
     *
     *
     * @return Support
     */
    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get Alipay API result.
     *
     *
     * @param array  $data
     * @param string $publicKey
     *
     * @return Collection
     */
    public static function requestApi(array $data, $publicKey,$gateway)
    {
        $method = str_replace('.', '_', $data['method']).'_response';
        $result = self::getInstance()->curl($gateway, $data);
        $result = json_decode($result, true);
        if (!self::verifySign($result[$method], $publicKey, true, $result['sign'])) {
            throw new \Exception('Alipay Sign Verify FAILED', $result[$method]['code']);
        }

        if (isset($result[$method]['code']) && $result[$method]['code'] == '10000') {
            return $result[$method];
        }

        throw new \Exception(
            'Get Alipay API Error:'.$result[$method]['msg'],
            $result[$method]['code']
        );
    }

    public static function curl($url, $postFields = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $postBodyString = "";
        $encodeArray = Array();
        $postMultipart = false;
        if (is_array($postFields) && 0 < count($postFields)) {
            foreach ($postFields as $k => $v) {
                if ("@" != substr($v, 0, 1)) //判断是不是文件上传
                {
                    $postBodyString .= "$k=" . urlencode(self::characet($v, "UTF-8")) . "&";
                    $encodeArray[$k] = self::characet($v, "UTF-8");
                } else //文件上传用multipart/form-data，否则用www-form-urlencoded
                {
                    $postMultipart = true;
                    $encodeArray[$k] = new \CURLFile(substr($v, 1));
                }

            }
            unset ($k, $v);
            curl_setopt($ch, CURLOPT_POST, true);
            if ($postMultipart) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $encodeArray);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
            }
        }

        if ($postMultipart) {
            $headers = array('content-type: multipart/form-data;charset=' . "UTF-8");
        } else {
            $headers = array('content-type: application/x-www-form-urlencoded;charset=' . "UTF-8");
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $reponse = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new \Exception($reponse, $httpStatusCode);
            }
        }
        curl_close($ch);
        return $reponse;
    }

    public static function characet($data, $targetCharset) {
        if (!empty($data)) {
            $fileType ="UTF-8";
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
            }
        }
        return $data;
    }

    /**
     * Generate sign.
     *
     *
     * @param array  $parmas
     * @param string $privateKey
     *
     * @return string
     */
    public static function generateSign(array $parmas, $privateKey = null)
    {
        if (is_null($privateKey)) {
            throw new \Exception('Missing Alipay Config -- [private_key]', 1);
        }
        if (self::endsWith($privateKey, '.pem')) {
            $privateKey = openssl_pkey_get_private($privateKey);
        } else {
            $privateKey = "-----BEGIN RSA PRIVATE KEY-----\n".
                wordwrap($privateKey, 64, "\n", true).
                "\n-----END RSA PRIVATE KEY-----";
        }
        openssl_sign(self::getSignContent($parmas), $sign, $privateKey, OPENSSL_ALGO_SHA256);
        return base64_encode($sign);
    }

    /**
     * Verfiy sign.
     *
     *
     * @param array       $data
     * @param string      $publicKey
     * @param bool        $sync
     * @param string|null $sign
     *
     * @return bool
     */
    public static function verifySign(array $data, $publicKey = null, $sync = false, $sign = null)
    {
        if (is_null($publicKey)) {
            throw new \Exception('Missing Alipay Config -- [ali_public_key]', 2);
        }

        if (self::endsWith($publicKey, '.pem')) {
            $publicKey = openssl_pkey_get_public($publicKey);
        } else {
            $publicKey = "-----BEGIN PUBLIC KEY-----\n".
                wordwrap($publicKey, 64, "\n", true).
                "\n-----END PUBLIC KEY-----";
        }

        $toVerify = $sync ? json_encode($data, JSON_UNESCAPED_UNICODE) : self::getSignContent($data, true);
        return openssl_verify($toVerify, base64_decode($sign), $publicKey, OPENSSL_ALGO_SHA256);
    }

    /**
     * Get signContent that is to be signed.
     *
     *
     * @param array $data
     * @param bool  $verify
     *
     * @return string
     */
    public static function getSignContent(array $data, $verify = false)
    {
        ksort($data);

        $stringToBeSigned = '';
        foreach ($data as $k => $v) {
            if ($verify && $k != 'sign' && $k != 'sign_type') {
                $stringToBeSigned .= $k.'='.$v.'&';
            }
            if (!$verify && $v !== '' && !is_null($v) && $k != 'sign' && '@' != substr($v, 0, 1)) {
                $stringToBeSigned .= $k.'='.$v.'&';
            }
        }

        return trim($stringToBeSigned, '&');
    }

    /**
     * Convert encoding.
     *
     * @param string|array $data
     * @param string       $to
     * @param string       $from
     *
     * @return array
     */
    public static function encoding($data, $to = 'utf-8', $from = 'gb2312')
    {
        return self::doEncoding((array) $data, $to, $from);
    }



    public static function endsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if (substr($haystack, -strlen($needle)) === (string) $needle) {
                return true;
            }
        }

        return false;
    }
    /**
     * Convert encoding.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param array $array
     * @param string $to_encoding
     * @param string $from_encoding
     *
     * @return array
     */
    public static function doEncoding($array, $to_encoding, $from_encoding = 'gb2312')
    {
        $encoded = [];

        foreach ($array as $key => $value) {
            $encoded[$key] = is_array($value) ? self::encoding($value, $to_encoding, $from_encoding) :
                mb_convert_encoding($value, $to_encoding, $from_encoding);
        }

        return $encoded;
    }
}
