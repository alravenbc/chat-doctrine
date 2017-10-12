<?php
// list_users.php
require_once __DIR__ . "/bootstrap.php";


$userRepository = $entityManager->getRepository('User');
$users = $userRepository->findAll();

/** @var $user User */
foreach ($users as $user) {
    echo sprintf("-%s\n", $user->getUsername());
}

