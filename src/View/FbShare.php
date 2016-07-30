<?php

namespace Rankster\View;


use Yaoi\View\Hardcoded;

class FbShare extends Hardcoded
{
    public function render()
    {
        echo <<<HTML
 <!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
 var js, fjs = d.getElementsByTagName(s)[0];
 if (d.getElementById(id)) return;
 js = d.createElement(s); js.id = id;
 js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.7&appId=1778599909085848";
 fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>




    <!-- Your share button code -->
<div class="fb-share-button" data-href="http://rankster.penix.tk/" data-layout="button" data-size="large" data-mobile-iframe="true">
<a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Frankster.penix.tk%2F&amp;src=sdkpreparse">Share</a></div>
HTML;
    }


}