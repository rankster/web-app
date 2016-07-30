<?php

namespace Rankster\Service;

class Output
{
    public static function process($tplName)
    {
        $tplName = preg_replace('~[^\w\-/]~', '', $tplName);
        $fullPath = DOC_ROOT . '/../templates/' . $tplName . '.php';
        if (!file_exists($fullPath)) {
            throw new \Exception('Template ' . $tplName . ' can not be found');
        }

        ob_start();
        /** @noinspection PhpIncludeInspection */
        include($fullPath);
        $output = ob_get_clean();
        ob_end_clean();

        return $output;
    }
}
