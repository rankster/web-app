<?php

namespace Rankster\Api;

use Rankster\Command\AuthRequired;
use Yaoi\Command;
use Yaoi\Database;
use Yaoi\Database\Exception;
use Yaoi\Log;
use Yaoi\Storage\PhpVar;

abstract class Api extends Command
{

    /**
     * @var Command
     */
    public $action;

    public function performAction()
    {
        $debugLogStorage = new PhpVar();
        $settings = new Log\Settings();
        $settings->storage = $debugLogStorage;
        $settings->driverClassName = Log\Driver\Storage::className();
        $debugLog = new Log($settings);
        Database::getInstance()->log($debugLog);

        try {
            if ($this->action instanceof AuthRequired) {
                $this->action->initSession();
            }

            $result = $this->action->performAction();
        } catch (ClientException $exception) {
            http_response_code(400);
            $result = array('error' => $exception->getMessage());
        } catch (Command\Exception $exception) {
            http_response_code(400);
            $result = array('error' => $exception->getMessage());
        } catch (Exception $exception) {
            http_response_code(500);
            $result = array(
                'error' => $exception->getMessage(),
                'query' => $exception->query,
                'debug' => $debugLogStorage->exportArray()
            );
        } catch (\Exception $exception) {
            http_response_code(500);
            $result = array('error' => $exception->getMessage());
        }

        header("Content-Type: text/javascript");
        echo json_encode($result);
        exit();
    }

}