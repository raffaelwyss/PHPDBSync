<?php

namespace PHPDBSync;

require_once '../../PHPDBSync.php';

//error_reporting(0);

$sync = new PHPDBSync();
$sync->setShowMessages('echo');
$sync->setDB('1', 'mysql', 'localhost', 'testsource', 'root', '');
$sync->setDB('2', 'mysql', 'localhost', 'testtarget', 'root', '');
$sync->synchronisation('1', '2');





