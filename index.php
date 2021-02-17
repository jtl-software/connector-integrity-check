<?php
$redirectUrl = sprintf('%s/public', $_SERVER['REQUEST_URI']);
header(sprintf('Location: %s', $redirectUrl));