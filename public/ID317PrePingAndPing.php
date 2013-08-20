<?php

	function MakeRequest($curlParams = array())
	{
	  $fieldsString = '';
	  $prePingUrl = '';

	  foreach($curlParams as $key => $value)
	  {
	    if($key === 'url')
	    {
		    $prePingUrl = $value;
	    }

	    $fieldsString .= $key . '=' . $value . '&';
	  }

	  $fieldsString = rtrim($fieldsString, "&");

	  $ch = curl_init($prePingUrl);
	  curl_setopt($ch, CURLOPT_URL, $prePingUrl);
	  curl_setopt($ch, CURLOPT_POST, TRUE);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded; charset=UTF-8"));
	  //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: text/plain"));
	  curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );

	  $result = curl_exec($ch);
	  $info	  = curl_getinfo($ch);

	  return $result;
	}
	
	//$prePingUrl = "https://test1.leadamplms.com/prospect/prospect/preping";   //NOTE: this is not working, always returns FALSE
	//$prePingUrl = "https://prod1.leadamplms.com/prospect/prospect/preping";
	$prePingUrl = 'http://test1.leadamplms.com/prospect/preping';

	// Pre Ping
	$curlParams = array('url' 	    => $prePingUrl,
			    'clientCode'    => urlencode('AMFAM'),
			    'sourceCode'    => urlencode('UGRELNT'),
			    'campaignCode'  => urlencode('AUTO'),
			    'zip' 	    => urlencode('36006'),  // alabama zip code
			    'affiliateCode' => urlencode('underground')
			    );

	var_dump( htmlentities(MakeRequest($curlParams)) );
	exit();


	// Ping

?>
