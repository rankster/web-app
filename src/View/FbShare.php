<?php

namespace Rankster\View;


use Yaoi\View\Hardcoded;

class FbShare extends Hardcoded
{
    public function render()
    {
        echo FbRoot::create();
        $url = 'http://rankster.penix.tk' . $_SERVER['REQUEST_URI'];
        $urlEncoded = urlencode($url);

        echo <<<HTML
 <!-- Your share button code -->
<div class="fb-share-button" data-href="{$url}" data-layout="button" data-size="large" data-mobile-iframe="true">
<a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={$urlEncoded}&amp;src=sdkpreparse">Share</a>
</div>
HTML;
    }


}