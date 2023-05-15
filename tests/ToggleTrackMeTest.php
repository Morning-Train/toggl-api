<?php

$api = new \MorningTrain\TogglApi\TogglApi($_ENV['TOGGL_API_TOKEN']);

test('get-info-about-myself', function () use($api) {

    $workspaceId = $_ENV['TOGGL_WORKSPACE_ID'];

    $rsp = $api->getMe();

    expect($rsp)->toBeObject();
    expect($rsp)->toHaveKeys([
        'id',
        'email',
        'fullname',
    ]);

    $myId = $rsp->id;

    $users = $api->getWorkspaceUsers($workspaceId);

    expect($users)->toBeArray();

    $userIds = [];
    foreach ($users as $user) {
        $userIds[] = $user->uid;
    }

    expect($myId)->toBeIn($userIds);
});
