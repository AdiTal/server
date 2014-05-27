<?php
/**
 * @package plugins.thumbCuePoint
 */
class ThumbCuePointPlugin extends KalturaPlugin implements IKalturaCuePoint, IKalturaTypeExtender
{
	const PLUGIN_NAME = 'thumbCuePoint';
	const CUE_POINT_VERSION_MAJOR = 1;
	const CUE_POINT_VERSION_MINOR = 0;
	const CUE_POINT_VERSION_BUILD = 0;
	const CUE_POINT_NAME = 'cuePoint';
	
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	/* (non-PHPdoc)
	 * @see IKalturaPermissions::isAllowedPartner()
	 */
	public static function isAllowedPartner($partnerId)
	{
		$partner = PartnerPeer::retrieveByPK($partnerId);
		return $partner->getPluginEnabled(self::PLUGIN_NAME);
	}
	
	/* (non-PHPdoc)
	 * @see IKalturaEnumerator::getEnums()
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('timedThumbAssetType', 'thumbCuePointType');
	
		if($baseEnumName == 'assetType')
			return array('timedThumbAssetType');
			
		if($baseEnumName == 'CuePointType')
			return array('thumbCuePointType');	
			
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see IKalturaPending::dependsOn()
	 */
	public static function dependsOn()
	{
		$cuePointVersion = new KalturaVersion(
			self::CUE_POINT_VERSION_MAJOR,
			self::CUE_POINT_VERSION_MINOR,
			self::CUE_POINT_VERSION_BUILD);
			
		$dependency = new KalturaDependency(self::CUE_POINT_NAME, $cuePointVersion);
		return array($dependency);
	}
	
	/* (non-PHPdoc)
	 * @see IKalturaTypeExtender::getExtendedTypes()
	 */
	public static function getExtendedTypes($baseClass, $enumValue)
	{
		if($baseClass == assetPeer::OM_CLASS && $enumValue == assetType::THUMBNAIL)
		{
			return array(
				ThumbCuePointPlugin::getAssetTypeCoreValue(timedThumbAssetType::TIMED_THUMB_ASSET)
			);
		}
		
		return null;
	}
	
	/* (non-PHPdoc)
	 * @see IKalturaObjectLoader::loadObject()
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		if($baseClass == 'KalturaThumbAsset' && $enumValue == self::getAssetTypeCoreValue(timedThumbAssetType::TIMED_THUMB_ASSET))
			return new KalturaTimedThumbAsset();
			
		if($baseClass == 'KalturaCuePoint' && $enumValue == self::getCuePointTypeCoreValue(thumbCuePointType::THUMB))
			return new KalturaThumbCuePoint();
		
		return null;
	}

	/* (non-PHPdoc)
	 * @see IKalturaObjectLoader::getObjectClass()
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{
		if($baseClass == 'asset' && $enumValue == self::getAssetTypeCoreValue(timedThumbAssetType::TIMED_THUMB_ASSET))
			return 'timedThumbAsset';
			
		if($baseClass == 'CuePoint' && $enumValue == self::getCuePointTypeCoreValue(thumbCuePointType::THUMB))
			return 'ThumbCuePoint';
		
		return null;
	}
	
/* (non-PHPdoc)
	 * @see IKalturaSchemaContributor::contributeToSchema()
	 */
	public static function contributeToSchema($type)
	{
		//TBD add thumb asset support to xsd
		$coreType = kPluginableEnumsManager::apiToCore('SchemaType', $type);
		if(
			$coreType != SchemaType::SYNDICATION
			&&
			$coreType != CuePointPlugin::getSchemaTypeCoreValue(CuePointSchemaType::SERVE_API)
			&&
			$coreType != CuePointPlugin::getSchemaTypeCoreValue(CuePointSchemaType::INGEST_API)
		)
			return null;
			
		$xsd = '
		
	<!-- ' . self::getPluginName() . ' -->
	
	<xs:complexType name="T_scene_thumbCuePoint">
		<xs:complexContent>
			<xs:extension base="T_scene">
				<xs:sequence>
					<xs:element maxOccurs="1" minOccurs="1" ref="slide" />
					<xs:element ref="scene-extension" minOccurs="0" maxOccurs="unbounded" />
				</xs:sequence>
			</xs:extension>
		</xs:complexContent>
	</xs:complexType>
	
	<xs:complexType name="T_slide">
		<xs:sequence>
			<xs:choice maxOccurs="1" minOccurs="1">
				<xs:element maxOccurs="1" minOccurs="1" ref="serverFileContentResource"></xs:element>
				<xs:element maxOccurs="1" minOccurs="1" ref="urlContentResource"></xs:element>
				<xs:element maxOccurs="1" minOccurs="1" ref="sshUrlContentResource"></xs:element>
				<xs:element maxOccurs="1" minOccurs="1" ref="remoteStorageContentResource"></xs:element>
				<xs:element maxOccurs="1" minOccurs="1" ref="remoteStorageContentResources"></xs:element>
				<xs:element maxOccurs="1" minOccurs="1" ref="entryContentResource"></xs:element>
				<xs:element maxOccurs="1" minOccurs="1" ref="assetContentResource"></xs:element>
				<xs:element maxOccurs="1" minOccurs="1" ref="contentResource-extension"></xs:element>
			</xs:choice>
		</xs:sequence>
		<xs:attribute name="slideThumbAssetId" type="xs:string" use="optional"/>
		<xs:attribute name="offset" type="xs:string" use="optional"/>			
	</xs:complexType>
	
	<xs:element name="scene-thumb-cue-point" type="T_scene_thumbCuePoint" substitutionGroup="scene">
		<xs:annotation>
			<xs:documentation>Single thumb cue point element</xs:documentation>
			<xs:appinfo>
				<example>
					<scene-thumb-cue-point sceneId="{scene id}" entryId="{entry id}">
						<sceneStartTime>00:00:05.3</sceneStartTime>
						<tags>
							<tag>my_tag</tag>
						</tags>
						<slide>
							<urlContentResource url="URL_TO_FILE"/>
						</slide>
					</scene-thumb-cue-point>
				</example>
			</xs:appinfo>
		</xs:annotation>
	</xs:element>
	
	<xs:element name="slide" type="T_slide">
		<xs:annotation>
			<xs:documentation>
				The slide image to attahce to tht thumb cue point ellement
			</xs:documentation>
		</xs:annotation>
	</xs:element>
		';
		
		return $xsd;
	}
	
	/* (non-PHPdoc)
	 * @see IKalturaCuePoint::getCuePointTypeCoreValue()
	 */
	public static function getCuePointTypeCoreValue($valueName)
	{
		$value = self::getPluginName() . IKalturaEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore('CuePointType', $value);
	}
	
	/**
	 * @return int id of dynamic enum in the DB.
	 */
	public static function getAssetTypeCoreValue($valueName)
	{
		$value = self::getPluginName() . IKalturaEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore('assetType', $value);
	}
	
	/**
	 * @return string external API value of dynamic enum.
	 */
	public static function getApiValue($valueName)
	{
		return self::getPluginName() . IKalturaEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
	}
	
	/* (non-PHPdoc)
	 * @see IKalturaCuePointXmlParser::parseXml()
	 */
	public static function parseXml(SimpleXMLElement $scene, $partnerId, CuePoint $cuePoint = null)
	{
		if($scene->getName() != 'scene-thumb-cue-point')
			return $cuePoint;
			
		if(!$cuePoint)
			$cuePoint = kCuePointManager::parseXml($scene, $partnerId, new ThumbCuePoint());
			
		if(!($cuePoint instanceof ThumbCuePoint))
			return null;
		
		return $cuePoint;
	}
	
	/* (non-PHPdoc)
	 * @see IKalturaCuePointXmlParser::generateXml()
	 */
	public static function generateXml(CuePoint $cuePoint, SimpleXMLElement $scenes, SimpleXMLElement $scene = null)
	{
		if(!($cuePoint instanceof ThumbCuePoint))
			return $scene;
			
		if(!$scene)
			$scene = kCuePointManager::generateCuePointXml($cuePoint, $scenes->addChild('scene-thumb-cue-point'));
		
		if($cuePoint->getEndTime())
			$scene->addChild('sceneEndTime', kXml::integerToTime($cuePoint->getEndTime()));
	
		$scene->addChild('thumbAssetId', $cuePoint->getTimedThumbAssetId());
		
		return $scene;
	}
	
	/* (non-PHPdoc)
	 * @see IKalturaCuePointXmlParser::syndicate()
	 */
	public static function syndicate(CuePoint $cuePoint, SimpleXMLElement $scenes, SimpleXMLElement $scene = null)
	{
		if(!($cuePoint instanceof ThumbCuePoint))
			return $scene;
			
		if(!$scene)
			$scene = kCuePointManager::syndicateCuePointXml($cuePoint, $scenes->addChild('scene-thumb-cue-point'));
		
		if($cuePoint->getEndTime())
			$scene->addChild('sceneEndTime', kXml::integerToTime($cuePoint->getEndTime()));
	
		$scene->addChild('thumbAssetId', $cuePoint->getTimedThumbAssetId());
			
		return $scene;
	}
}