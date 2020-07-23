<?php

require __DIR__.'/vendor/autoload.php';

// Открываем файл с конфигом
$json = file_get_contents(__DIR__."/conf/conf.json");
$init_arr = json_decode($json,1);

$apiClient = new \AmoCRM\Client\AmoCRMApiClient($init_arr['client_id'], $init_arr['client_secret'], $init_arr['redirect_uri']);

$oAuthConfig = new \MiniUpload\oAuthConfig();
$oAuthService = new \MiniUpload\oAuthService();

$apiClientFactory = new \AmoCRM\AmoCRM\Client\AmoCRMApiClientFactory($oAuthConfig, $oAuthService);
$apiClient = $apiClientFactory->make();
$accessToken = new \League\OAuth2\Client\Token\AccessToken([
    "access_token" => $init_arr['access_token'],
    "refresh_token" => $init_arr['refresh_token'],
    "expires" => (int) time() + 86400
]);

// Добавляем access token
$apiClient->setAccessToken($accessToken)
    ->setAccountBaseDomain("kupridon.amocrm.ru")
    ->onAccessTokenRefresh(
        function (\League\OAuth2\Client\Token\AccessTokenInterface $accessToken, string $baseDomain) {
            print_r($accessToken->getToken());
        });