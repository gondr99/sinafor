<?php


namespace App;


class JWTToken
{
    protected $alg;
    protected $secret = "yyskills";

    function __construct()
    {
        $this->alg = "HS256"; //sha256방식으로 해싱
    }

    function hashing(array $data)
    {
        $header = json_encode(['alg'=>$this->alg, 'typ' => 'JWT']);
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE);

        //dd($header, $payload);
        $signature = base64_encode(hash_hmac('sha256', $header.$payload, $this->secret, true));

        return base64_encode($header . "." . $payload . "." . $signature);
    }

    function deHashing($token)
    {
        $parted = explode(".", base64_decode($token));
        $signature = $parted[2];
        $check = base64_encode(hash_hmac('sha256', $parted[0].$parted[1], $this->secret, true));
        if($check !== $signature){
            return false;
        }
        return json_decode($parted[1],true);
    }
}
