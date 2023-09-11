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
        return $this->POST("clients", $clientData);
    }

    /**
     * Update client.
     *
     * @param int $clientId
     * @param array $clientData
     *
     * @return bool|mixed|object
     */
    public function updateClient($clientId, $clientData)
    {
        return $this->PUT('clients/' . $clientId, ['client' => $clientData]);
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
        return $this->DELETE('clients/' . $clientId);
    }

    /**
     * Get clients.
     *
     * @return bool|mixed|object
     */
    public function getClients()
    {
        $clients = $this->GET('clients');

        if (empty($clients)) {
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
        return $this->GET('clients/' . $clientId . '/projects');
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
        return $this->GET('clients/' . $clientId . '/projects?active=true');
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
        return $this->GET('clients/' . $clientId . '/projects?active=false');
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
        return $this->GET('clients/' . $clientId . '/projects?active=both');
    }

    /**
     * Get project.
     *
     * @param int $projectId
     *
     * @return bool|mixed|object
     */
    public function getProject($projectId)
    {
        return $this->GET("projects/{$projectId}");
    }

    /**
     * Get projects.
     *
     * @param array $options
     *
     * @return bool|mixed|object
     */
    public function getProjects($options)
    {
        return $this->GET('projects', $options);
    }

    /**
     * Create Project.
     *
     * @param array $project
     *
     * Project has the following properties:
     * - name: The name of the project (string, required, unique for client and workspace)
     * - wid: workspace ID, where the project will be saved (integer, required)
     * - cid: client ID (integer, not required)
     * - active: whether the project is archived or not (boolean, by default true)
     * - is_private: whether project is accessible for only project users or for all workspace users (boolean, default true)
     * - template: whether the project can be used as a template (boolean, not required)
     * - template_id: id of the template project used on current project's creation
     * - billable: whether the project is billable or not (boolean, default true, available only for pro workspaces)
     * - auto_estimates: whether the estimated hours is calculated based on task estimations or is fixed manually (boolean, default false, not required, premium functionality)
     * - estimated_hours: if auto_estimates is true then the sum of task estimations is returned, otherwise user inserted hours (integer, not required, premium functionality)
     * - at: timestamp that is sent in the response for PUT, indicates the time task was last updated (read-only)
     * - color: id of the color selected for the project
     * - rate: hourly rate of the project (float, not required, premium functionality)
     * - created_at: timestamp indicating when the project was created (UTC time), read-only
     *
     * @return bool|mixed|object
     *
     * @see https://github.com/toggl/toggl_api_docs/blob/master/chapters/projects.md
     */
    public function createProject($project)
    {
        return $this->POST('projects', $project);
    }

    /**
     * Update a project.
     *
     * @param int $projectId
     * @param array $project
     *
     * @return bool|mixed|object
     */
    public function updateProject($projectId, $project)
    {
        return $this->PUT("projects/{$projectId}", $project);
    }

    /**
     * Get project group relations.
     *
     * @param int $projectId
     *
     * @return bool|mixed|object
     */
    public function getProjectGroupRelations($projectId)
    {
        return $this->GET("projects/{$projectId}/project_groups");
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
        return $this->GET("clients/{$clientId}");
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
        return $this->POST('time_entries', $entry);
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
        return $this->PATCH("time_entries/{$timeEntryId}/stop");
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
        return $this->GET("time _entries/{$timeEntryId}");
    }

    /**
     * Update time entry.
     *
     * @param int $timeEntryId
     * @param array $entry
     *
     * @return bool|mixed|object
     */
    public function updateTimeEntry($timeEntryId, $entry)
    {
        return $this->PUT('time_entries/' . $timeEntryId, ['time_entry' => $entry]);
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
        return $this->DELETE('time_entries/' . $timeEntryId);
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

    /**
     * Create project user relation.
     *
     * @param array $user
     *
     * Project user has the following properties
     *
     * - pid: project ID (integer, required)
     * - uid: user ID, who is added to the project (integer, required)
     * - wid: workspace ID, where the project belongs to (integer, not-required, project's workspace id is used)
     * - manager: admin rights for this project (boolean, default false)
     * - rate: hourly rate for the project user (float, not-required, only for pro workspaces) in the currency of the project's client or in workspace default currency.
     * - at: timestamp that is sent in the response, indicates when the project user was last updated
     *
     * Workspace id (wid), project id (pid) and user id (uid) can't be changed on update.
     *
     * @return bool|mixed|object
     *
     * @see https://github.com/toggl/toggl_api_docs/blob/master/chapters/project_users.md
     */
    public function createProjectUser($user)
    {
        return $this->POST('project_users', $user);
    }


    /**
     * Create task.
     *
     * @param int $projectId
     * @param array $task
     *
     * @return bool|mixed|object
     */
    public function createTask($projectId, $task)
    {
        return $this->POST("projects/{$projectId}/tasks", $task);
    }

    /**
     * Update a task.
     *
     * @param int $projectId
     * @param int $taskId
     * @param array $task
     *
     * @return bool|mixed|object
     */
    public function updateTask($projectId, $taskId, $task)
    {
        return $this->PUT("projects/{$projectId}/tasks/{$taskId}", $task);
    }

    /**
     * Update multiple tasks.
     *
     * @param int $projectId
     * @param array $taskIds
     * @param array $task
     *
     * @return bool|mixed|object
     */
    public function updateTasks($projectId, $taskIds, $task)
    {
        $taskIdString = implode(',', $taskIds);

        return $this->PATCH("projects/{$projectId}/tasks/{$taskIdString}", $task);
    }

    /**
     * Delete a task.
     *
     * @param int $projectId
     * @param int $taskId
     *
     * @return bool|mixed|object
     */
    public function deleteTask($projectId, $taskId)
    {
        return $this->DELETE("projects/{$projectId}/tasks/{$taskId}");
    }

    /**
     * Get task.
     *
     * Tasks are available only for pro workspaces.
     *
     * @param int $taskId
     *
     * @return bool|mixed|object
     * Task has the following properties:
     * - name: The name of the task (string, required, unique in project)
     * - pid: project ID for the task (integer, required)
     * - wid: workspace ID, where the task will be saved (integer, project's workspace id is used when not supplied)
     * - uid: user ID, to whom the task is assigned to (integer, not required)
     * - estimated_seconds: estimated duration of task in seconds (integer, not required)
     * - active: whether the task is done or not (boolean, by default true)
     * - at: timestamp that is sent in the response for PUT, indicates the time task was last updated
     * - tracked_seconds: total time tracked (in seconds) for the task
     *
     * Workspace id (wid) and project id (pid) can't be changed on update.
     *
     * @see https://github.com/toggl/toggl_api_docs/blob/master/chapters/tasks.md
     */
    public function getTask($taskId)
    {
        return $this->GET('tasks/' . $taskId);
    }
}
