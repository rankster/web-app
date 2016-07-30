<?php

namespace Rankster\View;


use Yaoi\View\Hardcoded;

class FbLike extends Hardcoded
{
    public function render()
    {
        echo FbRoot::create();

        //$url = 'http://rankster.penix.tk' . $_SERVER['REQUEST_URI'];
        echo <<<HTML
<div class="fb-like" data-href="https://developers.facebook.com/docs/plugins/" data-layout="button_count" data-action="like" data-size="large" data-show-faces="true" data-share="true"></div>
HTML;
    }


}