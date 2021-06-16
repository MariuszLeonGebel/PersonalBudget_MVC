<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

class UserData extends \Core\Controller
{  
    public function userDataAction()
    {
        View::renderTemplate('UserData/userData.html');
    }

    // public function createAction()
    // {
    //     $userData = new UserData($_POST);

    //     if($userExpense->save()) {
    //         View::renderTemplate('UserData/userData.html', ['userData' => $userData]);
    //     } else {
    //         //var_dump($userIncome->errors);
    //         View::renderTemplate('UserData/userData.html', ['userData' => $userData]);
    //     }
    // }
}
