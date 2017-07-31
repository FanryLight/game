<?php
class Controller {

    private $view;
    private $session;

    function __construct()
    {
        $this->view = new View();
        $this->session = SessionManager::getInstance();
        $this->getSession()->start();
    }

    /**
     * @return SessionManager
     */
    public function getSession() {
        return $this->session;
    }

    /**
     * @return View
     */
    public function getView() {
        return $this->view;
    }

    /**
     * redirect to page
     *
     * @param string $path
     */
    protected function redirectTo($path) {
        $host = 'http://'.$_SERVER['HTTP_HOST'].$path;
        echo "<script type='text/javascript'>document.location.href='".$host."';</script>";
    }
}