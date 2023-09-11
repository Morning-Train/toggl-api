<?php

$api = new \MorningTrain\TogglApi\TogglApi($_ENV['TOGGL_API_TOKEN']);

it('can get all projects', function () use ($api) {
    $workspaceId = (string) $_ENV['TOGGL_WORKSPACE_ID'];

    $projects = $api->getProjects($workspaceId);

    expect($projects)->toBeArray();
});

it('can get a single project', function () use ($api) {
    $workspaceId = (string) $_ENV['TOGGL_WORKSPACE_ID'];

    $projects = $api->getProject($workspaceId, 194977014);

    expect($projects)->toBeObject();
});
