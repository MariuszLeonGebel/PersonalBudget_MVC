<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\UserTransactions;

class Balance extends \Core\Controller
{  
   
    public function balanceAction()
    {
        View::renderTemplate('Balance/balance.html');
    }

    public function createAction()
    {
        $user = new User($_POST);        
        View::renderTemplate('Balance/balance.html');    
    }
  
   
}
