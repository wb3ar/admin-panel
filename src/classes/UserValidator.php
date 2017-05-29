<?php
/**
 * Класс проверки введеных пользователем данных.
 */
class UserValidator
{
    /**
   * Объект пользователя.
   *
   * @var User
   */
  private $user;

  /**
   * Объект для хранения ошибок.
   *
   * @var FormErrors
   */
  private $formErrors;

  /**
   * Объект пользователя внедряется через конструктор
   *
   * @param User $user
   */
  public function __construct(User $user)
  {
      $this->user = $user;
      $this->formErrors = new FormErrors();
  }

/**
 * Метод для проверки переменных объекта User, введенных при логине, на наличие ошибок.
 */
public function validateUserLogin()
{
    $this->checkEmail();
    $this->checkPassword();
}

/**
 * Метод для проверки переменных объекта User, введенных при регистрации, на наличие ошибок.
 */
public function validateUserRegistration()
{
    // Получаем имена переменных объекта User в виде массива
  $arrayVarsObject = $this->user->getObjectVarsNames();
  // Убираем id из массива
    foreach ($arrayVarsObject as $value) {
        $methodName = 'check'.mb_strtoupper($value[0]).mb_substr($value, 1);
        if (method_exists($this, $methodName)) {
            $this->$methodName();
        }
    }
}

    /**
     * Проверяет введенные данные при изменении пользователя.
     *
     * @return bool Значение true если введен новый пароль, false если нет
     */
    public function validateUserEdit()
    {
        $arrayVarsObject = $this->user->getObjectVarsNames();
        $checkPassword = true;
        if (empty($this->user->getPassword()) and empty($this->user->getPasswordConfirm())) {
            $checkPassword = false;
        }
        foreach ($arrayVarsObject as $value) {
            $methodName = 'check'.mb_strtoupper($value[0]).mb_substr($value, 1);
            if (!$checkPassword && ($value = 'password' || $value = 'passwordConfirm')) {
                continue;
            } elseif (method_exists($this, $methodName)) {
                $this->$methodName();
            }
        }

        return $checkPassword;
    }

/**
 * Общий метод для проверки переменой, такой как имя или фамилия.
 *
 * @param string $varValue        Значение переменной
 * @param string $shortMethodName Часть названия метода для работы с переменной
 * @param string $str             Название поля
 */
public function checkName($varValue, $shortMethodName, $str)
{
    // Полное имя метода для объекта FormErrors
    $fullMethodName = 'setError'.$shortMethodName;
    if (empty($varValue)) {
        $this->formErrors->$fullMethodName('Не заполнено поле "'.$str.'".');
    } elseif (mb_strlen($varValue) > 50) {
        $this->formErrors->$fullMethodName('Поле "'.$str.'" должно содержать не более 50 символов.');
    } elseif (!preg_match('/^[а-яёa-z](([\s-_\'\.]?[0-9а-яёa-z]+)*)$/ui', $varValue)) {
        $this->formErrors->$fullMethodName('Поле "'.$str.'" может содержать русские, латинские буквы, цифры и некоторые специальные символы.');
    }
}

    /**
     * Метод проверки переменной объекта: имя.
     */
    public function checkFirstName()
    {
        $this->checkName($this->user->getFirstName(), 'FirstName', 'Имя');
    }

    /**
     * Метод проверки переменной объекта: фамилия.
     */
    public function checkLastName()
    {
        $this->checkName($this->user->getLastName(), 'LastName', 'Фамилия');
    }

    /**
     * Метод проверки переменной: год.
     */
    public function checkYearOfBirth()
    {
        $yearOfBirth = $this->user->getYearOfBirth();

        if (empty($yearOfBirth)) {
            $this->formErrors->setErrorYearOfBirth('Не заполнено поле "Год рождения".');
        } elseif ((int) $yearOfBirth < 1900 || (int) $yearOfBirth > (int) date('Y')) {
            $this->formErrors->setErrorYearOfBirth('Поле "Год рождения" должно содержать число из диапазона от 1901 до текущего года.');
        }
    }

    /**
     * Метод проверки переменной: email.
     */
    public function checkEmail()
    {
        $email = $this->user->getEmail();
        if (empty($email)) {
            $this->formErrors->setErrorEmail('Не заполнено поле "Email".');
        } elseif (mb_strlen($email) > 255) {
            $this->formErrors->setErrorEmail('Поле "Email" должно содержать не более 255 символов.');
        } elseif (!preg_match('/.+@.+/ui', $email)) {
            $this->formErrors->setErrorEmail('Поле "Email" должно содержать адрес электронной почты.');
        }
    }

    /**
     * Метод проверки переменной: пароль.
     */
    public function checkPassword()
    {
        $password = $this->user->getPassword();
        if (empty($password)) {
            $this->formErrors->setErrorPassword('Не заполнено поле "Пароль".');
        } elseif (mb_strlen($password) < 6) {
            $this->formErrors->setErrorPassword('Поле "Пароль" должно содержать не менее 6 символов.');
        } elseif (mb_strlen($password) > 128) {
            $this->formErrors->setErrorPassword('Поле "Пароль" должно содержать не более 128 символов.');
        }
    }

    /**
     * Проверяет поле "Подтверждение пароля".
     */
    public function checkPasswordConfirm()
    {
        $password = $this->user->getPassword();
        $passwordConfirm = $this->user->getPasswordConfirm();
        if (empty($passwordConfirm)) {
            $this->formErrors->setErrorPasswordConfirm('Не заполнено поле "Подтверждение пароля".');
        } elseif ($password !== $passwordConfirm) {
            $this->formErrors->setErrorPasswordConfirm('Поля "Пароль" и "Подтверждение пароля" не совпадают.');
        }
    }

    /**
     * Get the value of Объект пользователя.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of Объект пользователя.
     *
     * @param User user
     *
     * @return self
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of Объект для хранения ошибок.
     *
     * @return FormErrors
     */
    public function getFormErrors()
    {
        return $this->formErrors;
    }

    /**
     * Set the value of Объект для хранения ошибок.
     *
     * @param FormErrors formErrors
     *
     * @return self
     */
    public function setFormErrors(FormErrors $formErrors)
    {
        $this->formErrors = $formErrors;

        return $this;
    }
}
