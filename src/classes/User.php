<?php

/**
 * Класс пользователя.
 */
class User
{
    /**
     * User's ID.
     * @var int
     */
    private $id;
    /**
     * User's First Name.
     * @var string
     */
    private $firstName;
    /**
     * User's Last Name.
     * @var string
     */
    private $lastName;
    /**
     * User's Year of Birth.
     * @var int
     */
    private $yearOfBirth;
    /**
     * User's Email.
     * @var string
     */
    private $email;
    /**
     * User's Password.
     * @var string
     */
    private $password;
    /**
     * Подтверждение пароля
     * @var string
     */
    private $passwordConfirm;
    /**
     * Хеш пароля.
     * @var string
     */
    private $passwordHash;
    /**
     * Являеться админом?
     * @var boolean
     */
    private $isAdmin;
    /**
     * ID сессии
     * @var string
     */
    private $sessionId;

    /**
     * Назначение переменных объекта User из значений Post массива.
     * @param array $postArray
     */
    public function setFromPostValues($postArray)
    {
        // Получаем массив переменных объекта
        $arrayObjectVars = $this->getObjectVarsNames();
        foreach ($arrayObjectVars as $value) {
            // Проверяем существование имени переменной объекта в ключах массива $_POST
            $varValue = array_key_exists($value, $postArray) ? trim(strval($postArray[$value])) : '';
            // Назначаем значения переменным объекта
            $this->setVar($value, $varValue);
        }
    }

    /**
     * Получаем массив имен переменных объекта User.
     * @return array
     */
    public function getObjectVarsNames()
    {
        return array_keys(get_object_vars($this));
    }

    /**
     * Универсальный сеттер
     * @param mixed $var   имя задаваемой переменной
     * @param mixed $value значение
     */
    public function setVar($var, $value)
    {
        $this->$var = $value;
    }

    /**
     * Получить хеш пароля.
     */
    public function setPasswordHash()
    {
        $this->passwordHash = password_hash($this->getPassword(), PASSWORD_DEFAULT);
    }

    public function verifyPassword($password)
    {
      return password_verify($password, $this->getPasswordHash());
    }

    /**
     * Get the value of User's ID.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of User's ID.
     *
     * @param int id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of User's First Name.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of User's First Name.
     *
     * @param string firstName
     *
     * @return self
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of User's Last Name.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of User's Last Name.
     *
     * @param string lastName
     *
     * @return self
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of User's Year of Birth.
     *
     * @return int
     */
    public function getYearOfBirth()
    {
        return $this->yearOfBirth;
    }

    /**
     * Set the value of User's Year of Birth.
     *
     * @param int yearOfBirth
     *
     * @return self
     */
    public function setYearOfBirth($yearOfBirth)
    {
        $this->yearOfBirth = $yearOfBirth;

        return $this;
    }

    /**
     * Get the value of User's Email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of User's Email.
     *
     * @param string email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of User's Password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of User's Password.
     *
     * @param string password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of Хеш пароля.
     *
     * @return string
     */
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    /**
     * Get the value of Подтверждение пароля
     *
     * @return string
     */
    public function getPasswordConfirm()
    {
        return $this->passwordConfirm;
    }

    /**
     * Set the value of Подтверждение пароля
     *
     * @param string passwordConfirm
     *
     * @return self
     */
    public function setPasswordConfirm($passwordConfirm)
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }


    /**
     * Get the value of Являеться админом?
     *
     * @return boolean
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Set the value of Являеться админом?
     *
     * @param boolean isAdmin
     *
     * @return self
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }


    /**
     * Get the value of ID сессии
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set the value of ID сессии
     *
     * @param string sessionId
     *
     * @return self
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

}
