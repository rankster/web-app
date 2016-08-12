<?php

namespace Rankster\Api;


use Rankster\Api\V1\AddUser;
use Rankster\Api\V1\DeleteUser;
use Rankster\Api\V1\Games;
use Rankster\Api\V1\Login;
use Rankster\Api\V1\Oauth\Oauth;
use Rankster\Api\V1\RecalculateRank;
use Rankster\Api\V1\ReloadFbAvatars;
use Rankster\Api\V1\SeedMatches;
use Rankster\Api\V1\Users;
use Rankster\Api\V1\WipeRank;
use Rankster\Command\AuthRequired;
use Rankster\Api\V1\SubmitScore;
use Rankster\Api\V1\Update;
use Yaoi\Command;
use Yaoi\Command\Definition;
use Yaoi\Database;
use Yaoi\Database\Exception;
use Yaoi\Log;
use Yaoi\Storage\PhpVar;

class V1 extends Command
{
    /**
     * @var Command
     */
    public $action;

    /**
     * @param Definition $definition
     * @param \stdClass|static $options
     */
    static function setUpDefinition(Definition $definition, $options)
    {
        $options->action = Command\Option::create()
            ->addToEnum(Update::definition())
            ->addToEnum(Login::definition(), 'login')
            ->addToEnum(Games::definition(), 'games')
            ->addToEnum(Users::definition(), 'users')
            ->addToEnum(SubmitScore::definition(), 'submit-score')
            ->addToEnum(WipeRank::definition(), 'wipe-rank')
            ->addToEnum(RecalculateRank::definition(), 'recalculate-rank')
            ->addToEnum(AddUser::definition(), 'add-user')
            ->addToEnum(SeedMatches::definition(), 'seed-matches')
            ->addToEnum(DeleteUser::definition(), 'delete-user')
            ->addToEnum(ReloadFbAvatars::definition())
            ->addToEnum(Oauth::definition())
            ->setIsUnnamed();

    }

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