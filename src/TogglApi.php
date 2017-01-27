<?php

namespace MorningTrain\TogglApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class TogglApi
{
    
    protected $api_token = '';
    
    protected $client;
    
    public function __construct($api_token)
    {
        $this->api_token = $api_token;
        $this->client = new Client([
            'base_uri' => 'https://www.toggl.com/api/v8/',
            'auth' => [$this->api_token, 'api_token']
        ]);
    }
    
    private function GET($endpoint, $body = array(), $query = array())
    {
        try {
            $response = $this->client->get($endpoint, ['body' => json_encode($body), 'query' => $query]);
            return $this->checkResponse($response);
        } catch (ClientException $e) {
            return (object) [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    private function POST($endpoint, $body = array(), $query = array())
    {
        try {
            $response = $this->client->post($endpoint, ['body' => json_encode($body), 'query' => $query]);
            return $this->checkResponse($response);
        } catch (ClientException $e) {
            return (object) [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    private function PUT($endpoint, $body = array(), $query = array())
    {
        try {
            $response = $this->client->put($endpoint, ['body' => json_encode($body), 'query' => $query]);
            return $this->checkResponse($response);
        } catch (ClientException $e) {
            return (object) [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    private function DELETE($endpoint, $body = array(), $query = array())
    {
        try {
            $response = $this->client->delete($endpoint, ['body' => json_encode($body), 'query' => $query]);
            return $this->checkResponse($response);
        } catch (ClientException $e) {
            return (object) [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    private function checkResponse($response)
    {
        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody());
            if (is_object($data) && isset($data->data)) {
                $data = $data->data;
            }
            return $data;
        }
        return false;
    }
    
    public function getAvailableEndpoints()
    {
        return $this->get('');
    }
    
    /* 	CLIENTS (https://github.com/toggl/toggl_api_docs/blob/master/chapters/clients.md)
	
		Client has the following properties

		name: The name of the client (string, required, unique in workspace)
		wid: workspace ID, where the client will be used (integer, required)
		notes: Notes for the client (string, not required)
		hrate: The hourly rate for this client (float, not required, available only for pro workspaces)
		cur: The name of the client's currency (string, not required, available only for pro workspaces)
		at: timestamp that is sent in the response, indicates the time client was last updated
	*/
    
    public function createClient($client)
    {
        return $this->POST('clients', ['client' => $client]);
    }
    
    public function updateClient($clientId, $client)
    {
        return $this->PUT('clients/' . $clientId, ['client' => $client]);
    }
    
    public function deleteClient($clientId)
    {
        return $this->DELETE('clients/' . $clientId);
    }
    
    public function getClients()
    {
        return $this->GET('clients');
    }
    
    public function getClientProjects($clientId)
    {
        return $this->GET('clients/' . $clientId . '/projects');
    }
    
    public function getActiveClientProjects($clientId)
    {
        return $this->GET('clients/' . $clientId . '/projects?active=true');
    }
    
    public function getInactiveClientProjects($clientId)
    {
        return $this->GET('clients/' . $clientId . '/projects?active=false');
    }
    
    public function getAllClientProjects($clientId)
    {
        return $this->GET('clients/' . $clientId . '/projects?active=both');
    }
    
    public function getClientById($clientId)
    {
        return $this->GET('clients/' . $clientId);
    }
    
    /* 	PROJECTS USERS (https://github.com/toggl/toggl_api_docs/blob/master/chapters/project_users.md)
	
		Project user has the following properties

		pid: project ID (integer, required)
		uid: user ID, who is added to the project (integer, required)
		wid: workspace ID, where the project belongs to (integer, not-required, project's workspace id is used)
		manager: admin rights for this project (boolean, default false)
		rate: hourly rate for the project user (float, not-required, only for pro workspaces) in the currency of the project's client or in workspace default currency.
		at: timestamp that is sent in the response, indicates when the project user was last updated
		Workspace id (wid), project id (pid) and user id (uid) can't be changed on update.

	*/
    
    public function createProjectUser($user)
    {
        return $this->POST('project_users', ['project_user' => $user]);
    }
    
    public function createProjectUsers($user)
    {
        return $this->POST('project_users', ['project_user' => $user]);
    }
    
    public function updateProjectUser($projectUserId, $user)
    {
        return $this->PUT('project_users/' . $projectUserId, ['project_user' => $user]);
    }
    
    public function updateProjectUsers($projectUserIds, $user)
    {
        return $this->PUT('project_users/' . implode(',', $projectUserIds), ['project_user' => $user]);
    }
    
    public function deleteProjectUser($projectUserId)
    {
        return $this->DELETE('project_users/' . $projectUserId);
    }
    
    public function deleteProjectUsers($projectUserIds)
    {
        return $this->DELETE('project_users/' . implode(',', $projectUserIds));
    }
    
    /* 	PROJECTS (https://github.com/toggl/toggl_api_docs/blob/master/chapters/projects.md)
	
		Project has the following properties

		name: The name of the project (string, required, unique for client and workspace)
		wid: workspace ID, where the project will be saved (integer, required)
		cid: client ID (integer, not required)
		active: whether the project is archived or not (boolean, by default true)
		is_private: whether project is accessible for only project users or for all workspace users (boolean, default true)
		template: whether the project can be used as a template (boolean, not required)
		template_id: id of the template project used on current project's creation
		billable: whether the project is billable or not (boolean, default true, available only for pro workspaces)
		auto_estimates: whether the estimated hours is calculated based on task estimations or is fixed manually (boolean, default false, not required, premium functionality)
		estimated_hours: if auto_estimates is true then the sum of task estimations is returned, otherwise user inserted hours (integer, not required, premium functionality)
		at: timestamp that is sent in the response for PUT, indicates the time task was last updated (read-only)
		color: id of the color selected for the project
		rate: hourly rate of the project (float, not required, premium functionality)
		created_at: timestamp indicating when the project was created (UTC time), read-only

	*/
    
    public function createProject($project)
    {
        return $this->POST('projects', ['project' => $project]);
    }
    
    public function updateProject($projectId, $project)
    {
        return $this->PUT('projects/' . $projectId, ['project' => $project]);
    }
    
    public function deleteProject($projectId)
    {
        return $this->DELETE('projects/' . $projectId);
    }
    
    public function deleteProjects($projectIds)
    {
        return $this->DELETE('projects/' . implode(',', $projectIds));
    }
    
    public function getProjectUserRelations($projectId, $active = 'true')
    {
        return $this->GET('projects/' . $projectId . '/project_users');
    }
    
    public function getProjectTasks($projectId, $active = 'true')
    {
        return $this->GET('projects/' . $projectId . '/tasks');
    }

    public function getProject($projectId)
    {
        return $this->GET('projects/' . $projectId);
    }
    
    /* 	DASHBOARD (https://github.com/toggl/toggl_api_docs/blob/master/chapters/dashboard.md)
	
		Dashboard's main purpose is to give an overview of what users in the workspace are doing and have been doing. Dashboard request returns two objects:

		Activity
		Most active user
		The activity object holds the data of 10 latest actions in the workspace. Activity object has the following properties

		user_id: user ID
		project_id: project ID (ID is 0 if time entry doesn'y have project connected to it)
		duration: time entry duration in seconds. If the time entry is currently running, the duration attribute contains a negative value, denoting the start of the time entry in seconds since epoch (Jan 1 1970). The correct duration can be calculated as current_time + duration, where current_time is the current time in seconds since epoch.
		description: (Description property is not present if time entry description is empty)
		stop: time entry stop time (ISO 8601 date and time. Stop property is not present when time entry is still running)
		tid: task id, if applicable
		The most active user object holds the data of the top 5 users who have tracked the most time during last 7 days. Most active user object has the following properties

		user_id: user ID
		duration: Sum of time entry durations that have been created during last 7 days	
	*/
    
    public function getDashboadForWorkspace($workspaceId)
    {
        return $this->GET('dashboard/' . $workspaceId);
    }
    
    /* 	USERS (https://github.com/toggl/toggl_api_docs/blob/master/chapters/users.md)
	
		User has the following properties

		api_token: (string)
		default_wid: default workspace id (integer)
		email: (string)
		jquery_timeofday_format: (string)
		jquery_date_format:(string)
		timeofday_format: (string)
		date_format: (string)
		store_start_and_stop_time: whether start and stop time are saved on time entry (boolean)
		beginning_of_week: (integer 0-6, Sunday=0)
		language: user's language (string)
		image_url: url with the user's profile picture(string)
		sidebar_piechart: should a piechart be shown on the sidebar (boolean)
		at: timestamp of last changes
		new_blog_post: an object with toggl blog post title and link
		send_product_emails: (boolean) Toggl can send newsletters over e-mail to the user
		send_weekly_report: (boolean) if user receives weekly report
		send_timer_notifications: (boolean) email user about long-running (more than 8 hours) tasks
		openid_enabled: (boolean) google signin enabled
		timezone: (string) timezone user has set on the "My profile" page ( IANA TZ timezones )

	*/
    
    public function getMe($related = false)
    {
        return $this->GET('me', [], ['with_related_data' => $related]);
    }
    
    public function updateMe($user)
    {
        return $this->PUT('me', ['user' => $user]);
    }
    
    public function signup($user)
    {
        return $this->POST('signups', ['user' => $user]);
    }
    
    public function resetApiToken()
    {
        return $this->POST('reset_token');
    }
    
    /*	WORKSPACES (https://github.com/toggl/toggl_api_docs/blob/master/chapters/workspaces.md)
	
		Workspace has the following properties

		name: the name of the workspace (string)
		premium: If it's a pro workspace or not. Shows if someone is paying for the workspace or not (boolean)
		admin: shows whether currently requesting user has admin access to the workspace (boolean)
		default_hourly_rate: default hourly rate for workspace, won't be shown to non-admins if the only_admins_see_billable_rates flag is set to true (float)
		default_currency: default currency for workspace (string)
		only_admins_may_create_projects: whether only the admins can create projects or everybody (boolean)
		only_admins_see_billable_rates: whether only the admins can see billable rates or everybody (boolean)
		rounding: type of rounding (integer)
		rounding_minutes: round up to nearest minute (integer)
		at: timestamp that indicates the time workspace was last updated
		logo_url: URL pointing to the logo (if set, otherwise omited) (string)	
	*/
    
    public function getWorkspaces()
    {
        return $this->GET('workspaces');
    }
    
    public function getWorkspace($wid)
    {
        return $this->GET('workspaces/' . $wid);
    }
    
    public function updateWorkspace($wid, $workspace)
    {
        return $this->PUT('workspaces/' . $wid, ['workspace' => $workspace]);
    }
    
    public function getWorkspaceUsers($wid)
    {
        return $this->GET('workspaces/' . $wid . '/users');
    }
    
    public function getWorkspaceClients($wid)
    {
        return $this->GET('workspaces/' . $wid . '/clients');
    }
    
    public function getWorkspaceProjects($wid)
    {
        return $this->GET('workspaces/' . $wid . '/projects');
    }
    
    public function getWorkspaceTasks($wid)
    {
        return $this->GET('workspaces/' . $wid . '/tasks');
    }
    
    public function getWorkspaceTags($wid)
    {
        return $this->GET('workspaces/' . $wid . '/tags');
    }
    
    /* 	WORKSPACE USERS (https://github.com/toggl/toggl_api_docs/blob/master/chapters/workspace_users.md)
		Workspace user has the following properties:

		id: workspace user id (integer)
		uid: user id of the workspace user (integer)
		admin: if user is workspace admin (boolean)
		active: if the workspace user has accepted the invitation to this workspace (boolean)
		invite_url: if user has not accepted the invitation the url for accepting his/her invitation is sent when the request is made by workspace_admin
	*/
    
    public function inviteUsersToWorkspace($wid, $emails)
    {
        return $this->POST('workspaces/' . $wid . '/invite', ['emails' => $emails]);
    }
    
    public function updateWorkspaceUser($workspaceUserId, $user)
    {
        return $this->PUT('workspace_users/' . $workspaceUserId, ['workspace_user' => $user]);
    }
    
    public function deleteWorkspaceUser($workspaceUserId)
    {
        return $this->DELETE('workspace_users/' . $workspaceUserId);
    }
    
    public function getWorkspaceUserRelations($wid)
    {
        return $this->GET('workspaces/' . $wid . '/workspace_users');
    }
    
    /*  TAGS (https://github.com/toggl/toggl_api_docs/blob/master/chapters/tags.md)
	
		Tag has the following properties
		
		name: The name of the tag (string, required, unique in workspace)
		wid: workspace ID, where the tag will be used (integer, required)
	*/
    
    public function createTag($tag)
    {
        return $this->POST('tags', ['tag' => $tag]);
    }
    
    public function updateTag($tagId, $tag)
    {
        return $this->PUT('tags/' . $tagId, ['tag' => $tag]);
    }
    
    public function deleteTag($tagId)
    {
        return $this->DELETE('tags/' . $tagId);
    }
    
    /*  TAGS (https://github.com/toggl/toggl_api_docs/blob/master/chapters/tags.md)
	
		Tasks are available only for pro workspaces.

		Task has the following properties

		name: The name of the task (string, required, unique in project)
		pid: project ID for the task (integer, required)
		wid: workspace ID, where the task will be saved (integer, project's workspace id is used when not supplied)
		uid: user ID, to whom the task is assigned to (integer, not required)
		estimated_seconds: estimated duration of task in seconds (integer, not required)
		active: whether the task is done or not (boolean, by default true)
		at: timestamp that is sent in the response for PUT, indicates the time task was last updated
		tracked_seconds: total time tracked (in seconds) for the task
		Workspace id (wid) and project id (pid) can't be changed on update.
	*/
    
    public function getTask($taskId)
    {
        return $this->GET('tasks/' . $taskId);
    }
    
    public function createTask($task)
    {
        return $this->POST('tasks', ['task' => $task]);
    }
    
    public function updateTask($taskId, $task)
    {
        return $this->PUT('tasks/' . $taskId, ['task' => $task]);
    }
    
    public function updateTasks($taskIds, $task)
    {
        return $this->PUT('tasks/' . implode(',', $taskIds), ['task' => $task]);
    }
    
    public function deleteTask($taskId)
    {
        return $this->DELETE('tasks/' . $taskId);
    }
    
    public function deleteTasks($taskIds)
    {
        return $this->DELETE('tasks/' . implode(',', $taskIds));
    }
    
    /* 	TIME ENTRIES (https://github.com/toggl/toggl_api_docs/blob/master/chapters/time_entries.md)
		
		The requests are scoped with the user whose API token is used. Only his/her time entries are updated, retrieved and created.

		Time entry has the following properties

		description: (string, strongly suggested to be used)
		wid: workspace ID (integer, required if pid or tid not supplied)
		pid: project ID (integer, not required)
		tid: task ID (integer, not required)
		billable: (boolean, not required, default false, available for pro workspaces)
		start: time entry start time (string, required, ISO 8601 date and time)
		stop: time entry stop time (string, not required, ISO 8601 date and time)
		duration: time entry duration in seconds. If the time entry is currently running, the duration attribute contains a negative value, denoting the start of the time entry in seconds since epoch (Jan 1 1970). The correct duration can be calculated as current_time + duration, where current_time is the current time in seconds since epoch. (integer, required)
		created_with: the name of your client app (string, required)
		tags: a list of tag names (array of strings, not required)
		duronly: should Toggl show the start and stop time of this time entry? (boolean, not required)
		at: timestamp that is sent in the response, indicates the time item was last updated
	*/
    
    public function createTimeEntry($entry)
    {
        return $this->POST('time_entries', ['time_entry' => $entry]);
    }
    
    public function startTimeEntry($entry)
    {
        return $this->POST('time_entries/start', ['time_entry' => $entry]);
    }
    
    public function stopTimeEntry($timeEntryId)
    {
        return $this->PUT('time_entries/' . $timeEntryId.'/stop');
    }
    
    public function getTimeEntry($timeEntryId)
    {
        return $this->GET('time_entries/' . $timeEntryId);
    }
    
    public function getRunningTimeEntry()
    {
        return $this->GET('time_entries/current');
    }
    
    public function getTimeEntries()
    {
        return $this->GET('time_entries');
    }
    
    public function getTimeEntriesInRange($start, $end)
    {
        return $this->GET('time_entries', [], ['start_date' => $start, 'end_date' => $end]);
    }
    
    public function updateTagsForTimeEntries($timeEntryIds, $entry)
    {
        return $this->PUT('time_entries/' . implode(',', $timeEntryIds), ['time_entry' => $entry]);
    }
    
    public function updateTimeEntry($timeEntryId, $entry)
    {
        return $this->PUT('time_entries/' . $timeEntryId, ['time_entry' => $entry]);
    }
    
    public function deleteTimeEntry($timeEntryId)
    {
        return $this->DELETE('time_entries/' . $timeEntryId);
    }
}
