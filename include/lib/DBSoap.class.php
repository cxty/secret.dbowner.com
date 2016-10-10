<?php
/**
 * SOAP调用例子
 *
 * @author wbqing405@sina.com
 */
class DBSoap{

	public function __construct(){
		$this->SOAP_USER   = $GLOBALS['config']['DES']['SOAP_USER'];
		$this->DES_PWD     = $GLOBALS['config']['DES']['SOAP_PWD'];
		$this->DES_IV      = $GLOBALS['config']['DES']['SOAP_IV'];
		$this->Soap_Client = $GLOBALS['config']['DES']['User_Client'];
		$this->Soap_Header = $GLOBALS['config']['DES']['User_Header'];
	}
	/**
	 * 载入加密文件
	 */
	private function _load_des(){
		if($GLOBALS['DES_DBSoap']){
			return $GLOBALS['DES_DBSoap'];
		}else{
			require_once dirname(__FILE__).'/DES.class.php';
			$GLOBALS['DES_DBSoap'] = new DES($this->DES_PWD,$this->DES_IV);
				
			return $GLOBALS['DES_DBSoap'];
		}
	}
	/**
	 * 头部验证文件
	 */
	private function _soap($platform){
		switch($platform){
			case 'Auth':
				$this->Soap_Client = $GLOBALS['config']['DES']['Soap_Client_Auth'];
				$this->Soap_Header = $GLOBALS['config']['DES']['Soap_Header_Auth'];
				break;
			case 'Pay':
				$this->Soap_Client = $GLOBALS['config']['DES']['Soap_Client_Pay'];
				$this->Soap_Header = $GLOBALS['config']['DES']['Soap_Header_pay'];
				break;
			default:
				$this->Soap_Client = $GLOBALS['config']['DES']['Soap_Client_tPay'];
				$this->Soap_Header = $GLOBALS['config']['DES']['Soap_Header_tpay'];
				break;
		}
	}
	/**
	 * 处理SoapClient方法
	 * @param  $className 接口方法名
	 * @param  $conArr 数据数组
	 */
	private function manSoap($className,$conArr){	
		try{	
			$des = $this->_load_des();
			$client = new SoapClient($this->Soap_Client,array("trace"=>false, 'compression'=>true));
			$soap_var = new SoapVar(array('user'=>$this->SOAP_USER), SOAP_ENC_OBJECT,'Auth');
			$header = new SoapHeader($this->Soap_Header, 'Auth', $soap_var, true);
			$client->__setSoapHeaders($header);
			
			$val = $des->encrypt(json_encode($conArr));
			
			$tVal = array('data'=>json_encode(array('data'=>$val)));	
			
			//$re = $client->$className($tVal)->return;	
			//$_re =  json_decode($re);
			//$this->pr(array('data'=>json_encode(array('data'=>$val))));exit;
			
			$_re =  json_decode($client->$className(array('data'=>json_encode(array('data'=>$val))))->return);

			if(isset($_re->data) && $_re->data){
				return json_decode($des->decrypt($_re->data),true);
			}else{
				return false;
			}
		}catch (Exception $e) {
			
			//echo $client->__getLastRequest();
			//echo $client->__getLastResponse();
			echo $e->getMessage();
			
			exit;
				
			
			printf("Message = %s",$e->__toString());
		}
	}
	
	/**
	 * 取表信息
	 */
	public function setTableInfo($platform, $tableName, $condition=null){
		$this->_soap($platform);
		
		$conArr = $condition;
	
		return $this->manSoap($tableName,$conArr);
	}
	
	/**
	 * 取表信息
	 */
	public function getTableInfo($platform, $tableName, $condition=null, $order=null, $AppInfoID=null){
		$this->_soap($platform);
		
		$conArr = $condition;

		if($order){
			$conArr['order'] = $order;
		}else{
			$conArr['order'] = '';
		}
		if($AppInfoID){
			$conArr['AppInfoID'] = $AppInfoID;
		}

		return $this->manSoap($tableName,$conArr);
	}
	
	/**
	 * 取表信息
	 */
	public function SelectTableInfo($platform, $tableName, $condition=null, $order=null){
		$this->_soap($platform);
		
		$conArr = $condition;
		
		if($order){
			$conArr['order'] = $order;
		}else{
			$conArr['order'] = '';
		}

		return $this->manSoap($tableName,$conArr);
	}
	
	/**
	 * 更新表消息
	 */
	public function UpdateTableInfo($platform, $tableName, $udata){
		$this->_soap($platform);
		
		$conArr = $udata;

		return $this->manSoap($tableName,$conArr);
	}
	/**
	 * 删除用户信息
	 */
	public function DeleteTableInfo($platform, $tableName, $condition=null){
		$this->_soap($platform);
		
		$conArr = $condition;
		
		return $this->manSoap($tableName,$conArr);
	}
	/**
	 * 取用户列表
	 */
	public function GetTableList($platform, $tableName, $page=1, $rowNum=10, $condition=null, $order=null){
		$this->_soap($platform);
		
		$conArr['page']      = $page;
		$conArr['pagesize']  = $rowNum;
		
		if($condition){
			$conArr['condition'] = $condition;
		}else{
			$conArr['condition'] = '';
		}
		if($order){
			$conArr['order'] = $order;
		}else{
			$conArr['order'] = '';
		}

		return $this->manSoap($tableName,$conArr);
	}
	/**
	 * 增加用户基础信息
	 */
	public function InsertTableInfo($platform, $tableName, $data){
		$this->_soap($platform);
		
		$conArr = $data;

		return $this->manSoap($tableName,$conArr);
	}
	private function pr($arr=null){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}
?>