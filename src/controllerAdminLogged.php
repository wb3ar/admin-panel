<?php
// Если была нажата ссылка - Выход
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: /admin.php');
    exit;
}
$adminId = $user->getId();
$adminEmail = $user->getEmail();
$idDelete = array_key_exists('delete', $_GET) ? strval($_GET['delete']) : '';
$idEdit = array_key_exists('edit', $_GET) ? strval($_GET['edit']) : '';
$errorDelete = '';
$errorEdit = '';
// Если была нажата ссылка - Удалить
if ($idDelete) {
  // Защита от XSRF
    $tokenPost = array_key_exists('token', $_GET) ? strval($_GET['token']) : '';
    $tokenSession = array_key_exists('token', $_SESSION) ? strval($_SESSION['token']) : '';
    if ($tokenPost !== $tokenSession) {
        header('Location: /admin.php');
        exit;
    }
    $userDataGateway = new UserDataGateway($settings['connection']);
    if (!$userDataGateway->deleteUserById($idDelete)) {
        $errorDelete = 'Произошла ошибка. Не удалось удалить пользователя.';
    }
    else {
      $error = $userDataGateway->getError();
      if (empty($error)) {
        $messageSuccess="Пользователь успешно удален.";
      }
    }
}
// Если была нажата ссылка - Изменить
elseif ($idEdit) {
    $userDataGateway = new UserDataGateway($settings['connection']);
    $user = $userDataGateway->getUserById($idEdit);
    if ($user) {
        $page= array_key_exists('page', $_SESSION) ? $_SESSION['page'] : 1;
        require __DIR__.'/../src/controllerRegistration.php';
        exit;
    }
    $errorEdit = $userDataGateway->getError();
}
$userDataGateway = new UserDataGateway($settings['connection']);
$error = $userDataGateway->getError();
// Если удалось подключиться к базе, получаем колличество пользователей
if (empty($error)) {
  $totalRecords=$userDataGateway->getCountUsers();
  $page = array_key_exists('page', $_GET) ? intval($_GET['page']) : 1;
  $_SESSION['page']=$page;
  $pager = new Pager($totalRecords, $settings['adminPanel']['recordsPerPage'], 'admin.php', $page);
  // Если удалось подключиться к базе, получаем заданное колличество пользователей
     if (empty($error)) {
         $arrayUsers = $userDataGateway->getUsers($pager->getRecordsPerPage(), $pager->getOffset());

         $error = $userDataGateway->getError();
     }
 }

$t = new TemplateHelper($user);
if (isset($messageSuccess)) {
  $t->setMessageSuccess($messageSuccess);
}
$t->setPager($pager);
$t->setErrorMessage($error.((!empty($error) && (!empty($errorDelete) || !empty($errorEdit))) ? '<br>' : '').$errorDelete.$errorEdit);
  if (empty($error)) {
    $t->setArrayUsers($arrayUsers);
  }
$t->setPageTitle('Панель администратора');

// Защита от XSRF
$passwordGenerator = new PasswordGenerator(32);
$token = $passwordGenerator->getPassword();
$_SESSION['token'] = $token;

require __DIR__.'/../templates/adminLoggedPage.html';
