<?php

class Integrator {
	private $site = 'www-online-krasota-ru';
	function __construct() {

	}
	
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

	public function changeStatus($orderId,$by,$status) {
		$postdata = http_build_query(
			array(
				'order[paymentStatus]' => $status,
				'by' => $by,
				'site' => $this->site
				)
		);
		$content = file_get_contents("https://online-krasota.retailcrm.ru/api/v3/orders/".$orderId."/edit?apiKey=DNtmSFCVbZWMQ1GXRJ82cBpRbbtPSA8n",false,$this->getContext($postdata));
		return json_decode($content);
	}
	
	public function listOrder() {
		//$content = file_get_contents("https://online-krasota.retailcrm.ru/api/v3/orders?apiKey=DNtmSFCVbZWMQ1GXRJ82cBpRbbtPSA8n",false,$context);
	}
}
		//not_paid
$integrator = new Integrator();
echo "<pre>";
print_r($integrator->changeStatus(781,'externalId','not_paid'));
echo "</pre>";

