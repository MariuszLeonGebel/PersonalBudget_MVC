<?php

namespace App\Models;

use PDO;
use mysqli;

class UserTransactions extends \Core\Model
{
    public $incomeSum;
    public $expenseSum;
    public $mo;
     
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public static function getIncomeSum() {
        $db = static::getDB();
        $query = $db->prepare("SELECT SUM(amount) 
                               FROM incomes
                               WHERE user_id = :id
                               AND date_of_income BETWEEN :start AND :end");
        $query->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        $query->bindValue(':start', $_SESSION['balancePeriodB'], PDO::PARAM_STR);
        $query->bindValue(':end', $_SESSION['balancePeriodE'], PDO::PARAM_STR);
        $query->execute();
        return $query->fetch();        
    }

    public static function getExpenseSum() {
        $db = static::getDB();
        $query = $db->prepare("SELECT SUM(amount) 
                               FROM expenses
                               WHERE user_id = :id
                               AND date_of_expense BETWEEN :start AND :end");
        $query->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        $query->bindValue(':start', $_SESSION['balancePeriodB'], PDO::PARAM_STR);
        $query->bindValue(':end', $_SESSION['balancePeriodE'], PDO::PARAM_STR);
        $query->execute();
        return $query->fetch();        
    }

    public static function getIncomeTr() {
       $mo = static::ostatniDzienMiesiaca();
        if(isset($_POST['DP'])) {
            $_SESSION['balancePeriodB'] = $_POST['DP'];
        } else if (isset($_SESSION['balancePeriodB'])) {
            
        } else {
            $_SESSION['balancePeriodB'] = date("Y") . "-" . date("m") . '-01';
        }
       
        if(isset($_POST['DK'])) {
            $_SESSION['balancePeriodE'] = $_POST['DK'];
        } else if (isset($_SESSION['balancePeriodE'])) {
           
        } else {
            $_SESSION['balancePeriodE'] = date("Y") . "-" . date("m") . $mo;
        }

        $db = static::getDB();
        $query = $db->prepare("
            SELECT icat.name, icat.id, SUM(ic.amount) iSum
            FROM incomes ic
            INNER JOIN incomes_category_assigned_to_users icat
            ON ic.income_category_assigned_to_user_id = icat.id
            AND (ic.date_of_income BETWEEN :start AND :end)
            AND icat.id IN (
                SELECT icat.id FROM incomes_category_assigned_to_users icat
                INNER JOIN users
                ON users.id = icat.user_id
                AND users.id = :id
            )
            GROUP BY icat.id
            ORDER BY icat.id;");
        $query->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        $query->bindValue(':start', $_SESSION['balancePeriodB'], PDO::PARAM_STR);
        $query->bindValue(':end', $_SESSION['balancePeriodE'], PDO::PARAM_STR);
        $query->execute();
        return $query->fetchAll();
    }

    public static function getExpenseTr() {
        $db = static::getDB();
        $query = $db->prepare("
            SELECT icat.name, icat.id, SUM(ic.amount) iSum
            FROM expenses ic
            INNER JOIN expenses_category_assigned_to_users icat
            ON ic.expense_category_assigned_to_user_id = icat.id
            AND (ic.date_of_expense BETWEEN :start AND :end)
            AND icat.id IN (
                SELECT icat.id FROM expenses_category_assigned_to_users icat
                INNER JOIN users
                ON users.id = icat.user_id
                AND users.id = :id
            )
            GROUP BY icat.id
            ORDER BY icat.id;");
        $query->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        $query->bindValue(':start', $_SESSION['balancePeriodB'], PDO::PARAM_STR);
        $query->bindValue(':end', $_SESSION['balancePeriodE'], PDO::PARAM_STR);
        $query->execute();
        return $query->fetchAll();
    }

    private static function ostatniDzienMiesiaca() {
         $m=date("m");
         $y=date("y");
         $LiczbaDni;
        switch($m) {
            case 0: $LiczbaDni = 31; break;
            case 1: $LiczbaDni = 31; break;
            case 2: 
                $d = checkLeapYear($y);
                $LiczbaDni = d;
                break;
            case 3: $LiczbaDni = 31; break;
            case 4: $LiczbaDni = 30; break;
            case 5: $LiczbaDni = 31; break;
            case 6: $LiczbaDni = 30; break;
            case 7: $LiczbaDni = 31; break;
            case 8: $LiczbaDni = 31; break;
            case 9: $LiczbaDni = 30; break;
            case 10: $LiczbaDni = 31; break;
            case 11: $LiczbaDni = 30; break;
        }
        return $LiczbaDni;
    }

    function checkLeapYear($year) {
        $dni;
        if ((0 == $year % 4) && (0 != $year % 100) || (0 == $year % 400)) {
            $dni = 29;
        } else {
            $dni = 28;
        }
        return $dni;
    }
}
