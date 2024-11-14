<?php

require_once __DIR__ . '/autoload.php';

use Controllers\UserController;
use Views\UserView;

$userController = new UserController();
$userController->showUserName();

$userView = new UserView();
$userView->displayMessage();
