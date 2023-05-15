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
