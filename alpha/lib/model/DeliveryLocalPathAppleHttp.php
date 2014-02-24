<?php

class DeliveryLocalPathAppleHttp extends DeliveryAppleHttp {
	
	// doGetFlavorAssetUrl - Fully inherit from parent
	
	protected function doGetFileSyncUrl(FileSync $fileSync) {
		$url = parent::doGetFileSyncUrl($fileSync);
		return $url . "/playlist.m3u8";
	}
}

