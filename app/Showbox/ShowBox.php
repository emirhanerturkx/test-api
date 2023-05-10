<?php

namespace App\ShowBox;

use App\ShowBox\Api\Episode;
use App\ShowBox\Api\Movie;
use App\ShowBox\Api\Search;
use App\ShowBox\Api\TV;

class ShowBox
{
    public function encrypt($message)
    {
        $key = "123d6cedf626dy54233aa1w6";
        $iv = "wEiphTn!";
        $keyHex = utf8_decode($key);
        $ivHex = utf8_decode($iv);
        $encrypted = openssl_encrypt($message, "des-ede3-cbc", $keyHex, OPENSSL_RAW_DATA, $ivHex);
        $data = base64_encode($encrypted);
        return $data;
    }

    public function hash($message)
    {
        return md5($message);
    }

    public function generateVerifyToken($str, $str2, $str3)
    {
        if ($str) {
            return $this->hash($this->hash($str2) . $str3 . $str);
        }
        return null;
    }

    public function generateId($length)
    {
        $result = "";
        $characters = "abcdefghijklmnopqrstuvwxyz0123456789";
        $charactersLength = strlen($characters);

        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[rand(0, $charactersLength - 1)];
        }

        return $result;
    }

    public function getExpiryDate()
    {
        return time() + (60 * 60 * 12);
    }

    public function buildQuery($pramters)
    {
        $appId = "com.tdo.showbox";
        // $default = config('lara-showbox.default');

        $defaultPramters = array(
            'appid' => $appId,
            'expired_date' => $this->getExpiryDate(),
            'platform' => 'android',
            'app_version' => '11.5',
            'channel' => 'Website',

            'childmode' => 0,
            'lang' => "tr",
            "pagelimit" => 10,
        );

        $pramters = array_filter($pramters, function ($value) {
            return $value !== null;
        });

        $pramters = array_merge($defaultPramters, $pramters);
        return json_encode($pramters);
    }


    public function generateEncryptedBody($query)
    {
        $key = "123d6cedf626dy54233aa1w6";
        $appKey = "moviebox";
        $encryptedQuery = $this->encrypt($query);
        $appKeyHash = md5($appKey);

        $newBody = array(
            'app_key' => $appKeyHash,
            'verify' => $this->generateVerifyToken($encryptedQuery, $appKey, $key),
            'encrypt_data' => $encryptedQuery
        );

        $newBody = json_encode($newBody);

        $words = utf8_encode($newBody);
        $base64 = base64_encode($words);

        return array(
            'data' => $base64,
            'appid' => "27",
            'platform' => "android",
            'version' => "129",
            'medium' => "Website&token" . $this->generateId(32)
        );
    }


    public function request($data, $server = null)
    {
        $default = config('lara-showbox.default');
        $servers = config('lara-showbox.servers');

        $headers = [
            'Platform' => 'android',
            'Accept' => 'charset=utf-8',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.79 Safari/537.36',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $path = "https://showbox.shegu.net/api/api_client/index/";

        $curlOptions = array(
            CURLOPT_URL => $path,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
        );

        $curl = curl_init();
        curl_setopt_array($curl, $curlOptions);
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    public function call($parameters, $server = null)
    {
        return $this->request(
            $this->generateEncryptedBody($this->buildQuery($parameters)),
            $server
        );
    }
}
