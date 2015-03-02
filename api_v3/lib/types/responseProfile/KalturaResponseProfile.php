<?php
/**
 * @package api
 * @subpackage objects
 */
class KalturaResponseProfile extends KalturaResponseProfileBase implements IFilterable
{
	/**
	 * Auto generated numeric identifier
	 * 
	 * @var int
	 * @readonly
	 * @filter eq,in
	 */
	public $id;
	
	/**
	 * Friendly name
	 * 
	 * @var string
	 */
	public $name;
	
	/**
	 * Unique system name
	 * 
	 * @var string
	 * @filter eq,in
	 */
	public $systemName;
	
	/**
	 * @var int
	 * @readonly
	 */
	public $partnerId;
	
	/**
	 * Creation time as Unix timestamp (In seconds) 
	 * 
	 * @var time
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $createdAt;
	
	/**
	 * Update time as Unix timestamp (In seconds) 
	 * 
	 * @var time
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $updatedAt;
	
	/**
	 * @var KalturaResponseProfileStatus
	 * @readonly
	 * @filter eq,in
	 */
	public $status;
	
	/**
	 * @var KalturaResponseProfileType
	 */
	public $type;
	
	/**
	 * Comma separated fields list to be included or excluded
	 * 
	 * @var string
	 */
	public $fields;
	
	/**
	 * @var KalturaRelatedFilter
	 */
	public $filter;
	
	/**
	 * @var KalturaFilterPager
	 */
	public $pager;
	
	/**
	 * @var KalturaNestedResponseProfileBaseArray
	 */
	public $relatedProfiles;
	
	/**
	 * @var KalturaResponseProfileMappingArray
	 */
	public $mappings;
	
	public function __construct(ResponseProfile $responseProfile = null)
	{
		if($responseProfile)
		{
			$this->fromObject($responseProfile);
		}
	}
	
	private static $map_between_objects = array(
		'id', 
		'name', 
		'systemName', 
		'partnerId',
		'createdAt',
		'updatedAt',
		'status',
		'type',
		'fields',
		'pager',
		'relatedProfiles',
		'mappings',
	);
	
	/* (non-PHPdoc)
	 * @see KalturaObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	/* (non-PHPdoc)
	 * @see KalturaObject::validateForUsage($sourceObject, $propertiesToSkip)
	 */
	public function validateForUsage($sourceObject, $propertiesToSkip = array())
	{
		// Allow null in case of update
		$allowNull = !is_null($sourceObject);
		
		$this->validatePropertyMinLength('name', 2, $allowNull);
		$this->validatePropertyMinLength('systemName', 2, $allowNull);
		
		if(!$allowNull)
			$this->validatePropertyNotNull('type');
		
		$id = null;
		if($sourceObject)
			$id = $sourceObject->getId();
			
		if(trim($this->systemName) && !$this->isNull('systemName'))
		{
			$systemNameTemplates = ResponseProfilePeer::retrieveBySystemName($this->systemName, $id);
	        if (count($systemNameTemplates))
	            throw new KalturaAPIException(KalturaErrors::RESPONSE_PROFILE_DUPLICATE_SYSTEM_NAME, $this->systemName);
		}
		
		parent::validateForUsage($sourceObject, $propertiesToSkip);
	}
	
	/* (non-PHPdoc)
	 * @see KalturaObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject($object = null, $propertiesToSkip = array())
	{
		if(is_null($object))
		{
			$object = new ResponseProfile();
		}
		
		if($this->filter)
		{
			$object->setFilterApiClassName(get_class($this->filter));
			$object->setFilter($this->filter->toObject());
		}
		
		return parent::toObject($object, $propertiesToSkip);
	}
	
	/* (non-PHPdoc)
	 * @see KalturaObject::fromObject($srcObj, $responseProfile)
	 */
	public function doFromObject($srcObj, KalturaResponseProfileBase $responseProfile = null)
	{
		/* @var $srcObj ResponseProfile */
		parent::doFromObject($srcObj, $responseProfile);
		
		if($srcObj->getFilter() && $this->shouldGet('filter', $responseProfile))
		{
			$filterApiClassName = $srcObj->getFilterApiClassName();
			$this->filter = new $filterApiClassName();
			$this->filter->fromObject($srcObj->getFilter());
		}
	}
	
	/* (non-PHPdoc)
	 * @see KalturaResponseProfileBase::getRelatedProfiles()
	 */
	public function getRelatedProfiles()
	{
		if($this->relatedProfiles)
		{
			return $this->relatedProfiles;
		}
		
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see KalturaResponseProfileBase::getPager()
	 */
	public function getPager()
	{
		return $this->pager;
	}
	
	/* (non-PHPdoc)
	 * @see IFilterable::getExtraFilters()
	 */
	public function getExtraFilters()
	{
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see IFilterable::getFilterDocs()
	 */
	public function getFilterDocs()
	{
		return array();
	}
}