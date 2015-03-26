<?php 

abstract class LiveReportQueryHelper {
	
	/**
	 * Executes a simple report query and returns the result as array of <key, value> according to the specified fields. 
	 * @param KalturaLiveReportType $reportType The type of the report
	 * @param KalturaLiveReportInputFilter $filter The input filter for the report
	 * @param KalturaFilterPager $pager The pager of the report
	 * @param string $keyField The name of the field that will be used as key
	 * @param string $valueField The name of the field that will be used as value
	 */
	public static function retrieveFromReport($reportType,
			KalturaLiveReportInputFilter $filter = null,
			KalturaFilterPager $pager = null,
			$keyField = null,
			$valueField) {
		
		$result = KBatchBase::$kClient->liveReports->getReport($reportType, $filter, $pager);
		if($result->totalCount == 0)
			return array();
		
		$res = array();
		foreach($result->objects as $object) {
			$res = self::setResultValue($object, $keyField, $valueField, $res);
		}
		return $res;
	}

	private static function setResultValue($object, $keyField, $valueField, $res) {
		$resValue = $object->$valueField;
		if (is_array($valueField)) {
			$resValue = 0;
			foreach($valueField as $singleValueField) {
				$resValue += $object->$singleValueField;
			}
		}
		if($keyField) {
			$res[$object->$keyField] = $resValue;
		}
		else {
			$res[] = $resValue;
		}
		return $res;
	}

	/**
	 * Executes a simple events query and returns the result as string according to the specified key.
	 * @param KalturaLiveReportType $reportType The type of the report
	 * @param KalturaLiveReportInputFilter $filter The input filter for the report
	 * @param KalturaFilterPager $pager The pager of the report
	 * @param string $keyField The name of the field that will be used as key
	 */
	public static function getEvents($reportType,
			KalturaLiveReportInputFilter $filter = null,
			KalturaFilterPager $pager = null,
			$keyField) {
		
		$results = KBatchBase::$kClient->liveReports->getEvents($reportType, $filter, $pager);
		foreach($results as $result) {
			if($result->id == $keyField) {
				return $result->data;
			}
		}
		return null;
	}
}