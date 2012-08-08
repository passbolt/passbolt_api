<?php
if(!isset($data)){
	$data = array();
}
foreach ($flashMessages as $message) {
  $json = $message;
  break;
}
echo json_encode($json);
?>
