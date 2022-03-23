<?php
session_start();
require_once 'inc/functions.php';

if (!isset($_SESSION['userId'])) {
    header('Location: index.php?logged_in=0');
}

$mediaId = $_GET['id'];
$listId = add_media($_SESSION['accessToken'], $mediaId);