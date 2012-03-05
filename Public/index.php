<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');

define('LIBRARY_DIR', '/home/felipe/TG-II/Library');
define('DATA_DIR', '/home/felipe/TG-II/Data');

set_include_path(get_include_path() . PATH_SEPARATOR . LIBRARY_DIR);

define('GRAPH_DATA_DIR', DATA_DIR . DIRECTORY_SEPARATOR . 'Graphs');
define('RESULT_DATA_DIR', DATA_DIR . DIRECTORY_SEPARATOR . 'Results');

define('ALGORITHM_DIR', LIBRARY_DIR . DIRECTORY_SEPARATOR . 'Algorithms');

require 'RestService.php';

$servico = new RestService();

$servico->handle();

$servico->SendResponse();

/**
LIBRARY_DIR: caminho absoluto do diretório que contém a biblioteca do sistema, além dos arquivos das classes de algoritmos (LIBRARY_DIR/Algorithms).
DATA_DIR: caminho absoluto do diretório que contém os arquivos de grafo (DATA_DIR/Graphs) e de resultado (DATA_DIR/Results).

Ainda existem outras constantes, mas estas não precisam ser modificadas:
ALGORITHM_DIR: diretório onde ficam as classes dos algoritmos desenvolvidos.
GRAPH_DATA_DIR: diretório que contém os arquivos de grafo.
RESULT_DATA_DIR: diretório que contém os arquivos de resultado.
*/

