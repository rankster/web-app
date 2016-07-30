<?php

namespace Rankster\View;


use Yaoi\View\Hardcoded;

class FbRoot extends Hardcoded
{
    private static $done;

    public function render()
    {
        if (self::$done) {
            return;
        }
        self::$done = true;

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

HTML;

        // TODO: Implement render() method.
    }
}