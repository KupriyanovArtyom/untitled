<?php

$link = 'https://kupridon.amocrm.ru/oauth2/access_token'; //Формируем URL для запроса

/** Соберем данные для запроса */
$data = [
    'client_id' => 'b6c9c828-8e1e-4b6d-9a2f-9193b044451b',
    'client_secret' => 'lJayBwCeWr3ANtUcOvzXXKV9t1spuRDx32mAHPn7Q4FrNlloW7bO8A0gMQRKYPCo',
    'grant_type' => 'authorization_code',
    'code' => 'def50200b828d6155d909462b2a8cd3cb83ec317ea969b802b6c37adabd466934f213e296a73ab0053e9c242ce76033a47b1ce6239054b9ec988b8aab76da2798c185f0129d4de3ea2990545607dbeb89a3f1f73c20503b0907adc78a933182004a966fc69cd5cde82a0f75e9f5568853ec7fe8f1f29c39edb677cc25339d4ab3b68829c9a25d2a77a86bbcf689bb84a980cb9efb2a276aa0788dad1e7125ded7d140e5f5d69c754dd827a577817c81be21f39bfcd308807e63eb0b80115f4b2bf728c6c67a556bf4cb832a090670bda8447ab1b710cd19eba1592bd292a6176ad86f2c64113fe05e2e77aad7f8f9e4a2efcd1c6e97349a02ff623bcffbd44d1ba495a52074af0182bf934e5b501ea01218e3bee26ac78e91f39292affcb0cdc2856547c5b7f5e54da399f9305c98eafa8a5340168a13835dc32428d792ce00f6b9901c14b9c4d19df46cddbc0dbe4b580de3717a7be17852433a699aba625e4d69e951ff532578c15f524d5dd3d3b82ce3fed8cd0a7e35cc855e459f12dd429fb9e647f4e9964f5a9ec29d0145507cd161af5f58118bf43e447de4da5d8af6fbbaf2ecd38532d33b073eee61ab4be01c10b560aa7f99d1a9e9a3bf9181d3402ef67bfcde26eec',
    'redirect_uri' => 'https://2ac277821154.ngrok.io',
];

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
print_r($out);
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

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
    if ($code < 200 || $code > 204) {
        throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
    }
}
catch(\Exception $e)
{
    die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
}
$response = json_decode($out, true);

print_r($response);

$access_token = $response['access_token']; //Access токен
$refresh_token = $response['refresh_token']; //Refresh токен
$token_type = $response['token_type']; //Тип токена
$expires_in = $response['expires_in']; //Через сколько действие токена истекает

//https://2401a8fd2ba7.ngrok.io/?code=def502003070f82cdc37e2d40ee91c51fbf12c343f5672a40b0a4c09aec13c407345e801191cf051c91a33c9c988fcef2c74434dc60f5c09d237cd5443a26505ab964f2b0606016e45dc0c47ddd0385a11a1a56f491f0b68b362892512ccf954e94865bafaa69928d2bb7ed2bb33f31484e49c169a2864b17040b62e9a956f54a4e3667dbbbf6729e51e12dfb4f32aaec18a764a3671b42f6619efea5e3c88d43a071ddb6cfd2874df3bb755bbec2f05db0512a2a9f83331e8bd9cc86aceb1bf7e96d9fab95f7e5d4c6e715b508576b6fbbf59c97d43cf60a0ee917338e8240674711535264458aed1f9f0c5e7d892d233494e98ebb2a7a60ff5e6421e09e79c50ffa13dddbdf1a56eb35b0bee6f133c97710244d12067f226c0e9225f7aa02eb4981e9168b9ccf86ee8ee2d1d5d9168d8746f6fbec6ec0cf82695319170374e122794111118b68a79904943dcb3b6d537e500191d260b2cc41056ebd4d362320de7b5cfb90f65ab5b6ee1fdeff2de7ad83a5f154718f57248dc88324d45a423ffc534c3bd49066538a0008fb988522270f6411b27ed5d83a8c930611ea1fbff373a1535bd155aced508bc4d4ea51bbc237f9e39440d67018561ab65db936d72763c24e26d1a688135
//&state=true
//&referer=kupriyanov.amocrm.ru
//&client_id=2385a797-3a3b-4171-90d4-221d86007e2e

//https://www.amocrm.ru/oauth?client_id=b6c9c828-8e1e-4b6d-9a2f-9193b044451b&state=check&mode=popup


