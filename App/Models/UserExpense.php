<?php

namespace App\Models;

use PDO;

class UserExpense extends \Core\Model
{
    public $errors=[];
    public $errorKwota;
    public $errorData;
    public $successComment;
    public $expenseCat;
    public $paymentMethods;
  
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public static function getExpenseCategories() {
        $db = static::getDB();
        $expenseCat = $db->prepare("SELECT id, name FROM expenses_category_assigned_to_users WHERE user_id=:id");
        $expenseCat->bindValue(':id',$_SESSION['user_id'],PDO::PARAM_INT);
        $expenseCat->execute();
        return $expenseCat->fetchAll();
    }
    
    public static function getPaymentMethods() {
        $db = static::getDB();
        $paymentMethods = $db->prepare("SELECT id, name FROM payment_methods_assigned_to_users WHERE user_id=:id");
        $paymentMethods->bindValue(':id',$_SESSION['user_id'],PDO::PARAM_INT);
        $paymentMethods->execute();
        return $paymentMethods->fetchAll();
    }

    public function save()
    {
        $this->validate();
        echo("1");
        if(empty($this->errors)) {
        $kwotaPopr = str_replace(",", ".", $this->kwota);
        echo("2");
        $sql = 'INSERT INTO expenses (user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment)
                VALUES (:user_id, :cat_id, :pay_id, :kwota, :data, :opis)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':cat_id', $this->kategoria, PDO::PARAM_INT);
        $stmt->bindValue(':pay_id', $this->platnosc, PDO::PARAM_INT);
        $stmt->bindValue(':kwota', $kwotaPopr, PDO::PARAM_STR);
        $stmt->bindValue(':data', $this->data, PDO::PARAM_STR);
        $stmt->bindValue(':opis', $this->opis, PDO::PARAM_STR);

        $this->successComment = "Wydatek został zarejestrowany";
        return $stmt->execute();

        }
        return false;
    }
 
    public function validate()
    {
        if($this->kwota == '') {
            $this->errors[] = 'Podaj kwotę przychodu!';
            $this->errorKwota = 'Podaj kwotę przychodu!';
        }

        $kwotaPopr = str_replace(",", ".", $this->kwota);
        if(is_numeric($kwotaPopr) == false || $kwotaPopr < 0)
        {
            $this->errors[] = 'W polu "Kwota" można wpisywać tylko wartości liczbowe większe od 0';
            $this->errorKwota = 'W polu "Kwota" można wpisywać tylko wartości liczbowe większe od 0';
        }

        if($this->data == '') {
            $this->errors[] = 'Podana data jest błędna! Ustawiono datę dzisiejszą';
            $this->errorData = 'Podana data jest błędna! Ustawiono datę dzisiejszą';
        }
    } 
}
