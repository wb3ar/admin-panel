<?php
// Проверяем был ли выполнен Post запрос
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Защита от XSRF
    $tokenPost = array_key_exists('token', $_POST) ? strval($_POST['token']) : '';
    $tokenSession = array_key_exists('token', $_SESSION) ? strval($_SESSION['token']) : '';
    if ($tokenPost !== $tokenSession) {
      if ($admin) {
        header('Location: /admin.php?edit='.$idEdit);
        exit;
      }
      header('Location: /registration.php');
      exit;

    }
    if ($admin) {
        $oldEmail = $user->getEmail();
    }
    $user->setFromPostValues($_POST);
    $userValidator = new UserValidator($user);
  // Проверяем введенные данные
  if ($admin) {
      $setPassword = $userValidator->validateUserEdit();
  } else {
      $userValidator->validateUserRegistration();
      $setPassword = true;
  }

  // Если ошибок нет, устанавливаем соединение с базой
  if ($userValidator->getFormErrors()->getCountErrors() === 0) {
      $userDataGateway = new UserDataGateway($settings['connection']);
      $error = $userDataGateway->getError();
      // Если удалось подключиться к базе, сгенерировать хеш пароля и добавить пользователя в базу
      if (empty($error)) {
          if ($setPassword) {
              $user->setPasswordHash();
          }
          if ($admin) {
              $setEmail = true;
              if ($user->getEmail() === $oldEmail) {
                  $setEmail = false;
              }
              $user->setId($idEdit);
              $userDataGateway->updateUserById($user, $setPassword, $setEmail);
          } else {
              $user->setSessionId(session_id());
              $userDataGateway->addUser($user);
              $user->setId($userDataGateway->getPdo()->lastInsertId());
          }
          $error = $userDataGateway->getError();
      }
  }
    if (!empty($error)) {
        $userValidator->getFormErrors()->setErrorDataBase($error);
    } elseif ($admin) {
        $_SESSION['messageSuccess'] = 'Пользователь успешно изменен';
    }
    $_SESSION['errors'] = $userValidator->getFormErrors();
    // Затираем пароль для безопасности, чтобы не хранился в сессиях
    if ($admin) {
        header('Location: /admin.php?edit='.$idEdit);
    } else {
        $user->setPassword('');
        $_SESSION['user'] = $user;
        header('Location: /registration.php');
    }
    exit;
}
$t = new TemplateHelper($user, $errors);
$t->setPageTitle(($admin ? 'Изменить пользователя' : 'Регистрация'));
$messageSuccess = array_key_exists('messageSuccess', $_SESSION) ? $_SESSION['messageSuccess'] : '';
unset($_SESSION['messageSuccess']);
$t->setMessageSuccess($messageSuccess);

// Защита от XSRF
$passwordGenerator = new PasswordGenerator(32);
$token = $passwordGenerator->getPassword();
$_SESSION['token'] = $token;

require __DIR__.'/../templates/'.($admin ? 'adminEdit' : 'registration').'Page.html';
