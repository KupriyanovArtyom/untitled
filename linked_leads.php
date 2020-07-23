<?php

use AmoCRM\Exceptions\AmoCRMApiException;

require __DIR__.'/vendor/autoload.php';
require __DIR__ . "/api_connect.php";

// Подключаем сделки
$leadsService = $apiClient->leads();
// Подключаем контакты
$contactsService = $apiClient->contacts();
// Подключаем компании
$companiesService = $apiClient->companies();

try {
    // Получаем списки сделок, контактов и компаний
    $leadsCollection = $leadsService->get();
    $contactsCollection = $contactsService->get();
    $companiesCollection = $companiesService->get();
    // Привязываем контакты и компании к сделкам
    link_leads($leadsCollection, $contactsCollection, $companiesCollection, $apiClient);
    for ($i = 0; $i < 19; $i++) {
        // Получаем следующую страницу списка сделок, контактов и компаний
        $leadsCollection = $leadsService->nextPage($leadsCollection);
        $contactsCollection = $contactsService->nextPage($contactsCollection);
        $companiesCollection = $companiesService->nextPage($companiesCollection);
        // Привязываем контакты и компании к сделкам
        link_leads($leadsCollection, $contactsCollection, $companiesCollection, $apiClient);
        print "Страница {$i}\n";
    }
} catch (AmoCRMApiException $e) {
    print $e->getMessage();
    die;
}

function link_leads($leadsCollection, $contactsCollection, $companiesCollection, $apiClient) {
    $i = 0;
    foreach ($leadsCollection->all() as $key => $value) {
        // Проверка на количество отправленных запросов, чтобы не превысить лимит
        if ($i > 5) {
            $i = 0;
            sleep(1);
        }
        $links = new \AmoCRM\Collections\LinksCollection();
        $links->add($contactsCollection->all()[$key]);
        $links->add($companiesCollection->all()[$key]);
        print "Message: К сделке под id: {$value->getId()} привязана компания {$companiesCollection->all()[$key]->getId()} и контакт {$contactsCollection->all()[$key]->getId()}\n";
        // Привязка компании и контакта к сделке
        $apiClient->leads()->link($value, $links);
        $i++;
    }
}

