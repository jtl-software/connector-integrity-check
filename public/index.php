<?php

use Jtl\Connector\Integrity\IntegrityCheck;

require_once dirname(__DIR__) . '/bootstrap.php';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="JTL-Connector-Systemcheck">
    <meta name="author" content="JTL-Software GmbH">
    <link rel="shortcut icon" href="favicon.ico" />

    <title>JTL-Connector-Systemcheck v<?php echo IntegrityCheck::VERSION; ?></title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link href="css/application.css" rel="stylesheet">
</head>
<body style="background: #f4f4f4 url(images/pageBackground.png) repeat-x;">
<div id="app">
    <div class="container">
        <a class="logo">
            <img src="images/logo-jtlsoftware.png" alt="JTL-Software GmbH">
        </a>
        <div class="navbar navbar-inverse navbar-transparent" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="https://www.jtl-software.de/">JTL-Software GmbH</a></li>
                    <li><a href="https://guide.jtl-software.de/jtl-connector/">JTL-Guide</a></li>
                    <li><a href="https://forum.jtl-software.de/">JTL-Forum</a></li>
                </ul>
            </div>
        </div>
    </div>

    <integrity></integrity>
</div>

<footer class="footer">
    <div class="container">
        <p class="text-muted">JTL-Connector-Systemcheck v<?php echo IntegrityCheck::VERSION; ?> © 2017 JTL-Software-GmbH</p>
    </div>
</footer>

<script src="js/application.js"></script>
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>