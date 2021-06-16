<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\UserExpense;

class Expense extends \Core\Controller
{  
    public function expenseAction()
    {
        View::renderTemplate('Expense/expense.html');
    }

    public function createAction()
    {
        $userExpense = new UserExpense($_POST);

        if($userExpense->save()) {
            View::renderTemplate('Expense/expense.html', ['userExpense' => $userExpense]);
        } else {
            //var_dump($userIncome->errors);
            View::renderTemplate('Expense/expense.html', ['userExpense' => $userExpense]);
        }
    }
}
