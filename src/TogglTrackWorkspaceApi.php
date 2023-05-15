<?php

namespace MorningTrain\TogglApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Wrapper for the Toggl Api.
 *
 * @see https://github.com/toggl/toggl_api_docs/blob/master/toggl_api.md
 */
class TogglTrackWorkspaceApi extends BaseApiClass
{

    protected $workspaceId = null;

    /**
     * TogglWorkspaceApi constructor.
     *
     * @param string $apiToken
     */
    public function __construct($apiToken, $workspaceId)
    {
        $this->workspaceId = $workspaceId;

        parent::__construct($apiToken);
    }

    /**
     * Get the base API URI
     * @return string
     */
    protected function getBaseURI(): string
    {
        return 'https://api.track.toggl.com';
    }

    /**
     * Get the base API URI
     * @return string
     */
    protected function generateFullEndpoint(string $endpoint): string
    {
        $fragments = ['api', 'v9', 'workspaces', $this->workspaceId, $endpoint];
        return implode('/', array_filter($fragments));
    }

    /**
     * Create client in the provided workspace
     *
     * @param array $clientData
     *
     * Client has the following properties
     *
     * - name: The name of the client (string, required, unique in workspace)
     * - wid: workspace ID, where the client will be used (integer, required)
     * - notes: Notes for the client (string, not required)
     * - hrate: The hourly rate for this client (float, not required, available only for pro workspaces)
     * - cur: The name of the client's currency (string, not required, available only for pro workspaces)
     * - at: timestamp that is sent in the response, indicates the time client was last updated
     *
     * @return bool|mixed|object
     *
     * @see https://github.com/toggl/toggl_api_docs/blob/master/chapters/clients.md
     */
    public function createClient($clientData)
    {
        return $this->POST("clients", ['client' => $clientData]);
    }

    /**
     * Update client.
     *
     * @param int   $clientId
     * @param array $clientData
     *
     * @return bool|mixed|object
     */
    public function updateClient($clientId, $clientData)
    {
        return $this->PUT('clients/'.$clientId, ['client' => $clientData]);
    }

    /**
     * Delete client.
     *
     * @param int $clientId
     *
     * @return bool|mixed|object
     */
    public function deleteClient($clientId)
    {
        return $this->DELETE('clients/'.$clientId);
    }

    /**
     * Get clients.
     *
     * @return bool|mixed|object
     */
    public function getClients()
    {
        $clients = $this->GET('clients');

        if(empty($clients)) {
            return [];
        }

        return $clients;
    }

    /**
     * Get client projects.
     *
     * @param int $clientId
     *
     * @return bool|mixed|object
     */
    public function getClientProjects($clientId)
    {
        return $this->GET('clients/'.$clientId.'/projects');
    }

    /**
     * Get active client projects.
     *
     * @param int $clientId
     *
     * @return bool|mixed|object
     */
    public function getActiveClientProjects($clientId)
    {
        return $this->GET('clients/'.$clientId.'/projects?active=true');
    }

    /**
     * Get inactive client projects.
     *
     * @param int $clientId
     *
     * @return bool|mixed|object
     */
    public function getInactiveClientProjects($clientId)
    {
        return $this->GET('clients/'.$clientId.'/projects?active=false');
    }

    /**
     * Get all client projects.
     *
     * @param int $clientId
     *
     * @return bool|mixed|object
     */
    public function getAllClientProjects($clientId)
    {
        return $this->GET('clients/'.$clientId.'/projects?active=both');
    }

    /**
     * Get client by ID.
     *
     * @param int $clientId
     *
     * @return bool|mixed|object
     */
    public function getClientById($clientId)
    {
        return $this->GET('clients/'.$clientId);
    }

    /////////////////////////////
    /// Time entries
    /////////////////////////////

    /**
     * Create time entry.
     *
     * The requests are scoped with the user whose API token is used. Only his/her time entries are updated, retrieved and created.
     *
     * @param array $entry
     * Time entry has the following properties
     * - description: (string, strongly suggested to be used)
     * - wid: workspace ID (integer, required if pid or tid not supplied)
     * - pid: project ID (integer, not required)
     * - tid: task ID (integer, not required)
     * - billable: (boolean, not required, default false, available for pro workspaces)
     * - start: time entry start time (string, required, ISO 8601 date and time)
     * - stop: time entry stop time (string, not required, ISO 8601 date and time)
     * - duration: time entry duration in seconds. If the time entry is currently running, the duration attribute contains a negative value, denoting the start of the time entry in seconds since epoch (Jan 1 1970). The correct duration can be calculated as current_time + duration, where current_time is the current time in seconds since epoch. (integer, required)
     * - created_with: the name of your client app (string, required)
     * - tags: a list of tag names (array of strings, not required)
     * - duronly: should Toggl show the start and stop time of this time entry? (boolean, not required)
     * - at: timestamp that is sent in the response, indicates the time item was last updated
     *
     * @return bool|mixed|object
     *
     * @see https://developers.track.toggl.com/docs/api/time_entries#post-timeentries
     */
    public function createTimeEntry($entry)
    {
        return $this->POST('time_entries', ['time_entry' => $entry]);
    }

    /**
     * Start time entry.
     *
     * @param array $entry
     *
     * @return bool|mixed|object
     */
    public function startTimeEntry($entry)
    {
        return $this->POST('time_entries/start', ['time_entry' => $entry]);
    }

    /**
     * Stop time entry.
     *
     * @param int $timeEntryId
     *
     * @return bool|mixed|object
     */
    public function stopTimeEntry($timeEntryId)
    {
        return $this->PATCH('time_entries/'.$timeEntryId.'/stop');
    }

    /**
     * Get time entry.
     *
     * @param int $timeEntryId
     *
     * @return bool|mixed|object
     */
    public function getTimeEntry($timeEntryId)
    {
        return $this->GET('time_entries/'.$timeEntryId);
    }

    /**
     * Update time entry.
     *
     * @param int   $timeEntryId
     * @param array $entry
     *
     * @return bool|mixed|object
     */
    public function updateTimeEntry($timeEntryId, $entry)
    {
        return $this->PUT('time_entries/'.$timeEntryId, ['time_entry' => $entry]);
    }

    /**
     * Delete time entry.
     *
     * @param int $timeEntryId
     *
     * @return bool|mixed|object
     */
    public function deleteTimeEntry($timeEntryId)
    {
        return $this->DELETE('time_entries/'.$timeEntryId);
    }

    /////////////////////////////
    /// Users
    /////////////////////////////

    /**
     * Get workspaces users.
     *
     * @return bool|mixed|object
     */
    public function getUsers()
    {
        return $this->GET('workspace_users');
    }

}
