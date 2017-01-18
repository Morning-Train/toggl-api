<?php

namespace MorningTrain\TogglApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/*
	https://github.com/toggl/toggl_api_docs/blob/master/reports.md
	
	The API expects the request parameters as the query string of the URL.

	The following parameters and filters can be used in all of the reports

	user_agent: string, required, the name of your application or your email address so we can get in touch in case you're doing something wrong.
	workspace_id: integer, required. The workspace whose data you want to access.
	since: string, ISO 8601 date (YYYY-MM-DD), by default until - 6 days.
	until: string, ISO 8601 date (YYYY-MM-DD), by default today
	billable: possible values: yes/no/both, default both
	client_ids: client ids separated by a comma, 0 if you want to filter out time entries without a client
	project_ids: project ids separated by a comma, 0 if you want to filter out time entries without a project
	user_ids: user ids separated by a comma
	tag_ids: tag ids separated by a comma, 0 if you want to filter out time entries without a tag
	task_ids: task ids separated by a comma, 0 if you want to filter out time entries without a task
	time_entry_ids: time entry ids separated by a comma
	description: string, time entry description
	without_description: true/false, filters out the time entries which do not have a description ('(no description)')
	order_field:
	date/description/duration/user in detailed reports
	title/duration/amount in summary reports
	title/day1/day2/day3/day4/day5/day6/day7/week_total in weekly report
	order_desc: on/off, on for descending and off for ascending order
	distinct_rates: on/off, default off
	rounding: on/off, default off, rounds time according to workspace settings
	display_hours: decimal/minutes, display hours with minutes or as a decimal number, default minutes

*/

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
	
	private function GET($endpoint, $query = array()){		
		try {
			$response = $this->client->get($endpoint, ['query' => $query]);
			return $this->checkResponse($response);
		} catch (ClientException $e) {
			return (object) [
				'success' => false,
				'message' => $e->getMessage()
			];
		}
	}
	
	private function POST($endpoint, $query = array()){		
		try {
			$response = $this->client->post($endpoint, ['query' => $query]);
			return $this->checkResponse($response);
		} catch (ClientException $e) {
			return (object) [
				'success' => false,
				'message' => $e->getMessage()
			];
		}
	}
	
	private function PUT($endpoint, $query = array()){		
		try {
			$response = $this->client->put($endpoint, ['query' => $query]);
			return $this->checkResponse($response);
		} catch (ClientException $e) {
			return (object) [
				'success' => false,
				'message' => $e->getMessage()
			];
		}
	}
	
	private function DELETE($endpoint, $query = array()){		
		try {
			$response = $this->client->delete($endpoint, ['query' => $query]);
			return $this->checkResponse($response);
		} catch (ClientException $e) {
			return (object) [
				'success' => false,
				'message' => $e->getMessage()
			];
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
	
	public function getAvailableEndpoints(){
		return $this->get('');
	}
	
	public function getProjectReport($query){
		return $this->get('project', $query);
	}
	
	public function getSummaryReport($query){
		return $this->get('summary', $query);
	}
	
	public function getDetailsReport($query){
		return $this->get('details', $query);
	}
	
	public function getWeeklyReport($query){
		return $this->get('weekly', $query);
	}
	
	
	
	
}

?>
