<?php

namespace Rankster\View;


use Yaoi\View\Hardcoded;

class FbLike extends Hardcoded
{
    public function render()
    {
        echo FbRoot::create();

        $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        echo <<<HTML
<div class="fb-like" data-href="{$url}" data-layout="button_count" data-action="like" data-size="large" data-show-faces="true" data-share="true"></div>
HTML;
    }


}