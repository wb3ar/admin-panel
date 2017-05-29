<?php
// Проверяем был ли выполнен Post запрос
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Защита от XSRF
    $tokenPost = array_key_exists('token', $_POST) ? strval($_POST['token']) : '';
    $tokenSession = array_key_exists('token', $_SESSION) ? strval($_SESSION['token']) : '';
    if ($tokenPost !== $tokenSession) {
        if ($admin) {
            header('Location: /admin.php');
            exit;
        }
        header('Location: /index.php');
        exit;
    }
    $user->setFromPostValues($_POST);
    $userValidator = new UserValidator($user);
    $userValidator->validateUserLogin();
    if ($userValidator->getFormErrors()->getCountErrors() === 0) {
        $userDataGateway = new UserDataGateway($settings['connection']);
        $error = $userDataGateway->getError();
          // Если удалось подключиться к базе, ищем есть ли такой логин и проверяем пароль
             if (empty($error)) {
                 $user->setSessionId(session_id());
                 $user = $userDataGateway->getUserForLogin($user, $admin);
                 // Затираем для безопасности пароль, чтобы не хранился в сессиях
                 $user->setPassword('');
                 $error = $userDataGateway->getError();
             }
        if (!empty($error)) {
            $userValidator->getFormErrors()->setErrorDataBase($error);
        }
    }
    $_SESSION['errors'] = $userValidator->getFormErrors();
    $_SESSION['user'] = $user;
    header('Location: /'.($admin ? 'admin.php' : ''));
    exit;
}

$t = new TemplateHelper($user, $errors);
$t->setPageTitle('Вход'.($admin ? ' в панель администратора' : ''));

// Защита от XSRF
$passwordGenerator = new PasswordGenerator(32);
$token = $passwordGenerator->getPassword();
$_SESSION['token'] = $token;

require __DIR__.'/../templates/'.($admin ? 'admin' : '').'loginPage.html';
