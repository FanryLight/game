<?php


class ProfileController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Route('/')
     */
    public function indexAction() {
        if ($this->getSession()->isLoggedIn()) {
            $user = User::findById($this->getSession()->getUserId());
            $data['login'] = $user->getLogin();
            $data['games'] = $user->getGames();
            $data['wins'] = $user->getWins();
            $data['defeats'] = $user->getDefeats();
            if ($user->getGames()) {
                $data['rate'] = number_format($user->getWins()/$user->getGames()*100, 2)."%";
            } else {
                $data['rate'] = '0%';
            }
            $this->getView()->generate('profile', null, $data);
        } else {
            $this->redirectTo('/authorization');
        }
    }
}