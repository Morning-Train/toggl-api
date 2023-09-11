<?php

$api = new \MorningTrain\TogglApi\TogglApi($_ENV['TOGGL_API_TOKEN']);

test('has-workspace', function () use($api) {

    $workspaceId = (string) $_ENV['TOGGL_WORKSPACE_ID'];

    $workspaces = $api->getWorkspaces();

    expect($workspaces)->toBeArray();

    $workspaceIds = [];

    foreach ($workspaces as $workspace) {
        $workspaceIds[] = (string) $workspace->id;
    }

    expect($workspaceId)->toBeIn($workspaceIds);

});

it('can create project user', function () use($api) {
    $workspaceId = (string) $_ENV['TOGGL_WORKSPACE_ID'];

    $response = $api->createProjectUser($workspaceId, [
        'user_id' => 10651606,
        'project_id' => 194977014,
    ]);
});
