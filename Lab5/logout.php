<?php
session_start();
require 'db.php';

global $db;

logout($db);

// Перенаправлення на головну сторінку
header('Location: index.php');
exit;
?>
