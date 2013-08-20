<?php

    // include PHPExcel
    require_once('PHPExcel_1.7.9_doc/Classes/PHPExcel.php');
    require_once('PHPExcel_1.7.9_doc/Classes/PHPExcel/IOFactory.php');


    $connection = mysqli_connect("localhost", "root", "yourDatabasePassword", "yourDatabaseName") or die("Error in connectin to the database");


    class ChunkReadFilter implements PHPExcel_Reader_IReadFilter 
    { 
	private $_startRow = 0; 
	private $_endRow = 0; 

	/**  Set the list of rows that we want to read  */ 
	public function setRows($startRow, $chunkSize){ 
	    $this->_startRow = $startRow; 
	    $this->_endRow = $startRow + $chunkSize; 
	} 

	public function readCell($column, $row, $worksheetName = '') { 
	    //  Only read the heading row, and the configured rows 
	    if (($row == 1) ||
		($row >= $this->_startRow && $row < $this->_endRow)) { 
		return true; 
	    } 
	    return false; 
	} 
    }

    $inputFileName   = 'ID-604.xlsx';
    $sheetNames      = array('Sheet 1');
    $columns 	     = array('A' => 'Accepted', 'C' => 'Email');  // columns to be used for the update operation
    $inputFileType   = 'Excel2007';
    $objReader       = PHPExcel_IOFactory::createReader($inputFileType);  //PHPExcel_Reader_Excel2007 implements PHPExcel_Reader_IReader
    $chunkSize       = 2;
    $chunkReadFilter = new ChunkReadFilter(); // ChunkReadFilter
    $objReader->setReadFilter($chunkReadFilter); // PHPExcel_Reader_IReader
    $maxRows         = 8;
    $startRow        = 2;


    foreach($sheetNames as $sheetName)	// looping the worksheets
    {
      for($currentRow = $startRow; $startRow <= $maxRows; $startRow += $chunkSize) // looping the rows in chunks
      {
	  $chunkReadFilter->setRows($startRow, $chunkSize);
	  $objPHPExcel = $objReader->load($inputFileName);  // PHPExcel
	  $objPHPExcel_Worksheet = $objPHPExcel->setActiveSheetIndexByName( $sheetName ); // PHPExcel_Worksheet

	  $currentRow = $startRow;
	  $abort = false;
	  for($ctr = 0; $ctr < $chunkSize; $ctr++) // looking up the n rows retreived, and processing the update or insertion
	  {
	    $columnAndValuePair = '';
	    foreach($columns as $columnName => $columnValue) // build column and value pair
	    {
	      if( strtolower($columnValue) === 'email' )
	      {
		continue;
	      }

	      $columnValueFromExcel = $objPHPExcel_Worksheet->getCell($columnName.$currentRow)->getValue();
	      
	      if( !isset($columnValueFromExcel) )
	      {
		$abort = true;
		break;
	      }

	      $columnAndValuePair .= "`".strtolower($columnValue)."` = '$columnValueFromExcel',";
	    } // build column and value pair

	    if($abort)
	    {
	      break;
	    }

	    $columnAndValuePair = rtrim($columnAndValuePair, ',');
	    $tempArray = $columns;
	    $tempArray = array_map('strtolower', $tempArray);
	    $columnName = array_search('email', $tempArray);	// reusing $columnName
	    $email = $objPHPExcel_Worksheet->getCell($columnName.$currentRow)->getValue();


	    $updateQuery = "update `logging` set $columnAndValuePair where `email` = '$email'";
	    $result = mysqli_query($connection, $updateQuery);
	    echo $updateQuery . '<br>';
	    $currentRow++;
	  } // looking up the n rows retreived, and processing the update or insertion

	  
      } // looping the rows in chunks

      //var_dump($objPHPExcel_Worksheet->getCellCollection(false)); // gets the loaded collection of cells only, depends a lot on the chunksize and startRow configuration
      //var_dump($objPHPExcel_Worksheet->getCell('C4')->getValue());
    } // looping the worksheets

?>