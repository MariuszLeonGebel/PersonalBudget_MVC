<?php

namespace App\Controllers;
use \Core\View;
use \App\Models\User;

/**
 * Logout controller
 *
 * PHP version 7.0
 */
class Logout extends \Core\Controller
{

    // Initialize the session.
    // If you are using session_name("something"), don't forget it now!
    //session_start();

    public function logoutAction()
    {
        // Unset all of Finallythe session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // , destroy the session.
        session_destroy();

        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/', true, 303);
                exit;

    }
}

?>