<?php
/*
 | ---------------------------------------------------------------
 | PHP FERConexion.PHP
 | ---------------------------------------------------------------
 | @Autor: Kenyi M. Caycho Coyocusi
 | @Fecha de creacion: 07/12/2010
 | @Organizacion: KND S.A.C.
 | ---------------------------------------------------------------
 | Pagina donde se encuentra la conexion a la base de datos y las funciones para las consultas.
*/

class MySQL{
  private $conexion;
  private $total_consultas;
  private $id;
  public function __construct() {
  	if(!isset($this->conexion)){
  	//$this->conexion = (mysql_connect("localhost","root","")) or die(mysql_error());
      $this->conexion = mysqli_connect("localhost","root","","kndpe_sgp");
      //$this->conexion = (mysql_connect("localhost","root","12345678")) or die(mysql_error());
        //mysql_select_db("kndpe_sgp",$this->conexion) or die(mysql_error());
        //mysql_select_db("fermar",$this->conexion) or die(mysql_error());
  	}
    if (!$this->conexion) {
      echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
      echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
      echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
      exit;
    }
        //mysql_query ("SET NAMES 'utf8'");
        //mysql_query("SET lc_time_names = 'es_ES'");
  }
  public function query($consulta) {
    return mysqli_query($this->conexion, $consulta);
  }
  public function consulta($consulta){
    $this->total_consultas++;
    $this->conexion->query("SET sql_mode = ''");
    $this->conexion->query("SET NAMES 'utf8'");
    $resultado = $this->conexion->query($consulta);
    $this->id = $this->conexion->insert_id;
    if(!$resultado){
        echo 'MySQL Error: ' . $this->conexion->error;
        exit;
    }
    return $resultado;
}
  public function fetch_assoc($consulta){ 
  	//return mysql_fetch_assoc($consulta);
    return mysqli_fetch_assoc($consulta);
  }
  public function num_rows($consulta){ 
  	//return mysql_num_rows($consulta);
    return mysqli_num_rows($consulta);
  }
  public function getTotalConsultas(){
  	return $this->total_consultas;
  }
  public function fetch_array($consulta){ 
  	return mysql_fetch_array($consulta);
  }
  public function fetch_num($consulta){
      return mysql_fetch_row($consulta);
  }
  public function getID(){
  	return $this->id;
  }
}?>