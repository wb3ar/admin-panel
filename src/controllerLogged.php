<?php
// Если была нажата ссылка - Выход
if (isset($_GET['logout'])) {
  session_unset();
  session_destroy();
  header('Location: /');
  exit;
}
$t = new TemplateHelper($user);
$t->setPageTitle('Ваши данные');
require __DIR__.'/../templates/loggedPage.html';
