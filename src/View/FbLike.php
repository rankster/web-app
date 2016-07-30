<?php

namespace Rankster\View;


use Yaoi\View\Hardcoded;

class FbLike extends Hardcoded
{
    public function render()
    {
        echo FbRoot::create();

        $url = 'http://rankster.penix.tk' . $_SERVER['REQUEST_URI'];
        echo <<<HTML
<!-- Your like button code -->
	<div class="fb-like" 
		data-href="{$url}" 
		data-layout="standard" 
		data-action="like" 
		data-show-faces="true">
	</div>
HTML;
    }


}