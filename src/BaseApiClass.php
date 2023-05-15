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
        return 'https://api.track.toggl.com';
    }

    /**
     * Get full endpoint
     * @return string
     */
    protected function generateFullEndpoint(string $endpoint): string
    {
        return $endpoint;
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
            $fullEndpoint = $this->generateFullEndpoint($endpoint);
            $response = $this->client->get($fullEndpoint, ['query' => $query]);

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
            $fullEndpoint = $this->generateFullEndpoint($endpoint);
            $response = $this->client->post($fullEndpoint, ['body' => json_encode($body), 'query' => $query]);

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
            $fullEndpoint = $this->generateFullEndpoint($endpoint);
            $response = $this->client->put($fullEndpoint, ['body' => json_encode($body), 'query' => $query]);

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
            $fullEndpoint = $this->generateFullEndpoint($endpoint);
            $response = $this->client->patch($fullEndpoint, ['body' => json_encode($body), 'query' => $query]);

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
            $fullEndpoint = $this->generateFullEndpoint($endpoint);
            $response = $this->client->delete($fullEndpoint, ['body' => json_encode($body), 'query' => $query]);

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
            $data = json_decode($response->getBody()->getContents());
            if (is_object($data) && isset($data->data)) {
                $data = $data->data;
            }

            return $data;
        }

        return false;
    }
}
