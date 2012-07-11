<?php
if(!isset($data)){
	$data = array();
}
foreach ($flashMessages as $message) {
  $json = $message;
  break;
}
$json['data'] = $data;
echo json_encode($json);
?>
