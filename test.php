<?php

require 'vendor/autoload.php';

require 'toggl/TogglApi.php';
//require 'toggl/TogglReportsApi.php';

define('TOGGL_API_KEY', 'a03a80361f3ad4d3c4b43afac5a975c5');

$toggl_client = new TogglApi(TOGGL_API_KEY);

echo '<pre>';

//$result = $toggl_client->getWorkspaceClients();
	
//var_dump($result);

$result = $toggl_client->getClientById(17376957);

var_dump($result);

echo '</pre>';

?>