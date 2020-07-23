<?php

$link = 'https://kupriyanov.amocrm.ru/oauth2/access_token'; //Формируем URL для запроса

$data = [
    'client_id' => 'bd4c3322-f332-40f3-a805-a5bcd547c933',
    'client_secret' => 'RAPRIhnNTesFuChlGntDmbalDqlohG6VgXB99rb9sctUzMIU4txZCRarZNSBSNhl',
    'grant_type' => 'refresh_token',
    'refresh_token' => 'def5020081a96098a29a1591409f547df7289f0a7f011d312d8ec3582bdb520f0821943bc4b1d226b6b94730cd67e9af1ea741806e38683c4e9599fe5a25154b8c3babc5c4c0baddc7987890409d40dfff9b40451306140144867224e3f4a4d6b05b46c02441b7fa771b95f911a1581918ee35b9c18702452d6e54a68618a03b52db503ac8983e27ed56ad4dcefa534a74b9bf4d0a03964d17307c73739e1c1d1de899fc0ebd1ad6fb5f2efde7b2f25cf11d559a08017dedfc16fe7df2a1843b5f72bd053813b934ae0be1790a18bd80e94999436218c3ffdd61a18268806386e4ae0e0a431b61e36aecdf86ee8de392f4c82bc91d6605cd29cfac9e72b67989d6eccfb3c7a78fa37620c4c3eea0e4f4fc92362cdb49c250ea43fb3669115ea5f9ccb74c730370b701a58f39f93392681651f8642d51cdf600ea423b1eec1de145024ec1df41bb9b9093db720a51e41f948af748e4ac6aaa4b7e1a155f5587f310808f089d28a608f6e6eba6a61e901d1c73a0a69566f752d5393a8506579f3b05be42dd26480f90ab4efb87c64c221a0f16ca6eddf2d111dd862a80ae664d818052d3ba9d7cffb363fce95ccefbe24da37daf65',
    'redirect_uri' => 'https://aea321b93e19.ngrok.io',
];

/**
* Нам необходимо инициировать запрос к серверу.
* Воспользуемся библиотекой cURL (поставляется в составе PHP).
* Вы также можете использовать и кроссплатформенную программу cURL, если вы не программируете на PHP.
*/
$curl = curl_init(); //Сохраняем дескриптор сеанса cURL
/** Устанавливаем необходимые опции для сеанса cURL  */
curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
//curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
curl_setopt($curl,CURLOPT_URL, $link);
curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
curl_setopt($curl,CURLOPT_HEADER, false);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
//curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
//curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
$out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
/** Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
$code = (int)$code;
$errors = [
    400 => 'Bad request',
    401 => 'Unauthorized',
    403 => 'Forbidden',
    404 => 'Not found',
    500 => 'Internal server error',
    502 => 'Bad gateway',
    503 => 'Service unavailable',
];

try {
/** Если код ответа не успешный - возвращаем сообщение об ошибке  */
    if ($code < 200 || $code > 204) {
    throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
    }
}
catch(\Exception $e) {
    die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
}

/**
* Данные получаем в формате JSON, поэтому, для получения читаемых данных,
* нам придётся перевести ответ в формат, понятный PHP
*/
$response = json_decode($out, true);

print_r($response);

$access_token = $response['access_token']; //Access токен
$refresh_token = $response['refresh_token']; //Refresh токен
$token_type = $response['token_type']; //Тип токена
$expires_in = $response['expires_in']; //Через сколько действие токена истекает