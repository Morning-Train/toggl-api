<?php

namespace MorningTrain\TogglApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Wrapper for the Toggl Reports Api.
 *
 * @see https://github.com/toggl/toggl_api_docs/blob/master/reports.md
 */
class TogglReportsApi extends BaseApiClass
{

    /**
     * Get the base API URI
     * @return string
     */
    protected function getBaseURI(): string
    {
        return 'https://api.track.toggl.com/reports/api/v2/';
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
     *
     * @return bool|mixed|object
     */
    public function getProjectReport($query)
    {
        return $this->get('project', $query);
    }

    /**
     * Get summary report.
     *
     * @param string $query
     *
     * @return bool|mixed|object
     */
    public function getSummaryReport($query)
    {
        return $this->get('summary', $query);
    }

    /**
     * Get details report.
     *
     * @param string $query
     *
     * @return bool|mixed|object
     */
    public function getDetailsReport($query)
    {
        return $this->get('details', $query);
    }

    /**
     * Get weekly report.
     *
     * @param string $query
     *
     * @return bool|mixed|object
     */
    public function getWeeklyReport($query)
    {
        return $this->get('weekly', $query);
    }

}
