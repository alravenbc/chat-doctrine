<?php
// create_user.php <name> <password>
require_once __DIR__ . "/bootstrap.php";

$newUserName = $argv[1];
$newUserPassword = $argv[2];
$newUserNick = $argv[3];

$user = new User();
$user->setUsername($newUserName);
$user->setPassword($newUserPassword);
$user->setNick($newUserNick);

$entityManager->persist($user);
$entityManager->flush();

echo "Created User " . $user->getUsername() . " with ID " . $user->getId() . "\n";