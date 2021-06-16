<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

/**
 * Menu controller
 *
 * PHP version 7.0
 */
class Menu extends \Core\Controller
{
    /**
     * Show the main page
     *
     * @return void
     */
    public function menuAction()
    {
        View::renderTemplate('Menu/menu.html');
    }
}
