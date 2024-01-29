<?php

namespace MorningTrain\TogglApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Wrapper for the Toggl Api.
 *
 * @see https://github.com/toggl/toggl_api_docs/blob/master/toggl_api.md
 */
class TogglTrackMeApi extends BaseApiClass
{

    /**
     * Get the base API URI
     * @return string
     */
    protected function getBaseURI(): string
    {
        return 'https://api.track.toggl.com';
    }

    /**
     * Get the base API URI
     * @return string
     */
    protected function generateFullEndpoint(string $endpoint): string
    {
        $fragments = ['api', 'v9', 'me', $endpoint];
        return implode('/', array_filter($fragments));
    }

    /**
     * Get current user.
     *
     * @param bool $related
     *
     * @return bool|mixed|object
     *
     * User has the following properties:
     * - api_token: (string)
     * - default_wid: default workspace id (integer)
     * - email: (string)
     * - jquery_timeofday_format: (string)
     * - jquery_date_format:(string)
     * - timeofday_format: (string)
     * - date_format: (string)
     * - store_start_and_stop_time: whether start and stop time are saved on time entry (boolean)
     * - beginning_of_week: (integer 0-6, Sunday=0)
     * - language: user's language (string)
     * - image_url: url with the user's profile picture(string)
     * - sidebar_piechart: should a piechart be shown on the sidebar (boolean)
     * - at: timestamp of last changes
     * - new_blog_post: an object with toggl blog post title and link
     * - send_product_emails: (boolean) Toggl can send newsletters over e-mail to the user
     * - send_weekly_report: (boolean) if user receives weekly report
     * - send_timer_notifications: (boolean) email user about long-running (more than 8 hours) tasks
     * - openid_enabled: (boolean) google signin enabled
     * - timezone: (string) timezone user has set on the "My profile" page ( IANA TZ timezones )
     *
     * @see https://github.com/toggl/toggl_api_docs/blob/master/chapters/users.md
     */
    public function getMe($related = false)
    {
        return $this->GET('', ['with_related_data' => $related]);
    }

    /**
     * Update current user.
     *
     * @param array $user
     *
     * @return bool|mixed|object
     */
    public function updateMe($user)
    {
        return $this->PUT('', ['user' => $user]);
    }

    /**
     * Get clients for the current user.
     *
     * @return bool|mixed|object
     */
    public function getClients()
    {
        return $this->GET('clients');
    }

    /**
     * Close account for current user.
     *
     * @return bool|mixed|object
     */
    public function closeAccount()
    {
        return $this->POST('close_account');
    }

    /**
     * Get features available for current user.
     *
     * @return bool|mixed|object
     */
    public function getFeatures()
    {
        return $this->GET('features');
    }

    /**
     * Get user's last known location. Returns the client's IP-based location. If no data is present, empty response will be yielded
     *
     * @return bool|mixed|object
     */
    public function getLocation()
    {
        return $this->GET('location');
    }

    /**
     * Used to check if authentication works.
     *
     * @return bool|mixed|object
     */
    public function getLogged()
    {
        return $this->GET('logged');
    }

    /**
     * Verifies the user request to reset the password.
     *
     * @return bool|mixed|object
     */
    public function getLostPassword()
    {
        return $this->GET('lost_passwords');
    }

    /**
     * Handles the users request to reset the password.
     *
     * @return bool|mixed|object
     */
    public function handleLostPassword($email)
    {
        return $this->POST('lost_passwords', ['email' => $email]);
    }

    /**
     * Handles lost password request confirmation.
     *
     * @return bool|mixed|object
     */
    public function confirmLostPassword($code, $password, $user_id)
    {
        return $this->POST('lost_passwords/confirm', ['code' => $code, 'password' => $password, 'user_id' => $user_id]);
    }

    /**
     * Get all organizations a given user is part of.
     *
     * @return bool|mixed|object
     */
    public function getOrganizations()
    {
        return $this->GET('organizations');
    }

    /**
     * Get projects for the current user.
     *
     * @return bool|mixed|object
     */
    public function getProjects()
    {
        return $this->GET('projects');
    }

    /**
     * Get paginated projects for the current user.
     *
     * @return bool|mixed|object
     */
    public function getPaginatedProjects(int $startProjectId = 0)
    {
        return $this->GET('projects/paginated', ['start_project_id' => $startProjectId]);
    }

    /**
     * Get tags for the current user.
     *
     * @return bool|mixed|object
     */
    public function getTags()
    {
        return $this->GET('tags');
    }

    /**
     * Get tasks for the current user.
     *
     * @return bool|mixed|object
     */
    public function getTasks()
    {
        return $this->GET('tasks');
    }

    /**
     * Returns a list of track reminders.
     *
     * @return bool|mixed|object
     */
    public function getTrackReminders()
    {
        return $this->GET('track_reminders');
    }

    /**
     * Returns a list of track reminders.
     *
     * @return bool|mixed|object
     */
    public function getWebTimer()
    {
        return $this->GET('web-timer');
    }

    /**
     * Get workspaces.
     *
     * @return bool|mixed|object
     */
    public function getWorkspaces()
    {
        return $this->GET('workspaces');
    }

    /**
     * Reset API Token for current user.
     *
     * @return bool|mixed|object
     */
    public function resetApiToken()
    {
        return $this->POST('reset_token');
    }

    /////////////////////////////
    /// Time entries
    /////////////////////////////

    /**
     * Get running time entry.
     *
     * @return bool|mixed|object
     */
    public function getRunningTimeEntry()
    {
        return $this->GET('time_entries/current');
    }

    /**
     * Get time entries.
     *
     * @return bool|mixed|object
     */
    public function getTimeEntries()
    {
        return $this->GET('time_entries');
    }

}
