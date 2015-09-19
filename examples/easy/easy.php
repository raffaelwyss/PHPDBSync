<?php

namespace PHPDBSync;

require_once '../../PHPDBSync.php';

$sync = new PHPDBSync();
$sync->setDB('1', 'mysql', 'localhost', 'synctest', 'root', '');
$sync->synchronisation('1', '2');





