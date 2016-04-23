<?php

class SQLtoGoogleChartJSON {
   function __construct($server, $connectionInfo) {
      $this->conn = sqlsrv_connect( $server, $connectionInfo);
       
      if( !$this->conn ) {
         echo "Connection could not be established.<br />";
         die( print_r( sqlsrv_errors(), true));
      }
   }
   
   public function getData($sql) {
      $stmt = sqlsrv_query( $this->conn, $sql );
      if( $stmt === false) {
         die( print_r( sqlsrv_errors(), true) );
      }
   
      //build the table array object
      $table = array();
   
      //handle the cols part
      $fieldMetadata = sqlsrv_field_metadata( $stmt );
      $cols = array(); //declare our array to hold out cols array data for google chart
      
      foreach ($fieldMetadata as $field) {
         switch ($field['Type']) {
            case 3:
            case 4:
            case 6:
               $type = 'number';
               break;
            case 1:
            case -8:
            case -9:
            case 12:
               $type = 'string';
               break;
            case -2:
            case 91:
            case 93:
               $type = 'date';
               break;
            default:
               $type = 'string';
               break;               
         }
      
         $column = array('label' => $field['Name'], 'type' => $type);
         array_push($cols,$column);
      } 
      $table['cols'] = $cols;
   
      //handle the rows part
      $rows = array();
      $numCols = count($cols)-1;
      while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
         $tempRow = array();
         for ($i = 0; $i <= $numCols; $i++) {
            $tempColumn = array('v' => $row[$i]);
            array_push($tempRow, $tempColumn);
         }
   
         $rows[] = array('c' => $tempRow);
      }
      $table['rows'] = $rows;
   
      //convert to json
      $jsonTable = json_encode($table);
   
     return $jsonTable;
   }
   
   function __destruct() {
      sqlsrv_close( $this->conn );
   }
}

?>
