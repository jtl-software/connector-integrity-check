<?php
require_once (__DIR__ . '/../src/bootstrap.php');

if (!isset($_GET['t'])) {
    header('HTTP/1.1 400 Bad Request');
    die();
}

$r = [];
$r['data'] = [];
$r['data']['test'] = 0;
$r['data']['progress'] = 0.0;
$r['data']['finished'] = false;

// @TODO: do stuff
switch ((int) $_GET['t']) {
    case 1:
        $r['data']['test'] = 1;
        $r['data']['progress'] = 20.0;
        sleep(1);
        break;
    case 2:
        $r['data']['test'] = 2;
        $r['data']['progress'] = 40.0;
        sleep(1);
        break;
    case 3:
        $r['data']['test'] = 3;
        $r['data']['progress'] = 60.0;
        sleep(1);
        break;
    case 4:
        $r['data']['test'] = 4;
        $r['data']['progress'] = 80.0;
        sleep(1);
        break;
    default:
        $r['data']['test'] = (int) $_GET['t'];
        $r['data']['progress'] = 100;
        $r['data']['finished'] = true;
        sleep(1);
        break;
}

header('Content-Type: application/json');
echo json_encode($r);