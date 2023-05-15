<?php

$api = new \MorningTrain\TogglApi\TogglApi($_ENV['TOGGL_API_TOKEN']);

test('can-manage-clients', function () use ($api) {
    $workspaceId = (string)$_ENV['TOGGL_WORKSPACE_ID'];

    $clientName = "Our test client";

    $currentClients = $api->getWorkspaceClients($workspaceId);
    expect($currentClients)->toBeArray();
    if (count($currentClients) > 0) {
        foreach ($currentClients as $currentClient) {
            expect($currentClient)->toBeObject();
            if ($clientName === $currentClient->name) {
                $deleteRsp = $api->deleteClient($workspaceId, $currentClient->id);
                expect($deleteRsp)->toBe($currentClient->id);
            }
        }
    }

    $createdClient = $api->createClient($workspaceId, $clientName);

    expect($createdClient)->toBeObject();
    expect($createdClient->id)->toBeInt();

    $fetchedClient = $api->getClientById($workspaceId, $createdClient->id);

    expect($fetchedClient)->toBeObject();
    expect($fetchedClient->id)->toBe($createdClient->id);


    $clientExists = false;
    $currentClients = $api->getWorkspaceClients($workspaceId);
    expect($currentClients)->toBeArray();
    if (count($currentClients) > 0) {
        foreach ($currentClients as $currentClient) {
            expect($currentClient)->toBeObject();
            if ($clientName === $currentClient->name) {
                $clientExists = true;
            }
        }
    }
    expect($clientExists)->toBeTrue();

    $deleteRsp = $api->deleteClient($workspaceId, $fetchedClient->id);
    expect($deleteRsp)->toBe($fetchedClient->id);
});
