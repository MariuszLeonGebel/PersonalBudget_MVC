<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

class Settings extends \Core\Controller
{
    public function settingsAction()
    {
        View::renderTemplate('Settings/settings.html');
    }
}
