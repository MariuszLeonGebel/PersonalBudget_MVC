<?php

namespace App\Models;

use PDO;

class User extends \Core\Model
{
    public $errors=[];
    public $errorLogin;
    public $errorEmail;
    public $errorPass;
    public $errorPassword=[];

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public function save()
    {
        $this->validate();

        if(empty($this->errors)) {
            $password_hash = password_hash($this->haslo1, PASSWORD_DEFAULT);
            $sql = 'INSERT INTO users (username, email, password)
                    VALUES (:login, :email, :haslo1)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':login', $this->login, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':haslo1', $password_hash, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $user = static::findUser($this->login);
                $this->copyDefaultCategories($user->id);
                $_SESSION['user_id'] = $user->id;
                return true;
            }
        }
        return false;
    }
 
    protected function copyDefaultCategories($userId) {
        $db = static::getDB();
        $copyPayments=$db->prepare("INSERT INTO payment_methods_assigned_to_users (id, user_id, name) SELECT NULL, :newUserId, name FROM payment_methods_default");
        $copyPayments->bindValue(':newUserId',$userId,PDO::PARAM_INT);
        $copyIncomes=$db->prepare("INSERT INTO incomes_category_assigned_to_users (id, user_id, name) SELECT NULL, :newUserId, name FROM incomes_category_default");
        $copyIncomes->bindValue(':newUserId',$userId,PDO::PARAM_INT);
        $copyExpenses=$db->prepare("INSERT INTO expenses_category_assigned_to_users (id, user_id, name) SELECT NULL, :newUserId, name FROM expenses_category_default");
        $copyExpenses->bindValue(':newUserId',$userId,PDO::PARAM_INT);
        return ($copyPayments->execute() && $copyIncomes->execute() && $copyExpenses->execute());
    }

    public function validate()
    {
        if($this->login == '') {
            $this->errors[] = 'Podaj login!';
            $this->errorLogin = 'Podaj login!';
        }

        if(static::loginExists($this->login)) {
            $this->errors[] = 'Podany login już jest zajęty!';
            $this->errorLogin = 'Podany login już jest zajęty!';
        }

        if(filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Podaj prawidłowy e-mail!';
            $this->errorEmail = 'Podaj prawidłowy e-mail!';
        }

       if(static::emailExists($this->email)) {
            $this->errors[] = 'Istnieje już konto przypisane do tego adresu e-mail!';
            $this->errorEmail = 'Istnieje już konto przypisane do tego adresu e-mail!';
        }

        if ($this->haslo1 != $this->haslo2) {
            $this->errors[] = "Podane hasła nie są jednakowe!";
            $this->errorPassword[] = "Podane hasła nie są jednakowe!";
        }

        if(strlen($this->haslo1) < 3) {
            $this->errors[] = "Hasło powinno mieć co najmniej 3 znaki!";
            $this->errorPassword[] = "Hasło powinno mieć co najmniej 3 znaki!";
        }

        if(preg_match('/.*[a-z]+.*/i', $this->haslo1) == 0) {
            $this->errors[] = 'W haśle musi być co najmniej jedna litera!';
            $this->errorPassword[] = 'W haśle musi być co najmniej jedna litera!';
        }

        if(preg_match('/.*\d+.*/i', $this->haslo1) == 0) {
            $this->errors[] = "W haśle musi być co najmniej jedna cyfra!";
            $this->errorPassword[] = "W haśle musi być co najmniej jedna cyfra!";
        }
    }

    public static function loginExists($login)
    {
        return static::findUser($login) !== false;
    }

    public static function findUser($login)
    {
        $sql = 'SELECT * FROM users WHERE username = :login';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':login', $login, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function authenticate($login, $haslo) {
        $user = static::findUser($login);

        if($user){
            if(password_verify($haslo, $user->password)){
                return $user;
            } else {
                $this->errorPass = 'Podano nieprawidłowe hasło!';
            }
        }
        $this->errorLogin = 'Podano nieprawidłowy login!';
        return false;
    }

     public static function emailExists($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
}
