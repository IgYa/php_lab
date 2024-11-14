<?php

namespace Controllers;

use Models\UserModel;

/**
 * Class UserController
 *
 * Контролер для керування діями користувача.
 */
class UserController {
    /**
     * Виводить ім'я користувача, отримане з моделі.
     */
    public function showUserName() {
        $userModel = new UserModel();
        echo "User Name: " . $userModel->getUserName() . "<br>";
    }
}

