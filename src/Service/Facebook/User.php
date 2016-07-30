<?php

namespace Rankster\Service\Facebook;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Rankster\Service\Facebook;
use Yaoi\Service;

class User extends Service
{
    protected $accessToken;

    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function getData($user = 'me', $fields = [])
    {
        $fb = Facebook::getInstance()->getSDK();
        try {
            $args = '';
            if (!empty($fields)) {
                $args = '?fields=' . join(',', $fields);
            }
            $response = $fb->get('/' . $user . $args, $this->accessToken);
        } catch(FacebookResponseException $e) {
            throw new \Exception('Graph returned an error: ' . $e->getMessage(), 0, $e);
        } catch(FacebookSDKException $e) {
            throw new \Exception('Facebook SDK returned an error: ' . $e->getMessage(), 0, $e);
        }

        return $response->getGraphUser();
    }

    public function getUserList()
    {
        $fb = Facebook::getInstance()->getSDK();
        $response = $fb->get('/me/friends?fields=id', $this->accessToken);
        var_dump($response);
    }
}