<?php

$api = new \MorningTrain\TogglApi\TogglApi($_ENV['TOGGL_API_TOKEN']);

test('has-organizations', function () use($api) {

    $organizationId = (string) $_ENV['TOGGL_ORGANIZATION_ID'];

    $organization = $api->getOrganizationById($organizationId);

    expect($organization)->toBeObject();
    expect($organization->id)->toEqual($organizationId);

});
