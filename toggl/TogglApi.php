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
	
	/* ME */	
	
	public function getMe(){
		return $this->GET('me');
	}
	
	/* 	CLIENTS 
	
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
	
	/* 	PROJECTS
	
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
	
	/* 	DASHBOARD 
	
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
}

?>