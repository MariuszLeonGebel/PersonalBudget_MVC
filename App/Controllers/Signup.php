<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

class Signup extends \Core\Controller
{
    public function newAction()
    {
        View::renderTemplate('Signup/new.html');
    }

    public function createAction()
    {
    	$user = new User($_POST);

    	if($user->save()) {
            $this->redirect('/');
    	} else {
    		View::renderTemplate('Signup/new.html', ['user' => $user]);
    	}
    }
}
