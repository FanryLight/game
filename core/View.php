<?php
class View
{
    /**
     * @param string $view
     * @param string $message
     * @param array $data
     */
    function generate($view, $message = null, $data = null)
    {
        include 'view/'.$view.'.php';
    }
}