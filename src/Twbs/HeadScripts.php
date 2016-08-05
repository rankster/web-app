<?php

namespace Rankster\Twbs;


use Yaoi\View\Hardcoded;

class HeadScripts extends Hardcoded
{
    public function render()
    {
        echo <<<HTML
    <link rel="shortcut icon" href="/rankster-favico.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    
    <!-- Optional theme -->
    <link rel="stylesheet" href="/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/css/select2.min.css">
    <link rel="stylesheet" href="/css/select2-bootstrap.css">
    
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
    
    <link href="/css/font-awesome.css" rel="stylesheet">
    <link href="/css/docs.css" rel="stylesheet">
    
    <link href="/css/bootstrap-social.css" rel="stylesheet">
    <link href="/css/rankster.css" rel="stylesheet">
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/js/jquery.min.js"></script>
    <script src="/js/jquery.sparkline.min.js"></script>

HTML;

    }


}