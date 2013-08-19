<?php
	//$string = "this is a test";
	$string  = 'abcd';

	function printBackwards($string, $length)
	{
		$test = 1;
		try
		{
			if( $length > -1 )
			{
				echo $string[$length-1] . "<br>";
			
				printBackwards($string, $length -1);
			}
		}
		catch(Exception $e)
		{
			echo 'Caught exception: ',  $e->getMessage(), "\n";
			break;
		}
	}

	@printBackwards($string, 4);
?>
