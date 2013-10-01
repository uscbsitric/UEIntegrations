<?php
$value = array('monster1' => array('iWantIt' => 1, 'iGotIt' => 1),
			   'monster2' => array('iWantIt' => 0, 'iGotIt' => 1)
			  );
$value = json_encode($value);


setcookie("TestCookie", $value);


echo html
