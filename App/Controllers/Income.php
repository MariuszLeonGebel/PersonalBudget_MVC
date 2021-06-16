<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\UserIncome;

class Income extends \Core\Controller
{ 
    public function incomeAction()
    {       
        View::renderTemplate('Income/income.html');
    }

    public function createAction()
    {
        //var_dump($_POST);
        //echo($_REQUEST['kwota'] . ", " . $_REQUEST['data']);
        $userIncome = new UserIncome($_POST);

        if($userIncome->save()) {
            View::renderTemplate('Income/income.html', ['userIncome' => $userIncome]);
        } else {
            //var_dump($userIncome->errors);
            View::renderTemplate('Income/income.html', ['userIncome' => $userIncome]);
        }
    }
}
