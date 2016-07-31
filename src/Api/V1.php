<?php

namespace Rankster\Api;


use Rankster\Api\V1\AddUser;
use Rankster\Api\V1\Games;
use Rankster\Api\V1\Login;
use Rankster\Api\V1\SeedMatches;
use Rankster\Api\V1\Users;
use Rankster\Api\V1\WipeRank;
use Rankster\Command\AuthRequired;
use Rankster\Api\V1\SubmitScore;
use Rankster\Api\V1\Update;
use Yaoi\Command;
use Yaoi\Command\Definition;

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
            ->addToEnum(AddUser::definition(), 'add-user')
            ->addToEnum(SeedMatches::definition(), 'seed-matches')
            ->setIsUnnamed();

    }

    public function performAction()
    {
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
        } catch (\Exception $exception) {
            http_response_code(500);
            $result = array('error' => $exception->getMessage());
        }

        header("Content-Type: text/javascript");
        echo json_encode($result);
        exit();
    }


}