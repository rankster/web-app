<?php

namespace Rankster\Service\Facebook;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Rankster\Service\Facebook;
use Yaoi\Service;


class Login extends Service
{
    public function getLoginUrl()
    {
        $fb = Facebook::getInstance()->getSDK();
        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email', 'user_friends'];

        return $helper->getLoginUrl(Facebook::getInstance()->getCallbackUri(), $permissions);
    }

    public function callback()
    {
        $fb = Facebook::getInstance()->getSDK();
        $helper = $fb->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
        } catch(FacebookResponseException $e) {
            throw new \Exception('Graph returned an error: ' . $e->getMessage());
        } catch(FacebookSDKException $e) {
            throw new \Exception('Facebook SDK returned an error: ' . $e->getMessage());
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
//                $return['error'] = $helper->getError();
//                $return['error_code'] = $helper->getErrorCode();
//                $return['error_reason'] = $helper->getErrorReason();
//                $return['error_description'] = $helper->getErrorDescription();
                throw new \Exception($helper->getErrorDescription());
            }
            throw new \Exception('Bad request');
        }

        $oAuth2Client = $fb->getOAuth2Client();
        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        // Validation (these will throw FacebookSDKException's when they fail)
        //$tokenMetadata->validateAppId({app-id}); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (!$accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (FacebookSDKException $e) {
                throw new \Exception('Error getting long-lived access token: ' . $helper->getMessage());
            }
        }

        $_SESSION['fb_access_token'] = (string) $accessToken;

        return (string) $accessToken;
    }

    public function getStoredAccessToken()
    {
        if (isset($_SESSION['fb_access_token'])) {
            return $_SESSION['fb_access_token'];
        }

        return null;
    }
}
