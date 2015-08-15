<?php

require 'vendor/autoload.php';

require 'toggl/TogglApi.php';
//require 'toggl/TogglReportsApi.php';

define('TOGGL_API_KEY', 'a03a80361f3ad4d3c4b43afac5a975c5');

define('TEST_WORKSPACE_ID', 723463);

$toggl_client = new TogglApi(TOGGL_API_KEY);

echo '<pre>';

// $result = $toggl_client->getClients();

// $result = $toggl_client->getClientById(17376957);

// $result = $toggl_client->createClient(['name' => 'Test client', 'wid' => TEST_WORKSPACE_ID] );

// $result = $toggl_client->updateClient(17381177, ['name' => 'Test client 3']);

// $result = $toggl_client->deleteClient(17381177);

// $result = $toggl_client->getClientProjects(17376957);

// $result = $toggl_client->getMe();

// $result = $toggl_client->getDashboadForWorkspace(TEST_WORKSPACE_ID);

// $result = $toggl_client->createProject(['name' => 'Test projekt']);

// $result = $toggl_client->updateProject(10892836, ['name' => 'Test projekt 2']);

// $result = $toggl_client->deleteProject(10892836);

// $result = $toggl_client->deleteProjects([10892842, 10892843, 10892844]);

// $result = $toggl_client->getProjectUserRelations(10872705);

// $result = $toggl_client->getWorkspaces();

// $result = $toggl_client->getWorkspace(TEST_WORKSPACE_ID);

// $result = $toggl_client->updateWorkspace(TEST_WORKSPACE_ID, ['name' => 'Morning Train']);

// $result = $toggl_client->getWorkspaceUsers(TEST_WORKSPACE_ID);

// $result = $toggl_client->getWorkspaceClients(TEST_WORKSPACE_ID);

// $result = $toggl_client->getWorkspaceProjects(TEST_WORKSPACE_ID);

// $result = $toggl_client->getWorkspaceTasks(TEST_WORKSPACE_ID);

// $result = $toggl_client->getWorkspaceTags(TEST_WORKSPACE_ID);

// $result = $toggl_client->createTag(['name' => 'test tag', 'wid' => TEST_WORKSPACE_ID]);

// $result = $toggl_client->updateTag(1517759, ['name' => 'test tag 2']);

// $result = $toggl_client->deleteTag(1517759);

// $result = $toggl_client->createTask(['name' => 'Test task '.time(), 'pid' => 10892640]);

// $result = $toggl_client->updateTask(7205119, ['name' => 'Test task 2 '.time()]);

// $result = $toggl_client->getTask(7205120);

// $result = $toggl_client->deleteTask(7205120);

// $result = $toggl_client->updateTasks([7205119, 7205121, 7205122], ['estimated_seconds' => 3600]);

// $result = $toggl_client->deleteTasks([7205119, 7205121, 7205122]);

// $result = $toggl_client->getProjectTasks(10892640);

if(isset($result)){
	var_dump($result);
}

echo '</pre>';

?>