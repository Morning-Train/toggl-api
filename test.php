<?php

require 'vendor/autoload.php';

require 'toggl/TogglApi.php';
require 'toggl/TogglReportsApi.php';

define('TOGGL_API_KEY', 'a03a80361f3ad4d3c4b43afac5a975c5');

define('TEST_WORKSPACE_ID', 723463);
define('TEST_USER_ID', 1689899);
define('TEST_USER_ID2', 1410149);
define('TEST_PROJECT_ID', 10892943);

define('TEST_TIMEENTRY_ID', 262405902);

$toggl_client = new TogglApi(TOGGL_API_KEY);
$toggl_reports_client = new TogglReportsApi(TOGGL_API_KEY);

echo '<pre>';

// $result = $toggl_client->getClients();

// $result = $toggl_client->getClientById(17376957);

// $result = $toggl_client->createClient(['name' => 'Test client', 'wid' => TEST_WORKSPACE_ID] );

// $result = $toggl_client->updateClient(17381177, ['name' => 'Test client 3']);

// $result = $toggl_client->deleteClient(17381177);

// $result = $toggl_client->getClientProjects(17376957);

// $result = $toggl_client->getActiveClientProjects(17376957);

// $result = $toggl_client->getInactiveClientProjects(17376957);

// $result = $toggl_client->getAllClientProjects(17376957);

// $result = $toggl_client->getMe();

// $result = $toggl_client->getDashboadForWorkspace(TEST_WORKSPACE_ID);

// $result = $toggl_client->createProject(['name' => 'Test projekt']);

// $result = $toggl_client->updateProject(10892836, ['name' => 'Test projekt 2']);

// $result = $toggl_client->deleteProject(10892836);

// $result = $toggl_client->deleteProjects([10892842, 10892843, 10892844]);

// $result = $toggl_client->getProjectUserRelations(TEST_PROJECT_ID);

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

// $result = $toggl_client->createProjectUser(['uid' => TEST_USER_ID2, 'pid' => TEST_PROJECT_ID]);

// $result = $toggl_client->updateProjectUser(18636919, ['manager' => true]);

// $result = $toggl_client->deleteProjectUser(18636919);

// $result = $toggl_client->updateProjectUsers([18636921, 18636913], ['rate' => 200]);

// $result = $toggl_client->deleteProjectUsers([18636939, 18636940]);

// $result = $toggl_client->createProjectUsers(['uid' => implode(',', [TEST_USER_ID, TEST_USER_ID2]), 'pid' => TEST_PROJECT_ID, 'rate' => 200]);

// $result = $toggl_client->updateMe(['fullname' => 'Bjarne Bonde']);

// $newTestWorkspaceId = 1057336;

// $result = $toggl_client->inviteUsersToWorkspace($newTestWorkspaceId, ['bjarne.bonde@hotmail.com']);

// $result = $toggl_client->updateWorkspaceUser(1421632, ['admin' => true]);

// $result = $toggl_client->deleteWorkspaceUser(1421632);

// $result = $toggl_client->getWorkspaceUserRelations($newTestWorkspaceId);

// $result = $toggl_client->getRunningTimeEntry();

// $result = $toggl_client->getTimeEntry(TEST_TIMEENTRY_ID);

// $result = $toggl_client->stopTimeEntry(TEST_TIMEENTRY_ID);

// $result = $toggl_client->createTimeEntry(['start' => '2015-08-15T14:31:00.000Z', 'duration' => 1200, 'description' => 'test', 'created_with' => 'API']);

// $result = $toggl_client->getTimeEntries();

// $result = $toggl_client->getTimeEntriesInRange('2015-08-15T01:00:00+02:00', '2015-08-16T00:01:00+02:00');

// $result = $toggl_client->deleteTimeEntry(262408850);

// $result = $toggl_client->startTimeEntry(['description' => 'test', 'created_with' => 'API']);

// $result = $toggl_client->updateTagsForTimeEntries([262400713, 262404562, 262405902, 262409049], ['tags' => ['Udvikling Back-end'], 'tag_action' => 'add']);

// $result = $toggl_reports_client->getDetailsReport(['workspace_id' => TEST_WORKSPACE_ID, 'user_agent' => 'API TEST']);

// $result = $toggl_reports_client->getSummaryReport(['workspace_id' => TEST_WORKSPACE_ID, 'user_agent' => 'API TEST']);

// $result = $toggl_reports_client->getProjectReport(['project_id' => TEST_PROJECT_ID, 'workspace_id' => TEST_WORKSPACE_ID, 'user_agent' => 'API TEST']);


// $result = $toggl_reports_client->getAvailableEndpoints();


if(isset($result)){
	var_dump($result);
}

echo '</pre>';

?>