<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class TogglReportsApi {
	
	protected $api_token = '';
	
	protected $client;
	
	public function __construct($api_token) {
		$this->api_token = $api_token;
		$this->client = new Client([
			'base_uri' => 'https://www.toggl.com/reports/api/v2/',
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
	
	/* 	PROJECT (https://github.com/toggl/toggl_api_docs/blob/master/reports/project.md)
	
		GET /reports/api/v2/project

		Parameters are:

		user_agent string, required, email, or other way to contact client application developer
		workspace_id integer, required. The workspace whose data you want to access
		project_id integer, required. The project whose data you want to access
		page integer, optional. number of 'tasks_page' you want to fetch
		order_field string: name/assignee/duration/billable_amount/estimated_seconds
		order_desc string: on/off, on for descending and off for ascending order

	*/
	
	public function getProjectReport($user_agent, $workspace_id, $project_id, $page = 0, $order_field = 'name', $order_desc = 'off'){
		return $this->get('project?user_agent='.$user_agent
			.'&workspace_id='.$workspace_id
			.'&project_id='.$project_id
			.'&page='.$page
			.'&order_field='.$order_field
			.'&order_desc='.$order_desc);
	}
	
	
	
	
}

?>