<?php

namespace MorningTrain\TogglApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Wrapper for the Toggl Api.
 *
 * @see https://github.com/toggl/toggl_api_docs/blob/master/toggl_api.md
 */
class BaseApiClass
{

    protected string $apiToken = '';

    protected Client $client;

    /**
     * Get the base API URI
     * @return string
     */
    protected function getBaseURI(): string
    {
        throw new \Exception('No Base API URI has been implemented');
    }

    /**
     * TogglApi constructor.
     *
     * @param string $apiToken
     */
    public function __construct($apiToken)
    {
        $this->apiToken = $apiToken;
        $this->client = new Client([
           'base_uri' => $this->getBaseURI(),
           'auth' => [$this->apiToken, 'api_token'],
       ]);
    }

    /**
     * Helper for client get command.
     *
     * @param string $endpoint
     * @param array $body
     * @param array $query
     *
     * @return bool|mixed|object
     */
    protected function GET($endpoint, $query = array())
    {
        try {
            $response = $this->client->get($endpoint, ['query' => $query]);

            return $this->checkResponse($response);
        } catch (ClientException $e) {
            return (object) [
                'success' => false,
                'message' => $e->getResponse()->getBody()->getContents(),
            ];
        }
    }

    /**
     * Wrapper for client post command.
     *
     * @param string $endpoint
     * @param array $body
     * @param array $query
     *
     * @return bool|mixed|object
     */
    protected function POST($endpoint, $body = array(), $query = array())
    {
        try {
            $response = $this->client->post($endpoint, ['body' => json_encode($body), 'query' => $query]);

            return $this->checkResponse($response);
        } catch (ClientException $e) {
            return (object) [
                'success' => false,
                'message' => $e->getResponse()->getBody()->getContents(),
            ];
        }
    }

    /**
     * Helper for client put command.
     *
     * @param string $endpoint
     * @param array $body
     * @param array $query
     *
     * @return bool|mixed|object
     */
    protected function PUT($endpoint, $body = array(), $query = array())
    {
        try {
            $response = $this->client->put($endpoint, ['body' => json_encode($body), 'query' => $query]);

            return $this->checkResponse($response);
        } catch (ClientException $e) {
            return (object) [
                'success' => false,
                'message' => $e->getResponse()->getBody()->getContents(),
            ];
        }
    }

    /**
     * Helper for client patch command.
     *
     * @param string $endpoint
     * @param array $body
     * @param array $query
     *
     * @return bool|mixed|object
     */
    protected function PATCH($endpoint, $body = array(), $query = array())
    {
        try {
            $response = $this->client->patch($endpoint, ['body' => json_encode($body), 'query' => $query]);

            return $this->checkResponse($response);
        } catch (ClientException $e) {
            return (object) [
                'success' => false,
                'message' => $e->getResponse()->getBody()->getContents(),
            ];
        }
    }

    /**
     * Helper for client delete command.
     *
     * @param $endpoint
     * @param array $body
     * @param array $query
     *
     * @return bool|mixed|object
     */
    protected function DELETE($endpoint, $body = array(), $query = array())
    {
        try {
            $response = $this->client->delete($endpoint, ['body' => json_encode($body), 'query' => $query]);

            return $this->checkResponse($response);
        } catch (ClientException $e) {
            return (object) [
                'success' => false,
                'message' => $e->getResponse()->getBody()->getContents(),
            ];
        }
    }

    /**
     * Helper for checking http response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return bool|mixed
     */
    protected function checkResponse($response)
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
}
