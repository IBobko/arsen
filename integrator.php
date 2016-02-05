<?php
set_time_limit(300);

class MoySklad {
	private $username = "sklad@online_krasota";
	private $password = "1march1985";	

	function __construct() {

	}

	public function customerOrder() {
		$params = array('http' => array('proxy' => 'tcp://localhost:41009','header'  => "Authorization: Basic " . base64_encode("{$this->username}:{$this->password}")));
		$context = stream_context_create($params);
		$data = file_get_contents("https://online.moysklad.ru/api/remap/1.0/entity/customerOrder", false, $context);
		return json_decode($data);
	}

	public function getOrdersSize() {
		$params = array('http' => array('proxy' => 'tcp://localhost:41009','header'  => "Authorization: Basic " . base64_encode("{$this->username}:{$this->password}")));
		$context = stream_context_create($params);		
		$result = json_decode(file_get_contents("https://online.moysklad.ru/api/remap/1.0/entity/customerOrder?offset=100000",false,$context));		
		return $result->metaTO->size;
	}

	public function getLastHundred($size) {
		$params = array('http' => array('proxy' => 'tcp://localhost:41009','header'  => "Authorization: Basic " . base64_encode("{$this->username}:{$this->password}")));
		$context = stream_context_create($params);		
		$result = json_decode(file_get_contents("https://online.moysklad.ru/api/remap/1.0/entity/customerOrder?offset=".($size-50)."&limit=50",false,$context));	
		return $result;
	}
}


class Integrator {
	function __construct() {}
	
	private function getContext($postdata) {
		$opts = array('http' => array(
				'proxy' => 'tcp://localhost:41009',
				'request_fulluri' => true,
				'method'  => 'POST',
				'content' =>  $postdata,
				'header' => 'Content-type: application/x-www-form-urlencoded'
				));
		return stream_context_create($opts);
	}

	public function changeStatus($orderId,$status,$date,$by,$site) {
		$postdata = http_build_query(
			array(
				'order' => json_encode(array('paymentStatus' => $status,'customFields'=>array('date_cash'=>$date))),
				'by' => $by,
				'site' => $site
				)
		);
		$content = file_get_contents("https://online-krasota.retailcrm.ru/api/v3/orders/".$orderId."/edit?apiKey=DNtmSFCVbZWMQ1GXRJ82cBpRbbtPSA8n",false,$this->getContext($postdata));
		return json_decode($content);
	}
	
	public function listOrder($orderid) {
		$opts = array('http' => array(
				'proxy' => 'tcp://localhost:41009',
				'request_fulluri' => true,
				));
		$context = stream_context_create($opts);
		$content = file_get_contents("https://online-krasota.retailcrm.ru/api/v3/orders?apiKey=DNtmSFCVbZWMQ1GXRJ82cBpRbbtPSA8n&filter[number]=$orderid",false,$context);
		return json_decode($content);
	}

	public function start() {
		$sklad = new MoySklad();		
		$orders = $sklad->getUpdatingOrders();
		foreach($orders as $order) {
			$this->changeStatus($order,'externalId','paid');
			print_r($order);
		}
	}

	public function t() {
		$opts = array('http' => array(
				'proxy' => 'tcp://localhost:41009',
				'request_fulluri' => true,
				));
		$context = stream_context_create($opts);		
		$content = file_get_contents("https://online-krasota.retailcrm.ru/api/v3/orders?apiKey=DNtmSFCVbZWMQ1GXRJ82cBpRbbtPSA8n&page=2",false,$context);
		print_r(json_decode($content));
	}
}

$integrator = new Integrator();

$moySklad = new MoySklad();
$size = $moySklad->getOrdersSize();

$orders = $moySklad->getLastHundred($size);

echo "<pre>";
//$integrator->t();
//$integrator->changeStatus(27899,"not-paid","id","npcriz-sklad-ru");
echo "</pre>";
//exit();

echo "<pre>";
foreach($orders->rows as $value) {
	
//	print_r($integrator->listOrder($value->name)->orders);

	$crmOrder = $integrator->listOrder($value->name)->orders[0];
	//print_r($crmOrder);
//	continue;
	if ($value->applicable == 1) {
		if ($value->payedSum == 0 && $crmOrder->paymentStatus != "not-paid") {
			echo "Для заказа " . $crmOrder->id . " необходимо обновить статус. Сейчас у него {$crmOrder->paymentStatus}</br>";
			echo "Обновляю<br/>";
			print_r($integrator->changeStatus($crmOrder->id,"not-paid",0,"id",$crmOrder->site));
			echo "Обновил";			
		}
	
		if ($value->payedSum !=0 && $crmOrder->paymentStatus != "paid") {
			echo "Для заказа " . $crmOrder->id . " необходимо обновить статус. Сейчас у него {$crmOrder->paymentStatus}</br>";
			echo "Обновляю<br/>";
			print_r($integrator->changeStatus($crmOrder->id,"paid","id",$value->updated,$crmOrder->site));
			echo "Обновил";
		}
	}
	
	echo "<hr/>";
}
echo "</pre>";

