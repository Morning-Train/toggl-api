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
			$response = $this->client->get($endpoint, $args);
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
	
	/* CLIENTS */
	
	public function createClient(){
	
	}
	
	public function getWorkspaceClients(){
		return $this->GET('clients');
	}
	
	public function getClientById($clientId){
		return $this->GET('clients/'.$clientId);
	}
	
}

?>