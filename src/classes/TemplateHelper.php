<?php
/**
 * Класс для вывода информации в HTML.
 */
class TemplateHelper
{
    /**
     * Хранит сообщение об ошибке.
     *
     * @var string
     */
    private $errorMessage;
    /**
     * Хранит сообщение об успешном выполнении
     * @var string
     */
    private $messageSuccess;
  /**
   * Хранит объект ошибок формы.
   *
   * @var FormErrors;
   */
  private $formErrors;
  /**
   * Название страницы, подставдляеться в тег.
   *
   * @var string
   */
  private $pageTitle;
  /**
   * Хранит объект пользователя.
   *
   * @var User
   */
  private $user;
  /**
   * Хранит класс модели постраничной нафигации.
   *
   * @var Pager
   */
  private $pager;
  /**
   * Массив пользователей.
   *
   * @var array
   */
  private $arrayUsers;

/**
 * Объект пользователя внедряеться через конструктор. Также может быть внедрен объект ошибок формы
 * @param User   $user   объей пользователя
 * @param FormErrors $errors Объект ошибок формы, необязательный параметр
 */
    public function __construct(User $user, $errors = null)
    {
        $this->formErrors = $errors;
        $this->user = $user;
    }

/**
 * Метод для экранирования строки
 * @param  string $str Строка
 * @return string      Возвращает экранированную строку
 */
    public function escape($str)
    {
        return htmlspecialchars($str, ENT_QUOTES);
    }

/**
 * Получить html код элемента списка
 * @param  integer $prop Значение 1 если ссылка предыдущей страницы, 0 если следующей
 * @return string      Html код элемента списка
 */
    public function getPaginationLink($prop)
    {
        $page = $this->getPager()->getCurrentPage();
        $prop = $prop == 1 ? 1 : intval($this->getPager()->getTotalPages());
        $url = '<li';
        $url .= $page == $prop ? ' class="disabled">' : '>';
        $url .= $page !== $prop && $prop !== 1 ? '<a href="'.$this->getPager()->getLinkForPage($page + 1).'" aria-label="Next">' : ($page !== $prop ? '<a href="'.$this->getPager()->getLinkForPage($page - 1).'" aria-label="Previous">' : '');
        $url .= '<span aria-hidden="true">';
        $url .= $prop == 1 ? '&laquo;' : '&raquo;';
        $url .= '</span>';
        $url .= $page !== $prop ? '</a>' : '';
        $url .= '</li>';

        return $url;
    }

/**
 * Получить HTML код строк пользователей
 * @param  string $token Токен для защиты от XSRF
 * @return string        Html код строк пользователей для таблицы
 */
    public function getUsersTableRows($token)
    {
        $array = $this->arrayUsers;
        $result = '';
        $page = $this->getPager()->getCurrentPage();
        $i = ($this->getPager()->getCurrentPage() * $this->getPager()->getRecordsPerPage() + 1) - $this->getPager()->getRecordsPerPage();
        foreach ($array as $value) {
            $result .= '<tr><td>'.$i.'</td><td>'.$value->getFirstName().'</td><td>'.$value->getLastName().'</td><td>'.$value->getYearOfBirth().'</td><td>'.$value->getEmail().
            '</td><td>'.($value->getIsAdmin() ? 'Администратор' : 'Пользователь')."</td><td><a class='margin-right' href='/admin.php?edit=".$value->getId()."'>Изменить</a><a href='/admin.php?token=".$token.($page!==1?'&page='.$page:'')."&delete=".$value->getId()."'>Удалить</a></td></tr>".PHP_EOL;
            ++$i;
        }

        return $result;
    }

  /**
   * Возвращает сформированный HTML код для строки формы в зависимости от ошибки.
   *
   * @param  string $var  Часть названия метода возвращающего ошибку
   *
   * @return string       Строка с классом или пустая
   */
  public function getClass($var, $isLogin = false)
  {
      if ($this->formErrors->getCountErrors() == null) {
          return '';
      }
      $methodName = 'getError'.$var;
      $result = ($this->formErrors->$methodName() !== null) ? ' has-error' : ' has-success';
      if ($isLogin && $result === ' has-success') {
          return '';
      }

      return $result;
  }

/**
 * Получить значение экранированной переменной пользователя
 * @param  mixed $var Значение переменной
 * @return string     Экранированное знаечение переменной
 */
    public function getUserValue($var)
    {
        $methodName = 'get'.$var;

        return htmlspecialchars($this->user->$methodName(), ENT_QUOTES);
    }

/**
 * Получить HTML код ошибки
 * @return string HTML код ошибки
 */
    public function getMessageErrors()
    {
        $arrayObject = $this->getFormErrors()->getErrors();
        $result = '';
        foreach ($arrayObject as $value) {
            if (!empty($value)) {
                $result .= $value.'<br>';
            }
        }

        return $result;
    }

    /**
     * Get the value of Хранит объект ошибок формы.
     *
     * @return FormErrors;
     */
    public function getFormErrors()
    {
        return $this->formErrors;
    }

    /**
     * Set the value of Хранит объект ошибок формы.
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

    /**
     * Get the value of Хранит объект пользователя.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of Хранит объект пользователя.
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
     * Get the value of Название страницы, подставдляеться в тег <title>.
     *
     * @return string
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * Set the value of Название страницы, подставдляеться в тег <title>.
     *
     * @param string pageTitle
     *
     * @return self
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;

        return $this;
    }

    /**
     * Get the value of Хранит сообщение об ошибке.
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Set the value of Хранит сообщение об ошибке.
     *
     * @param string errorMessage
     *
     * @return self
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * Get the value of Массив пользователей.
     *
     * @return array
     */
    public function getArrayUsers()
    {
        return $this->arrayUsers;
    }

    /**
     * Set the value of Массив пользователей.
     *
     * @param array arrayUsers
     *
     * @return self
     */
    public function setArrayUsers(array $arrayUsers)
    {
        $this->arrayUsers = $arrayUsers;

        return $this;
    }

    /**
     * Get the value of Хранит класс модели постраничной нафигации.
     *
     * @return Pager
     */
    public function getPager()
    {
        return $this->pager;
    }

    /**
     * Set the value of Хранит класс модели постраничной нафигации.
     *
     * @param Pager pager
     *
     * @return self
     */
    public function setPager(Pager $pager)
    {
        $this->pager = $pager;

        return $this;
    }



    /**
     * Get the value of Хранит сообщение об успешном выполнении
     *
     * @return string
     */
    public function getMessageSuccess()
    {
        return $this->messageSuccess;
    }

    /**
     * Set the value of Хранит сообщение об успешном выполнении
     *
     * @param string messageSuccess
     *
     * @return self
     */
    public function setMessageSuccess($messageSuccess)
    {
        $this->messageSuccess = $messageSuccess;

        return $this;
    }

}
