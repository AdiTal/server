<?php
require_once("../../../bootstrap.php");
KalturaLog::setContext("TESTME");
$service = $_GET["service"];
$action = $_GET["action"];
$bench_start = microtime(true);
KalturaLog::INFO ( ">------- api_v3 testme [$service][$action]-------");

function toArrayRecursive(KalturaPropertyInfo $propInfo)
{
	return $propInfo->toArray();
}

$actionInfo = null;
try
{
	$serviceReflector = KalturaServiceReflector::constructFromServiceId($service);
	
	$actionParams = $serviceReflector->getActionParams($action);
	$actionInfo = $serviceReflector->getActionInfo($action);
	
	$actionInfo = array(
		"actionParams" => array(),
	    "description" => $actionInfo->description
	);

	foreach($actionParams as $actionParam)
	{
		$actionInfo["actionParams"][] = toArrayRecursive($actionParam); 
	}
}
catch ( Exception $ex )
{
	KalturaLog::ERR ( "<------- api_v3 testme [$service][$action\n" . 
		 $ex->__toString() .  " " ." -------");
}
echo json_encode($actionInfo);
$bench_end = microtime(true);
KalturaLog::INFO ( "<------- api_v3 testme [$service][$action][" . ($bench_end - $bench_start) . "] -------");

?>