<?php
/*
 | ---------------------------------------------------------------
 | PHP VAL_Password.PHP
 | ---------------------------------------------------------------
 | @Autor: Kenyi M. Caycho Coyocusi
 | @Fecha de creacion: 07/12/2010
 | @Organizacion: KND S.A.C.
 | ---------------------------------------------------------------
 | Pagina donde se valida si la contraseña es correcta
*/

include_once("FERConexion.php");
$db = new MySQL();
$usuario = $_POST['usuario'];
$password = md5($_POST['password']);
$qry = "SELECT * FROM usuario WHERE usu_ch8_cod='$usuario' AND usu_vc100_pas='$password'";
$res = $db->consulta($qry);
$row = $db->fetch_assoc($res);
$password = "";
if($row['usu_ch8_cod']!=''){
    $password = "1";
}else{
    $password = "0";
}
echo $password;
?>
