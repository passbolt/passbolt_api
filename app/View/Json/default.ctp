<?php
if(!isset($data)){
	$data = array();
}
foreach ($flashMessages as $message) {
  $json = $message;
  break;
}
$json['content'] = $data;
echo json_encode($json);
?>
