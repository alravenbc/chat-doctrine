<?php

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/lib/Framework.php";
require_once __DIR__ . "/lib/Session.php";


$entityManager = Framework::getInstance()->getEntityManager();

session_start();