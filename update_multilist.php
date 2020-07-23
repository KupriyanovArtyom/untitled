<?php

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;

require __DIR__.'/vendor/autoload.php';
require __DIR__ . "/api_connect.php";

$contactsService = $apiClient->contacts();

// Получаем список из 50 контактов
try {

    $contactsCollection = $contactsService->get();
    update_multilist($contactsCollection, $apiClient);

    for ($i = 0; $i < 23; $i++) {
        $contactsCollection = $contactsService->nextPage($contactsCollection);
        update_multilist($contactsCollection, $apiClient);
        print "Страница {$i}\n";
    }

} catch (AmoCRMApiException $e) {
    print "Code: ".$e->getCode()." Error: ".$e->getMessage();
    die;
}

function update_multilist($contactsCollection, $apiClient) {
    // Инициализация класса коллекции контактов
    foreach ($contactsCollection->all() as $key => $value) {
        $customFields = $value->getCustomFieldsValues();

        if (isset($customFields)){
            $phoneField = $customFields->getBy('fieldCode', 'PHONE');
            // Проверяем, есть ли у пользователя поле PHONE
            if (empty($phoneField)) {
                $phoneField = (new MultitextCustomFieldValuesModel())->setFieldCode('PHONE');
                $customFields->add($phoneField);
            }
        }
        else {
            $customFields = new \AmoCRM\Collections\CustomFieldsValuesCollection();
            $phoneField = (new MultitextCustomFieldValuesModel())->setFieldCode('PHONE');
            $customFields->add($phoneField);
            $value->setCustomFieldsValues($customFields);
        }

        // Добавляем новое значение мультисписка типа телефон
        $phoneField->setValues(
            (new MultitextCustomFieldValueCollection())
                ->add(
                    (new MultitextCustomFieldValueModel())
                        ->setEnum('WORKDD')
                        ->setValue('+7912'.rand(500,1000))
                )
        );
        print "Message: У контакта под id: {$value->getId()} был обновлен телефон\n";
    }
    // Обновляем список контактов
    $apiClient->contacts()->update($contactsCollection);
    sleep(1);
}
