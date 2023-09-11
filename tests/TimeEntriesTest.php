<?php

$api = new \MorningTrain\TogglApi\TogglApi($_ENV['TOGGL_API_TOKEN']);

it('can create time entry', function () use($api) {
    $workspaceId = $_ENV['TOGGL_WORKSPACE_ID'];

    $entry = $api->createTimeEntry($workspaceId, [
        'wid' => (int) $workspaceId,
        'start' => '2021-01-01T00:00:00+00:00',
        'stop' => '2021-01-01T01:00:00+00:00',
        'created_with' => 'Toggl API TEST',
    ]);

    expect($entry)->toBeObject();
});

it('can get time entries', function () use($api) {
    $entry = $api->getTimeEntries();

    expect($entry)->toBeArray();
});
