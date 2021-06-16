<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

/**
 * Login controller
 *
 * PHP version 7.0
 */
class Login extends \Core\Controller
{
    /**
     * Show the login page
     *
     * @return void
     */
    public function loginAction()
    {
        View::renderTemplate('Login/login.html');
    }

    public function createAction()
    {
        $user = User::authenticate($_POST['login'], $_POST['haslo']);

        if($user){
            //prevents cross-site attacks
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user->id;

            $this->redirect('/Menu/menu');
        } else {
            View::renderTemplate('Login/login.html', [
                'login' => $_POST['login'],
            ]);
        }
    }
}
