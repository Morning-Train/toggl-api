<?php

require 'vendor/autoload.php';

require 'toggl/TogglApi.php';
//require 'toggl/TogglReportsApi.php';

define('TOGGL_API_KEY', 'a03a80361f3ad4d3c4b43afac5a975c5');

$toggl_client = new TogglApi(TOGGL_API_KEY);

echo '<pre>';

//$result = $toggl_client->getClients();

//$result = $toggl_client->getClientById(17376957);

//$result = $toggl_client->createClient(['name' => 'Test client', 'wid' => 723463] );

//$result = $toggl_client->updateClient(17381177, ['name' => 'Test client 3']);

//$result = $toggl_client->deleteClient(17381177);

//$result = $toggl_client->getProjectsByClientId(17376957);

$result = $toggl_client->getMe();

var_dump($result);

echo '</pre>';

?>