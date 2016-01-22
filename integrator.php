<?php

//not_paid
$postdata = http_build_query(
    array(
		'order[paymentStatus]' => 'not_paid',
		'by'=>'externalId',
		'site'=>'www-online-krasota-ru'
		)
);




$opts = array('http' => array(
                'proxy' => 'tcp://localhost:41009',
				'request_fulluri'=>true,
				'method'  => 'POST',
				'content' => $postdata,
				'header'  => 'Content-type: application/x-www-form-urlencoded'
				));

$context = stream_context_create($opts); 



$content = file_get_contents("https://online-krasota.retailcrm.ru/api/v3/orders/781/edit?apiKey=DNtmSFCVbZWMQ1GXRJ82cBpRbbtPSA8n",false,$context);
//$content = file_get_contents("https://online-krasota.retailcrm.ru/api/v3/orders?apiKey=DNtmSFCVbZWMQ1GXRJ82cBpRbbtPSA8n",false,$context);


$result = json_decode($content);
echo "<pre>";
print_r($result);
echo "</pre>";


//include "api-client-php-master/lib/ApiClient.php";

//$client = 
