<?php

use AmoCRM\Models\LeadModel;

require __DIR__.'/vendor/autoload.php';
require __DIR__ . "/api_connect.php";

$leadsService = $apiClient->leads();

// Запрашиваем страницу сделок
$leadsCollection = $leadsService->get(null, [LeadModel::CONTACTS]);
// Выводим информацию о сделкаъ из списка
get_lead_info($leadsCollection, $apiClient);

for ($i = 0; $i < 20; $i++) {
    // Запрашиваем следующую страницу сделок
    $leadsCollection = $leadsService->nextPage($leadsCollection);
    // Выводим информацию о сделках из списка
    get_lead_info($leadsCollection, $apiClient);
    print "Страница {$i}\n";
}

// Функция для запроса информации о сделках
function get_lead_info($leadsCollection, $apiClient) {
    $i = 0;
    foreach ($leadsCollection->all() as $key => $value) {
        // Проверка на количество отправленных запросов, чтобы не превысить лимит
        if ($i > 2) {
            $i = 0;
            sleep(1);
        }

        print "Сделка: {$value->getname()}\n\n";

        // Проверка наличия привязанной компании
        if (!is_null($value->getCompany())) {
            // Запрос компании по id
            $company = $apiClient->companies()->getOne($value->getCompany()->getId());
            print "К сделке привязана компания {$company->getName()}\n";
        }

        // Проверка наличия привязанного контакта
        if (!is_null($value->getContacts())) {
            // Запрос контакта по id
            $contact = $apiClient->contacts()->getOne($value->getContacts()->first()->getId());
            print "К сделке привязан контакт {$company->getName()} с номером телефона {$contact->getCustomFieldsValues()->getBy('fieldCode', 'PHONE')->getValues()->first()->toArray()['value']}\n\n";
        }
        $i++;
    }
}

