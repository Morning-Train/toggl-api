<?php

namespace MorningTrain\TogglApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Wrapper for the Toggl Reports Api.
 *
 * @see https://github.com/toggl/toggl_api_docs/blob/master/reports.md
 */
class TogglReportsApi
{

    /**
     * @var string
     */
    protected $apiToken = '';

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * TogglReportsApi constructor.
     *
     * @param string $apiToken
     */
    public function __construct($apiToken)
    {
        $this->apiToken = $apiToken;
        $this->client = new Client([
            'base_uri' => 'https://api.track.toggl.com/reports/api/v2/',
            'auth' => [$this->apiToken, 'api_token'],
        ]);
    }

    /**
     * Get available endpoints.
     *
     * @return bool|mixed|object
     */
    public function getAvailableEndpoints()
    {
        return $this->get('');
    }

    /**
     * Get project report.
     *
     * @param string $query
     * @param array $options
     *
     * @return bool|mixed|object
     */
    public function getProjectReport($query, $options = array())
    {
        return $this->get('project', $query, $options);
    }

    /**
     * Get summary report.
     *
     * @param string $query
     * @param array $options
     *
     * @return bool|mixed|object
     */
    public function getSummaryReport($query, $options = array())
    {
        return $this->get('summary', $query, $options);
    }

    /**
     * Get details report.
     *
     * @param string $query
     * @param array $options
     *
     * @return bool|mixed|object
     */
    public function getDetailsReport($query, $options = array())
    {
        return $this->get('details', $query, $options);
    }

    /**
     * Get weekly report.
     *
     * @param string $query
     * @param array $options
     *
     * @return bool|mixed|object
     */
    public function getWeeklyReport($query, $options = array())
    {
        return $this->get('weekly', $query, $options);
    }

    /**
     * Helper for client get command.
     *
     * @param string $endpoint
     * @param array $query
     * @param array $options
     *
     * @return bool|mixed|object
     */
    private function GET($endpoint, $query = array(), $options = array())
    {
        $defaults = array(
        	'fullResponse' => false
        );
        $options = array_merge($defaults, $options);
        
        try {
            $response = $this->client->get($endpoint, ['query' => $query]);
            
            $returnFullResponse = false;
            if ($options['fullResponse'] === true) {
            	$returnFullResponse = true;
            }

            return $this->checkResponse($response, $returnFullResponse);
        } catch (ClientException $e) {
            return (object) [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Helper for client post command.
     *
     * @param string $endpoint
     * @param array $query
     *
     * @return bool|mixed|object
     */
    private function POST($endpoint, $query = array())
    {
        try {
            $response = $this->client->post($endpoint, ['query' => $query]);

            return $this->checkResponse($response);
        } catch (ClientException $e) {
            return (object) [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Helper for client put command.
     *
     * @param string $endpoint
     * @param array $query
     *
     * @return bool|mixed|object
     */
    private function PUT($endpoint, $query = array())
    {
        try {
            $response = $this->client->put($endpoint, ['query' => $query]);

            return $this->checkResponse($response);
        } catch (ClientException $e) {
            return (object) [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Helper for client delete command.
     *
     * @param string $endpoint
     * @param array $query
     *
     * @return bool|mixed|object
     */
    private function DELETE($endpoint, $query = array())
    {
        try {
            $response = $this->client->delete($endpoint, ['query' => $query]);

            return $this->checkResponse($response);
        } catch (ClientException $e) {
            return (object) [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Helper for checking http response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param bool $returnFull
     *
     * @return bool|mixed
     */
    private function checkResponse($response, $returnFull = false)
    {
        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody());
            if ($this->validateReturnData($data) && $returnFull == false) {
                $data = $data->data;
            }

            return $data;
        }

        return false;
    }
    
    /**
     * Helper for checking existence of data key in returned response.
     *
     * @param array $data
     *
     * @return bool
     */
    private function validateReturnData($data)
    {
    	return (is_object($data) && isset($data->data));
    }
}
