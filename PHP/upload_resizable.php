<?php

/*
  |---------------------------------------------------------------
  | PHP upload_resizable.php
  |---------------------------------------------------------------
  | @Autor: Jean Guzman Abregu
  | @Fecha de creacion: 15/08/2012
  | @Modificado por:Jean Guzman Abregu
  | @Ultima fecha de modificacion: 15/08/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina que se encarga de redimensionar la imagen y subirla.
 */

include 'FERConexion.php';
/* Subir Archivos Excel-SE DEBE DAR PERMISOS 777 */
if (isset($_GET['adjuntar_excelOT'])) {
    $excel_archivo = $_FILES['myfileExcel']['name'];
    $codUsu = $_POST['txtCodUsu'];
    if ($excel_archivo != '' && $excel_archivo != null) {
        $excel_carpeta = "../Reportes/Orden_trabajo/";
        $documento = 'formatoOT_' . $codUsu . '.xls';
        $archivo = $excel_carpeta . $documento;
        $temp = $_FILES['myfileExcel']['tmp_name'];
        move_uploaded_file($temp, $archivo);
        sleep(1);
        //echo "<script language='javascript' type='text/javascript'>window.top.window.stopUpload();</script>";
    }
}
?>
