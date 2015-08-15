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
	
	/* CLIENTS */
	
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
	
	public function getClientById($clientId){
		return $this->GET('clients/'.$clientId);
	}
	
	/* PROJECTS */
	
	public function getProjectsByClientId($clientId, $active = 'true'){
		return $this->GET('clients/'.$clientId.'/projects', ['active' => $active]);
	}
	
}

?>