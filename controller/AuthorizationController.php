<?php


class AuthorizationController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }


    /**
     * Route('/authorization')
     */
    public function indexAction() {
        if ($this->getSession()->isLoggedIn()) {
            $this->redirectTo('/');
        } else {
            $this->getView()->generate('authorize');
        }
    }

    /**
     * Route('/authorization/login')
     */
    public function loginAction() {
        if (isset($_POST['submit']) && isset($_POST['login']) && isset($_POST['password'])) {
            $login = htmlspecialchars($_POST['login']);
            $password = htmlspecialchars($_POST['password']);
            if ($user = User::findBy('login', $login)) {
                if ($user->comparePassword($password)) {
                    $this->getSession()->start($user->getId());
                    $this->redirectTo('/');
                } else {
                    $errorMessage = "Error: Wrong password";
                    $this->getView()->generate('authorize', $errorMessage);
                }
            } else {
                $errorMessage = "Error: User doesn't exist";
                $this->getView()->generate('authorize', $errorMessage);
            }
        } else {
            $this->redirectTo('/authorization');
        }
    }

    /**
     * Route('/authorization/register')
     */
    public function registerAction() {
        if (isset($_POST['submit']) && isset($_POST['login']) &&
            isset($_POST['password']) && isset($_POST['repeatPassword'])) {
            $login = htmlspecialchars($_POST['login']);
            $password = htmlspecialchars($_POST['password']);
            if (strlen($login) < 3 || strlen($login) > 11) {
                $errorMessage = "Error: Login length should be between 4 and 10 characters";
                $this->getView()->generate('authorize', $errorMessage);
                return;
            }
            if (strlen($password) < 5 || strlen($password) > 16) {
                $errorMessage = "Error: Password length should be between 6 and 15 characters";
                $this->getView()->generate('authorize', $errorMessage);
                return;
            }
            $repeatPassword = htmlspecialchars($_POST['repeatPassword']);
            if ($password === $repeatPassword) {
                if (!$user = User::findBy('login', $login)) {
                    $user = new User($login, $password);
                    $user->save();
                    $this->getSession()->start($user->getId());
                    $this->redirectTo('/');
                } else {
                    $errorMessage = "Error: Login is already taken";
                    $this->getView()->generate('authorize', $errorMessage);
                }
            } else {
                $errorMessage = "Error: Passwords do not match";
                $this->getView()->generate('authorize', $errorMessage);
            }
        }
    }

    /**
     * Route('/authorization/logout')
     */
    public function logoutAction() {
        $this->getSession()->close();
        $this->redirectTo('/authorization');
    }
}