<?php

require "vendor/autoload.php";

if (!function_exists('mb_strlen')) {
    function mb_strlen($string, $encoding = null): int
    {
        return strlen($string);
    }
}

if (!function_exists('mb_substr')) {
    function mb_substr($string, $start, $length = null, $encoding = null) {
        return substr($string, $start, $length);
    }
}

$search = (string)readline("Please enter company name: ");

$response = file_get_contents("https://data.gov.lv/dati/lv/api/3/action/datastore_search?q={$search}&resource_id=25e80bf3-f107-4ab4-89ef-251b5b9374e9");

$data = json_decode($response, true);

if (!isset($data['result']['records'])) {
    echo "No data found!\n";
    exit;
}

$table = new LucidFrame\Console\ConsoleTable();
$table
    ->addHeader('Company Name')
    ->addHeader('Registration Number')
    ->addHeader('Address');

foreach ($data['result']['records'] as $record) {
    $table->addRow()
        ->addColumn($record['name'])
        ->addColumn($record['regcode'])
        ->addColumn($record['address']);
}

$table->display();
