<?php

namespace Rankster\Twbs;


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

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function __construct()
    {
        $this->main = new Stack();
    }

    /** @var Stack  */
    private $main;

    public function pushMain(Renderer $block) {
        $this->main->push($block);
        return $this;
    }

    public function render()
    {
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?=$this->title?></title>

    <!-- Bootstrap -->
      <link rel="stylesheet" href="/css/bootstrap.min.css">

      <!-- Optional theme -->
      <link rel="stylesheet" href="/css/bootstrap-theme.min.css">

        <link href="http://coderthemes.com/minton_1.6/yellow_hori/assets/plugins/bootstrap-sweetalert/sweet-alert.css " rel="stylesheet" type="text/css">
        <link href="http://coderthemes.com/minton_1.6/yellow_hori/assets/plugins/jquery-circliful/css/jquery.circliful.css" rel="stylesheet" type="text/css" />
        <link href="http://coderthemes.com/minton_1.6/yellow_hori/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="http://coderthemes.com/minton_1.6/yellow_hori/assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="http://coderthemes.com/minton_1.6/yellow_hori/assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="http://coderthemes.com/minton_1.6/yellow_hori/assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="http://coderthemes.com/minton_1.6/yellow_hori/assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="http://coderthemes.com/minton_1.6/yellow_hori/assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="http://coderthemes.com/minton_1.6/yellow_hori/assets/css/responsive.css" rel="stylesheet" type="text/css" />
        <script src="http://coderthemes.com/minton_1.6/yellow_hori/assets/js/modernizr.min.js"></script>
      <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="/js/html5shiv.min.js"></script>
      <script src="/js/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <div class="container">
      <h1><a href="/"><img src="http://i.imgur.com/mQtR9aL.png" width="150" /></a></h1>
      <?php echo $this->main ?>
  </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/js/bootstrap.min.js"></script>
        <!-- Circliful -->
        <script src="http://coderthemes.com/minton_1.6/yellow_hori/assets/plugins/jquery-circliful/js/jquery.circliful.min.js"></script>
        <script src="http://coderthemes.com/minton_1.6/yellow_hori/assets/js/jquery.core.js"></script>
        <script src="http://coderthemes.com/minton_1.6/yellow_hori/assets/js/jquery.app.js"></script>
        <!-- script src="http://coderthemes.com/minton_1.6/yellow_hori/assets/pages/jquery.widgets.js"></script -->


        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('.counter').counterUp({
                    delay: 100,
                    time: 1200
                });
                $('.circliful-chart').circliful();
            });
        </script>
  </body>
</html>
<?php
    }

}
