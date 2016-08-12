<?php

namespace Rankster\Service;


use Rankster\Service\Google\GoogleSettings;
use Yaoi\Command\Web\RequestMapper;
use Yaoi\Http\Client;
use Yaoi\Service;

/**
 * @method GoogleSettings getSettings()
 */
class Google extends Service
{
    protected static function getSettingsClassName()
    {
        return GoogleSettings::className();
    }


    public function createAuthUrl()
    {
        $params = array(
            'response_type' => 'code',
            'client_id' => $this->getSettings()->clientId,
            'redirect_uri' => $this->getSettings()->redirectUrl,
            'scope' => 'email profile',
            'state' => $_SERVER['REQUEST_URI'],
            'access_type' => 'online',
            'prompt' => 'select_account',
        );

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
    }


    private $accessToken;

    public function getToken($code) {
        $params = array(
            'code' => $code,
            'client_id' => $this->getSettings()->clientId,
            'client_secret' => $this->getSettings()->secret,
            'redirect_uri' => $this->getSettings()->redirectUrl,
            'grant_type' => 'authorization_code',
        );

        $client = new Client();
        $client->post = $params;
        $tokenResponse = json_decode($client->fetch('https://www.googleapis.com/oauth2/v4/token'),true);
        $this->accessToken = $tokenResponse['access_token'];
        return $this->accessToken;
    }

    public function getUserInfo() {
        $url = 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token=' . $this->accessToken;
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

}