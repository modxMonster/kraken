<?php
//$coreLocation = $modx->getOption('kraken.core_path');
$coreLocation = $modx->getOption('kraken.core_path', null, $modx->getOption('core_path') . 'components/kraken/');

require_once($coreLocation . 'controllers/KrakenBlocksController.php');

KrakenBlocksController::loadService($modx);

$resIdStr = $modx->resource->get('id');
$resId = $resIdStr + 0;


if (empty($resId) || is_null($resId) ) {
  throw new Exception("Missing params for: getResourcesContent(resId: $resId)");
}
//TODO this must be changed go generate the tags
return $modx->KrakenBlocksController->generateVueComponentsFiles($resId);