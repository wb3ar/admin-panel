<?php

// Проверяем были ли сохранены переменные в сессию
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $errors = array_key_exists('errors', $_SESSION) ? $_SESSION['errors'] : new FormErrors();
    unset($_SESSION['errors']);
    if (!empty($user->getId()) && $controller==="Registration") {
      header('Location: /');
      exit;
    }
    // Если в объекте пользователя есть ID, загружаем страницу для залогиненого состояния
    elseif (!empty($user->getId())) {
        if (!($admin && !$user->getIsAdmin())) {
            $userDataGateway = new UserDataGateway($settings['connection']);
            $error = $userDataGateway->getError();
            $user->setSessionId(session_id());
            if (empty($error) && $userDataGateway->checkSessionId($user)) {
                require __DIR__.'/../src/controller'.($admin?'Admin':'').'Logged.php';
                exit;
            }
            if (!empty($error)) {
                $errors->setErrorDataBase($error);
            }
        }
    }
    // Очищаем сессию
    session_unset();

  // Если нет переменных в сессии создаем пустые объекты
} else {
    $user = new User();
    $errors = new FormErrors();
}
require __DIR__.'/../src/controller'.$controller.'.php';
