<?php

namespace MiniUpload;

use League\OAuth2\Client\Token\AccessTokenInterface;

class oAuthService implements \AmoCRM\OAuth\OAuthServiceInterface{

    public function saveOAuthToken(AccessTokenInterface $accessToken, string $baseDomain): void {
        $json = file_get_contents(__DIR__."/conf/conf.json");
        $arr = json_decode($json);
        $arr['access_token'] = $accessToken->getToken();
        $json = json_encode($arr);
        file_put_contents(__DIR__."/conf/conf.json", $json);
    }
}