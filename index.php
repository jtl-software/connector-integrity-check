<?php
$requestUri = rtrim($_SERVER['REQUEST_URI'], '/') . '/';
$redirectUrl = sprintf('%spublic', $requestUri);
header(sprintf('Location: %s', $redirectUrl));