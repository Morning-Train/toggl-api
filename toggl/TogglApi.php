<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class TogglApi {
	
	protected $api_token = '';
	
	protected $client;
	
	public function __construct($api_token) {
		$this->api_token = $api_token;
		$this->client = new Client([
			'base_uri' => 'https://www.toggl.com/api/v8/',
			'auth' => [$this->api_token, 'api_token']
		]);		
	}
	
	private function GET($endpoint, $args = array()){		
		try {
			$response = $this->client->get($endpoint, ['body' => json_encode($args)]);
			return $this->checkResponse($response);
		} catch (ClientException $e) {
			echo $e->getMessage();
			return false;
		}
	}
	
	private function POST($endpoint, $args = array()){		
		try {
			$response = $this->client->post($endpoint, ['body' => json_encode($args)]);
			return $this->checkResponse($response);
		} catch (ClientException $e) {
			echo $e->getMessage();
			return false;
		}
	}
	
	private function PUT($endpoint, $args = array()){		
		try {
			$response = $this->client->put($endpoint, ['body' => json_encode($args)]);
			return $this->checkResponse($response);
		} catch (ClientException $e) {
			echo $e->getMessage();
			return false;
		}
	}
	
	private function DELETE($endpoint, $args = array()){		
		try {
			$response = $this->client->delete($endpoint, ['body' => json_encode($args)]);
			return $this->checkResponse($response);
		} catch (ClientException $e) {
			echo $e->getMessage();
			return false;
		}
	}
	
	private function checkResponse($response) {
		if($response->getStatusCode() == 200) {
			$data = json_decode($response->getBody());
			if(is_object($data) && isset($data->data)){
				$data = $data->data;
			}
			return $data;
		}
		return false;
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
	
	public function createClient($args){
		return $this->POST('clients', ['client' => $args]);
	}
	
	public function updateClient($clientId, $args){
		return $this->PUT('clients/'.$clientId, ['client' => $args]);
	}
	
	public function deleteClient($clientId){
		return $this->DELETE('clients/'.$clientId);
	}
	
	public function getClients(){
		return $this->GET('clients');
	}
	
	public function getClientProjects($clientId, $active = 'true'){
		return $this->GET('clients/'.$clientId.'/projects', ['active' => $active]);
	}
	
	public function getClientById($clientId){
		return $this->GET('clients/'.$clientId);
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
	
	public function createProject($args){
		return $this->POST('projects', ['project' => $args]);
	}
	
	public function updateProject($projectId, $args){
		return $this->PUT('projects/'.$projectId, ['project' => $args]);
	}
	
	public function deleteProject($projectId){
		return $this->DELETE('projects/'.$projectId);
	}
	
	public function deleteProjects($projectIds){
		return $this->DELETE('projects/'.implode(',', $projectIds));
	}
	
	public function getProjectUsers($projectId, $active = 'true'){
		return $this->GET('projects/'.$projectId.'/project_users');
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
	
	public function getDashboadForWorkspace($workspaceId){
		return $this->GET('dashboard/'.$workspaceId);
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
	
	public function getMe(){
		return $this->GET('me');
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
	
	public function getWorkspaces(){
		return $this->GET('workspaces');
	}
	
	public function getWorkspace($wid){
		return $this->GET('workspaces/'.$wid);
	}
	
	public function updateWorkspace($wid, $args){
		return $this->PUT('workspaces/'.$wid, ['workspace' => $args]);
	}
	
	public function getWorkspaceUsers($wid){
		return $this->GET('workspaces/'.$wid.'/users');
	}
	
	public function getWorkspaceClients($wid){
		return $this->GET('workspaces/'.$wid.'/clients');
	}
	
	public function getWorkspaceProjects($wid){
		return $this->GET('workspaces/'.$wid.'/projects');
	}
	
	public function getWorkspaceTasks($wid){
		return $this->GET('workspaces/'.$wid.'/tasks');
	}
	
	public function getWorkspaceTags($wid){
		return $this->GET('workspaces/'.$wid.'/tags');
	}
	
	/* 	WORKSPACE USERS (https://github.com/toggl/toggl_api_docs/blob/master/chapters/workspace_users.md)
		Workspace user has the following properties:

		id: workspace user id (integer)
		uid: user id of the workspace user (integer)
		admin: if user is workspace admin (boolean)
		active: if the workspace user has accepted the invitation to this workspace (boolean)
		invite_url: if user has not accepted the invitation the url for accepting his/her invitation is sent when the request is made by workspace_admin
	*/
	
	public function inviteUserToWorkspace(){
	
	}
	
	public function updateWorkspaceUser($workspaceUserId, $args){
	
	}
	
	public function deleteWorkspaceUser($workspaceUserId){
	
	}
	
	public function getWorkspaceUserRelations($wid){
	
	}
	
}

?>