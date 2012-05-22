<?php
/**
 * @package plugins.document
 * @subpackage api.objects
 */
class KalturaSwfFlavorParamsOutput extends KalturaFlavorParamsOutput 
{
	public function toObject($object = null, $skip = array())
	{
		if(is_null($object))
			$object = new SwfFlavorParamsOutput();
		
		parent::toObject($object, $skip);
		$object->setType(DocumentPlugin::getAssetTypeCoreValue(DocumentAssetType::SWF));
		return $object;
	}
	
	private static $map_between_objects = array
	(
	);
	
	// attributes that defined in flavorParams and not in SwfFlavorParamsOutput
	private static $skip_attributes = array
	(
		"videoConstantBitrate",
		"videoBitrateTolerance",
	);
	
	public function getMapBetweenObjects()
	{
		$map = array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
		foreach(self::$skip_attributes as $skip_attribute)
		{
			if(isset($map[$skip_attribute]))
				unset($map[$skip_attribute]);
				
			$key = array_search($skip_attribute, $map);
			if($key !== false)
				unset($map[$key]);
		}
		return $map;
	}
}