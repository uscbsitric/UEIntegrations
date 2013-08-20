<?php

  //$prePingUrl = "http://www.freewaylms.com/controller_dev/process-xml.php";
  $url = 'http://www.freewaylms.com/controller_dev/ProcessXML.php';
  $filePath = '@'.'/var/www/testArea/UnderGroundElephant/ID-444/sampleXMLFormat.xml';

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
  curl_setopt($ch, CURLOPT_POSTFIELDS, array('filexml' => $filePath));
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );

  $result = curl_exec($ch);
  $info	= curl_getinfo($ch);

  echo "<pre>";
  var_dump($result);

?>