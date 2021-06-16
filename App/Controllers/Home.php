<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {   
        if(isset($_SESSION['user_id'])) {
            View::renderTemplate('Menu/menu.html');
        } else {
            View::renderTemplate('Home/index.html');
        }
        
    }
}
