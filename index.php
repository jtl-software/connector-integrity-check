<?php
$requestUri = $_SERVER['REQUEST_URI'] === '/' ? '' : '/' . rtrim($_SERVER['REQUEST_URI'], '/');
$redirectUrl = sprintf('%s/public', $requestUri);
header(sprintf('Location: %s', $redirectUrl));