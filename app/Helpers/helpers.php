<?php 

function getVendorName($vendor_ary=array(),$vendor_id='')
{
	$name = '';
	if(!empty($vendor_id)){
		$vendor_id = explode(',', $vendor_id);
		$vendor_name = array();
		foreach ($vendor_id as $key => $value) {
			if(isset($vendor_ary[$value])){
				$vendor_name[] = $vendor_ary[$value];
			}
		}	

		$name = implode(',', $vendor_name);	
	}

	return $name;
}