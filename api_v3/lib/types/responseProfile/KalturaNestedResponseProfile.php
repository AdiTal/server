<?php
/**
 * @package api
 * @subpackage objects
 */
class KalturaNestedResponseProfile extends KalturaNestedResponseProfileBase
{
	/**
	 * Friendly name
	 * 
	 * @var string
	 */
	public $name;
	
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
	
	private static $map_between_objects = array(
		'name', 
		'type',
		'fields',
		'pager',
		'relatedProfiles',
		'mappings',
	);
	
	/* (non-PHPdoc)
	 * @see KalturaObject::validateForUsage($sourceObject, $propertiesToSkip)
	 */
	public function validateForUsage($sourceObject, $propertiesToSkip = array())
	{
		$this->validatePropertyMinLength('name', 2);
		$this->validatePropertyNotNull('type');
		
		parent::validateForUsage($sourceObject, $propertiesToSkip);
	}
	
	/* (non-PHPdoc)
	 * @see KalturaObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}

	/* (non-PHPdoc)
	 * @see KalturaObject::fromObject($srcObj, $responseProfile)
	 */
	public function doFromObject($srcObj, KalturaResponseProfileBase $responseProfile = null)
	{
		/* @var $srcObj kResponseProfile */
		parent::doFromObject($srcObj, $responseProfile);
		
		if($srcObj->getFilter() && $this->shouldGet('filter', $responseProfile))
		{
			$filterApiClassName = $srcObj->getFilterApiClassName();
			$this->filter = new $filterApiClassName();
			$this->filter->fromObject($srcObj->getFilter());
		}
	}
	
	/* (non-PHPdoc)
	 * @see KalturaObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject($object = null, $propertiesToSkip = array())
	{
		if(is_null($object))
		{
			$object = new kResponseProfile();
		}
		
		if($this->filter)
		{
			$object->setFilterApiClassName(get_class($this->filter));
			$object->setFilter($this->filter->toObject());
		}
		
		return parent::toObject($object, $propertiesToSkip);
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
	 * @see KalturaNestedResponseProfileBase::get()
	 */
	public function get()
	{
		return $this;
	}
	
	/* (non-PHPdoc)
	 * @see KalturaResponseProfileBase::getPager()
	 */
	public function getPager()
	{
		return $this->pager;
	}
}