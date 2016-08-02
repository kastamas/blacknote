<?php

$exe_deep = 0; // Глубина в подпапках (0 - если лежит в корне)

$route = explode('/', $_SERVER['REQUEST_URI']);
$route = array_filter($route);
array_splice($route, 0, $exe_deep);

if (empty($route[0])) { $route[0] = 'index'; }

$exe_mod_file = $route[0] . '.php';

// Run module!
if (file_exists($exe_mod_file)) { include_once($exe_mod_file); }
else { header('HTTP/1.1 404 Not Found'); include_once ('404.php'); }

