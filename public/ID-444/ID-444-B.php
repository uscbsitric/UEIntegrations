<?php

    $xml = '<?xml version="1.0" encoding="utf-8"?>
	    <lead>
		<!-- lead -->
		<lms_idpartner>10080</lms_idpartner>
		    <lms_mediacode>FWYCA-A-UE-XM-E-00275</lms_mediacode>
		    <lms_gender>male</lms_gender>
		<lms_state>CA</lms_state>
		<lms_campaignid>259</lms_campaignid>
		    <lms_phone>333 567 8901</lms_phone>
		<lms_email>yamil@mail.com</lms_email>
		    <lms_zipcode>10027</lms_zipcode>
		    <lms_firstname>YamilUE</lms_firstname>
		    <lms_lastname>Quinones</lms_lastname>
		    <lms_address>Nieto</lms_address>
		    <lms_aptsuite>123</lms_aptsuite>
		    <lms_contactmecity>New York</lms_contactmecity>
		    <lms_apt>123</lms_apt>
		    <lms_date>2003-12-12</lms_date>
		    <lms_ip>123.123.123.123</lms_ip>
		    <lms_datepartner>2011-12-12</lms_datepartner>
		    <lms_maritalstatus>Married</lms_maritalstatus>
		    <lms_birthdate>2012-12-02</lms_birthdate>
		    <lms_cellphone>987 654 3321</lms_cellphone>
		    <lms_comment>Comments</lms_comment>
		    <lms_ncar>2</lms_ncar>
		    <lms_accidents>2</lms_accidents>
		    <lms_cov>Full coverage</lms_cov>
		    
		<!-- vehicle -->
		<vehicle>
		    <lms_year>2011</lms_year>
		    <lms_make>Acura</lms_make>
		    <lms_model>MDX</lms_model>
		    <lms_details>BASIC</lms_details>
		    <lms_usagetype>C</lms_usagetype>
		    <lms_milesoneway>5</lms_milesoneway>
		    <lms_mileage>7500</lms_mileage>
		    <lms_comprehensivecoverage>1000</lms_comprehensivecoverage>
		    <lms_collisioncoverage>250</lms_collisioncoverage>
		</vehicle>

		<vehicle>
		    <lms_year>2011</lms_year>
		    <lms_make>Acura</lms_make>
		    <lms_model>MDX</lms_model>
		    <lms_details>BASIC</lms_details>
		    <lms_usagetype>C</lms_usagetype>
		    <lms_milesoneway>5</lms_milesoneway>
		    <lms_mileage>7500</lms_mileage>
		    <lms_comprehensivecoverage>1000</lms_comprehensivecoverage>
		    <lms_collisioncoverage>250</lms_collisioncoverage>
		</vehicle>
		    
		<!-- driver -->
		<driver>
		    <lms_relapplicant>Insured</lms_relapplicant>
		    <lms_firstname>TEST ANDREA XML</lms_firstname>
		    <lms_lastname>TEST</lms_lastname>
		    <lms_birthdate>2010-12-12</lms_birthdate>
		    <lms_maritalstatus>Married</lms_maritalstatus>
		    <lms_gender>Male</lms_gender>
		    <lms_employement>Employed</lms_employement>
		    <lms_statelicensed>CA</lms_statelicensed>
		    <lms_drivingexpyears>5</lms_drivingexpyears>
		    <lms_drivingexpmonths>25</lms_drivingexpmonths>
		    <lms_milesoneway>5</lms_milesoneway>
		    <lms_mileage>7500</lms_mileage>
		</driver>

		    
		<coverage>
		    <lms_bodilyinjuryliability>15/30</lms_bodilyinjuryliability>
		    <lms_propertydamageliability>5</lms_propertydamageliability>
		    <lms_uninsuredmotoristbodilyinjury>15/30</lms_uninsuredmotoristbodilyinjury>
		    <lms_uninsuredmotoristpropertydamage>3</lms_uninsuredmotoristpropertydamage>
		    <lms_medicalpayments>1</lms_medicalpayments>
		    <lms_rentalreimbursement>3</lms_rentalreimbursement>
		    <lms_towing>3</lms_towing>
			    
		</coverage>
	    </lead>
	';

    $tmpFname = tempnam("/tmp", "FOO");
    $handle = fopen($tmpFname, "w");
    fwrite($handle, $xml);
    fclose($handle);

    $url = 'http://www.freewaylms.com/controller_dev/ProcessXML.php';
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array('filexml' => '@'.$tmpFname));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );

    $result = curl_exec($ch);
    $info = curl_getinfo($ch);
    
    echo "<pre>";
    var_dump($result);

    unlink($tmpFname);
    
?>