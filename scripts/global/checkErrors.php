<?php
function checkErrors() : void
{
    ini_set('display_errors', 'on');
    ini_set('log_errors', 'on');
    ini_set('display_startup_errors', 'on');
    ini_set('error_reporting', E_ALL);
}
