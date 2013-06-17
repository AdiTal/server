<?php
/**
 * @package plugins.caption
 * @subpackage api.filters
 */
class KalturaCaptionAssetFilter extends KalturaCaptionAssetBaseFilter
{

	static private $map_between_objects = array
	(
		"captionParamsIdEqual" => "_eq_flavor_params_id",
		"captionParamsIdIn" => "_in_flavor_params_id",
	);

	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}	
	
}
