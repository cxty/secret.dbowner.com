<?php
class testMod extends commonMod {
	public function test(){
		$tArr['UserID']      = $fieldArr['UserID'];
		$tArr['serialCode']  = $fieldArr['dSerialCode'];
		$tArr['coin']        = $fieldArr['CoinDB'];
		$tArr['type']        = $fieldArr['dType'];
		$tArr['AppendTime']  = $fieldArr['AppendTime'];
		
		foreach($tArr as $key=>$val){
			$ntArr[strtolower($key)] = $val;
		}
		ksort($ntArr);
			
		$_re = DBRandomHash::encryptRandomString($tArr['serialCode'],implode('|', $ntArr));
	}
	public function curl(){
		$url = $this->config['PLATFORM']['Secret'];
		$url .= '/authcode/getCoinRandomCode';
		
		$tArr['UserID']      = 1;
		$tArr['serialCode']  = '20130515154425767690000052724998';
		$tArr['coin']        = 670;
		$tArr['type']        = 3;
		$tArr['AppendTime']  = '1368603865';
		
		$url .= '?'.http_build_query($tArr);
		
		echo $url;exit;
		
		DBCurl::dbGet();
	}
	public function dcurl(){
		$url = $this->config['PLATFORM']['Secret'];
		$url .= '/authcode/decryptCoinRandom';
	
		$tArr['code']        = 'QWUvR3VxZ1JTQnk3dzJEaDlqVXIrK245S3JvSmhBdnhDK3pML2N4T3lHaUFKU3d1M3VuV3diRlcrR0NNRmR6OWZDd283M2hOOWlBPQ%3D%3D';
		$tArr['UserID']      = 1;
		$tArr['serialCode']  = '20130515154425767690000052724998';
		$tArr['coin']        = 670;
		$tArr['type']        = 3;
		$tArr['AppendTime']  = '1368603865';
	
		$url .= '?'.http_build_query($tArr);
	
		echo $url;exit;
	
		DBCurl::dbGet();
	}
}