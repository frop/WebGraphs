<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');

define('LIBRARY_DIR', '../Library');
set_include_path(get_include_path() . PATH_SEPARATOR . LIBRARY_DIR);

define('DATA_DIR', '../Data');
define('GRAPH_DATA_DIR', DATA_DIR . DIRECTORY_SEPARATOR . 'Graphs');
define('RESULT_DATA_DIR', DATA_DIR . DIRECTORY_SEPARATOR . 'Results');

define('ALGORITHM_DIR', LIBRARY_DIR . DIRECTORY_SEPARATOR . 'Algorithm');

require 'RestService.php';
require 'Grafo.php';

$servico = new RestService();

$servico->handle();

echo $servico->response();
