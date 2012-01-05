<?php

require '../Library/GraphBase.php';

$G = new GraphBase();
for($i=0;$i<10;$i++){
	$G->createVertex($i);
	$G->createEdge($i, (int) rand(0,$i-1));
}

$jsonFile = fopen('grafo.json', 'w');
if (!$jsonFile){
	return 0;
}
$graphJson = json_encode($G, JSON_FORCE_OBJECT);
fprintf($jsonFile, "%s\n", $graphJson);

