<?php

namespace Rankster\View;


use Rankster\View\HeadScripts;
use Rankster\View\FbLike;
use Yaoi\View\Hardcoded;
use Yaoi\View\Renderer;
use Yaoi\View\Stack;

class Layout extends Hardcoded
{
    public function isEmpty()
    {
        return false;
    }

    public $title = 'Rankster';
    /** @var Header */
    public $header;
    /** @var Footer */
    public $footer;

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function __construct()
    {
        $this->main = new Stack();
        $this->header = new Header();
        $this->footer = new Footer;
    }

    /** @var Stack */
    private $main;

    public function pushMain(Renderer $block)
    {
        $this->main->push($block);
        return $this;
    }

    public function render()
    {
        $fbLike = (string)FbLike::create();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
            <title><?= $this->title ?></title>

            <?= HeadScripts::create() ?>
        </head>
        <body>
        <div class="container">
            <h1><a href="/"><img src="/images/mQtR9aL.png" width="150"/></a></h1>
            <?php $this->header->render() ?>
            <?php echo $this->main ?>

            <div class="row">
                <?= $fbLike ?>
            </div>

            <?php $this->footer->render() ?>
        </div>

        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="/js/bootstrap.min.js"></script>
        <!-- Counter Up  -->
        <script
            src="/js/jquery.waypoints.js"></script>
        <script
            src="/js/jquery.counterup.min.js"></script>

        <!-- Sweet Alert  -->
        <script
            src="/js/sweet-alert.min.js"></script>
        <script src="/js/sweet_alerts.js"></script>

        <!-- Sparkline -->
        <script
            src="/js/jquery.sparkline.min.js"></script>

        <!-- skycons -->
        <script src="/js/skycons.min.js"
                type="text/javascript"></script>

        <!-- Todojs  -->
        <script src="/js/jquery.todo.js"></script>
        <!-- Circliful -->
        <script
            src="/js/jquery.circliful.min.js"></script>
        <script src="/js/jquery.core.js"></script>
        <script src="/js/jquery.app.js"></script>
        <script src="/js/jquery.widgets.js"></script>
        <script src="/js/jquery.modal.js" type="text/javascript" charset="utf-8"></script>


        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.counter').counterUp({
                    delay: 100,
                    time: 1200
                });
                $('.circliful-chart').circliful();
            });
        </script>
        <script src="/js/select2.min.js"></script>

        </body>
        </html>
        <?php
    }

}
