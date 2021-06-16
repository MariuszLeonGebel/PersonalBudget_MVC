<?php

namespace Core;

class View
{

    /**
     * Render a view file
     *
     * @param string $view  The view file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/App/Views/$view";  // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function renderTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig\Environment($loader);
            $twig->addGlobal('session', $_SESSION);
            $twig->addGlobal('income_cats', \App\Models\UserIncome::getIncomeCategories());
            $twig->addGlobal('expense_cats', \App\Models\UserExpense::getExpenseCategories());
            $twig->addGlobal('payment_cats', \App\Models\UserExpense::getPaymentMethods());
            $twig->addGlobal('incomeTr', \App\Models\UserTransactions::getIncomeTr());
            $twig->addGlobal('incomeSum', \App\Models\UserTransactions::getIncomeSum());
            $twig->addGlobal('expenseTr', \App\Models\UserTransactions::getExpenseTr());
            $twig->addGlobal('expenseSum', \App\Models\UserTransactions::getExpenseSum());
        }
        echo $twig->render($template, $args);
    }
}
