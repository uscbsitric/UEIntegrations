<?php

    $connection = mysql_pconnect('', '', '');

    if( mysql_errno($connection) )
    {
		echo 'Failed to connect to MySQL: ' . mysql_connect_error();
		exit();
    }
    
    mysql_select_db('undergroundelephant', $connection);

   
    
    class Logger
    {
		private $tableName;
	
		public function __construct($options = array())
		{
		  $this->tableName = $options['tableName'];
		}
		
		public function log($options = array())
	    {
		    $columnNames  = '';
		    $columnValues = '';
	
		    foreach($options as $columnName => $columnValue)
		    {
				$columnNames  .= '`'.$columnName.'`'  . ', ';
				$columnValues .= '"'.$columnValue.'"' . ', ';
		    }
	
		    $columnNames  = rtrim(trim($columnNames), ',');
		    $columnValues = rtrim(trim($columnValues), ',');
	
		    $insertQuery = 'INSERT INTO `'.$this->tableName.'` ('.$columnNames.') VALUES('.$columnValues.')';
	
		    if( !mysql_query($insertQuery, $GLOBALS['connection']) )
		    {
				return 'Error: ' . mysql_error($GLOBALS['connection']);
		    }
	
		}

    }


    $logger = new Logger( array('tableName' => 'ainsi515') );
    $result = $logger->log( array('vertical'  => 'vertical string value',
								  'leadid'    => '4D0BD454-6B89-E940-EE4C-573CABF0D046',
								  'email'     => 'frederick.sandalo@ripeconcepts.com',
								  'clientid'  => 'client-ID-1234',
								  'sale_date' => date('Y-m-d'),
								  'capture_time' => date('Y-m-d'),
								  'accepted'  => 1
								 )
						  );

?>
