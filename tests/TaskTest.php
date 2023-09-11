<?php

$api = new \MorningTrain\TogglApi\TogglApi($_ENV['TOGGL_API_TOKEN']);

it('can create a task', function () use ($api) {
    $workspaceId = (string)$_ENV['TOGGL_WORKSPACE_ID'];

    $task = $api->createTask($workspaceId, 194977014, [
        'active' => true,
        'name' => 'Test task',
    ]);
});

it('can update a task', function () use ($api) {
    $workspaceId = (string)$_ENV['TOGGL_WORKSPACE_ID'];

    $task = $api->updateTask($workspaceId, 194977014, 1234, [
        'active' => true,
        'name' => 'Test task',
    ]);
});
