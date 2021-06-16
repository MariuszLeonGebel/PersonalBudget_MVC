<?php

namespace App\Models;

use PDO;

class UserIncome extends \Core\Model
{
    public $errors=[];
    public $errorKwota;
    public $errorData;
    public $successComment;
    public $incomeCat;
  
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public static function getIncomeCategories()
    {
        $sql = 'SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = :userId';

        $db = static::getDB();
        $incomeCat = $db->prepare($sql);
        $incomeCat->bindParam(':userId', $_SESSION['user_id'], PDO::PARAM_INT);

        $incomeCat->execute();
        return $incomeCat->fetchALL();
    }

    public function save()
    {
        $this->validate();

        if(empty($this->errors)) {
        $kwotaPopr = str_replace(",", ".", $this->kwota);

        $sql = 'INSERT INTO incomes (user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment)
                VALUES (:user_id, :cat_id, :kwota, :data, :opis)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':cat_id', $this->kategoria, PDO::PARAM_INT);
        $stmt->bindValue(':kwota', $kwotaPopr, PDO::PARAM_STR);
        $stmt->bindValue(':data', $this->data, PDO::PARAM_STR);
        $stmt->bindValue(':opis', $this->opis, PDO::PARAM_STR);

        $this->successComment = "Przychód został zarejestrowany";
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
            $this->errorData = 'Podana data jest błędna!  Ustawiono datę dzisiejszą';
        }
    }

 
}
