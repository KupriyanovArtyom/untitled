<?php

use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\LeadModel;

require __DIR__.'/vendor/autoload.php';
require __DIR__."/connect.php";

// Функции поочередного добавления компаний, контактов и сделок
$companiesList = addCompanies($apiClient)->all();
$contactsList = addContacts($apiClient)->all();
$leadsList = addLeads($apiClient)->all();

$i = 0;
// Проходим по списку сделок и привязываем контакт и компанию
foreach ($leadsList as $key => $value) {
    if ($i > 5) {
        $i = 0;
        sleep(1);
    }
    // Инициализируем класс ссылок
    $links = new \AmoCRM\Collections\LinksCollection();
    // Добавляем контакт в ссылку
    $links->add($contactsList[$key]);
    // Добавляем компанию в ссылку
    $links->add($companiesList[$key]);
    // Привязываем ссылку к сделке
    $apiClient->leads()->link($value, $links);
    $i++;
}

// Функция добавления компаний
function addCompanies(\AmoCRM\Client\AmoCRMApiClient $apiClient) {
    // Инициализируем коллекцию компаний
    $companiesList = new \AmoCRM\Collections\CompaniesCollection();
    for ($i = 0; $i < 4; $i++) {
        $companies = new \AmoCRM\Collections\CompaniesCollection();
        for ($j = 0; $j < 250; $j++) {
            // Создаем компанию
            $company = new \AmoCRM\Models\CompanyModel();
            // Устанавливаем название компании
            $company->setName("SuperCompany".$j);
            // Добавляем компанию в коллекцию компаний
            $companies->add($company);
        }
        try {
            // Отправляем коллекцию контактов в CRM
            $companiesRequest = $apiClient->companies()->add($companies);
            // Добавление коллекции компаний в общую коллекцию
            foreach ($companiesRequest->all() as $value) {
                $companiesList->add($value);
            }
            sleep(1);
        } catch (AmoCRMApiException $e) {
            print $e->getMessage()."\n";
            print $e->getCode();
            die;
        }
    }
    return $companiesList;
}

// Функция добавления контактов
function addContacts(\AmoCRM\Client\AmoCRMApiClient $apiClient) {
    // Инициализируем коллекцию контактов
    $contactsList = new \AmoCRM\Collections\ContactsCollection();
    for ($i = 0; $i < 4; $i++) {
        $contacts = new \AmoCRM\Collections\ContactsCollection();
        for ($j = 0; $j < 250; $j++) {
            // Создаем контакт
            $contact = new \AmoCRM\Models\ContactModel();
            // Устанавливаем имя контакта
            $contact->setName("SuperContact".$j);
            // Создаем кастомные поля
            $fields = new \AmoCRM\Collections\CustomFieldsValuesCollection();
            // Создаем кастомное поля типа телефон
            $phoneField = (new MultitextCustomFieldValuesModel())->setFieldCode('PHONE');
            $fields->add($phoneField);

            // Устанавливаем значение телефона
            $phoneField->setValues(
                (new MultitextCustomFieldValueCollection())
                    ->add(
                        (new MultitextCustomFieldValueModel())
                            ->setEnum('WORKDD')
                            ->setValue('+79123'.$j)
                    )
            );
            // Привязываем кастомное поле к контакту
            $contact->setCustomFieldsValues($fields);
            $contacts->add($contact);
        }
        try {
            // Отправляем коллекцию контактов в CRM
            $contactsRequest = $apiClient->contacts()->add($contacts);
            // Добавление коллекции контактов в общую коллекцию
            foreach ($contactsRequest->all() as $value) {
                $contactsList->add($value);
            }
            sleep(1);
        } catch (AmoCRMApiException $e) {
            print $e->getMessage()."\n";
            print $e->getCode();
            die;
        }
    }
    return $contactsList;
}

// Функция добавления сделок
function addLeads(\AmoCRM\Client\AmoCRMApiClient $apiClient) {
    // Инициализируем коллекцию сделок
    $leadsList = new LeadsCollection();
    for ($i = 0; $i < 4; $i++) {
        $leads = new LeadsCollection();
        for ($j = 0; $j < 250; $j++) {
            // Создаем сделку
            $lead = new LeadModel();
            // Устанавливаем имя сделки
            $lead->setName("SuperLead".$j);
            // Добавляем сделку в коллекцию сделок
            $leads->add($lead);
        }
        try {
            // Отправляем коллекцию сделок в CRM
            $leadsRequest = $apiClient->leads()->add($leads);
            // Добавление коллекции сделок в общую коллекцию
            foreach ($leadsRequest->all() as $value) {
                $leadsList->add($value);
            }
            sleep(1);
        } catch (AmoCRMApiException $e) {
            print $e->getMessage()."\n";
            print $e->getCode();
            die;
        }
    }
    return $leadsList;
}



