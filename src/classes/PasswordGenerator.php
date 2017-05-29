<?php

/**
 * Класс генерирующий случайный пароли из букв и цифр.
 */
class PasswordGenerator
{
    /**
   * Сгенированный пароль.
   * @var string
   */
  private $password;

  /**
   *Пароль генерируеться через конструктор конструктор
   */
  public function __construct($max)
  {
      $chars = 'qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP';
      $size = mb_strlen($chars) - 1;
      $password = '';
      do {
          $password .= $chars[rand(0, $size)];
      } while (--$max);
      $this->password = $password;
  }

    /**
     * Get the value of Сгенированный пароль.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of Сгенированный пароль.
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

}
