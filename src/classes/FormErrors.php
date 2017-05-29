<?php

/**
 * Класс для хранения ошибок отправленной формы.
 */
class FormErrors
{
    /**
   * Ошибка в переменной: имя пользователя.
   *
   * @var string
   */
  private $errorFirstName;
  /**
   * Ошибка в переменной: фамилия пользователя.
   *
   * @var string
   */
  private $errorLastName;
  /**
   * Ошибка в переменой: год рождения.
   *
   * @var int
   */
  private $errorYearOfBirth;
  /**
   * Ошибка в переменной: email.
   *
   * @var string
   */
  private $errorEmail;
  /**
   * Ошибка в переменной: пароль.
   *
   * @var string
   */
  private $errorPassword;
  /**
   * Ошибка в поле: подтверждение пароля.
   *
   * @var string
   */
  private $errorPasswordConfirm;
 /**
  * Ошибка базы данных.
  *
  * @var string
  */
 private $errorDataBase;

    /**
     * Получить количество ошибок.
     *
     * @return int Количество ошибок
     */
    public function getCountErrors()
    {
        $countErrors = 0;
        $arrayObject = (array) $this;
        foreach ($arrayObject as $value) {
            if (!empty($value)) {
                ++$countErrors;
            }
        }

        return $countErrors;
    }

    /**
     * Возвращает массив из переменных текущего объекта.
     *
     * @return array Массив текущего объекта
     */
    public function getErrors()
    {
        return (array) $this;
    }

    /**
     * Get the value of Ошибка в переменной: имя пользователя.
     *
     * @return string
     */
    public function getErrorFirstName()
    {
        return $this->errorFirstName;
    }

    /**
     * Set the value of Ошибка в переменной: имя пользователя.
     *
     * @param string errorFirstName
     *
     * @return self
     */
    public function setErrorFirstName($errorFirstName)
    {
        $this->errorFirstName = $errorFirstName;

        return $this;
    }

    /**
     * Get the value of Ошибка в переменной: фамилия пользователя.
     *
     * @return string
     */
    public function getErrorLastName()
    {
        return $this->errorLastName;
    }

    /**
     * Set the value of Ошибка в переменной: фамилия пользователя.
     *
     * @param string errorLastName
     *
     * @return self
     */
    public function setErrorLastName($errorLastName)
    {
        $this->errorLastName = $errorLastName;

        return $this;
    }

    /**
     * Get the value of Ошибка в переменой: год рождения.
     *
     * @return int
     */
    public function getErrorYearOfBirth()
    {
        return $this->errorYearOfBirth;
    }

    /**
     * Set the value of Ошибка в переменой: год рождения.
     *
     * @param int errorYearOfBirth
     *
     * @return self
     */
    public function setErrorYearOfBirth($errorYearOfBirth)
    {
        $this->errorYearOfBirth = $errorYearOfBirth;

        return $this;
    }

    /**
     * Get the value of Ошибка в переменной: email.
     *
     * @return string
     */
    public function getErrorEmail()
    {
        return $this->errorEmail;
    }

    /**
     * Set the value of Ошибка в переменной: email.
     *
     * @param string errorEmail
     *
     * @return self
     */
    public function setErrorEmail($errorEmail)
    {
        $this->errorEmail = $errorEmail;

        return $this;
    }

    /**
     * Get the value of Ошибка в переменной: пароль.
     *
     * @return string
     */
    public function getErrorPassword()
    {
        return $this->errorPassword;
    }

    /**
     * Set the value of Ошибка в переменной: пароль.
     *
     * @param string errorPassword
     *
     * @return self
     */
    public function setErrorPassword($errorPassword)
    {
        $this->errorPassword = $errorPassword;

        return $this;
    }

    /**
     * Get the value of Ошибка базы данных.
     *
     * @return string
     */
    public function getErrorDataBase()
    {
        return $this->errorDataBase;
    }

    /**
     * Set the value of Ошибка базы данных.
     *
     * @param string errorDataBase
     *
     * @return self
     */
    public function setErrorDataBase($errorDataBase)
    {
        $this->errorDataBase = $errorDataBase;

        return $this;
    }

    /**
     * Get the value of Ошибка в поле: подтверждение пароля.
     *
     * @return string
     */
    public function getErrorPasswordConfirm()
    {
        return $this->errorPasswordConfirm;
    }

    /**
     * Set the value of Ошибка в поле: подтверждение пароля.
     *
     * @param string errorPasswordConfirm
     *
     * @return self
     */
    public function setErrorPasswordConfirm($errorPasswordConfirm)
    {
        $this->errorPasswordConfirm = $errorPasswordConfirm;

        return $this;
    }
}
