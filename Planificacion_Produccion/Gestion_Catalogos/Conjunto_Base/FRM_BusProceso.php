<?php
/*
|---------------------------------------------------------------
| PHP FRM_BusProceso.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 14/12/2010
| @Fecha de modificacion: 28/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Página en donde se encuentra el formulario el proceso para el Conjunto Base
*/
?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Registro de Busqueda de Proceso</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
    <?php
    include_once '../../../PHP/FERConexion.php';
    include_once '../../../Store_Procedure/SP_ProcedureAll.php';
    include_once 'Store_Procedure/SP_ConjuntoBase.php';
    $db= new MySQL();
    $SP_conjunto = new Procedure_ConjuntoBase();
    $resp_proc =$SP_conjunto->SP_lista_procesos();
   if(isset ($_POST['codtemp2'])){
       $codtempro = $_POST['codtemp2'];
       $codarr = $_POST['codarr2'];
   }else{
       $codtempro = '';
       $codarr = '';
   }
    ?>
<!--Formulario para ingresar datos de las Partes del Conjunto Base-->
    <form action="" id="busProceso">
        <div>
            <ul>
                <li>
                    <?php
                        echo '<span id ="sp_codarr2" style="display: none">'.$codtempro.'</span>';
                        echo '<input type="text" name="txt_proc_tem" style="display: none" id="txt_proc_tem" value="'.$codarr.'" />';
                    ?>
                </li>
                <li>
                    <?php
                        echo '<label for="txt_proc_desc">Descripci&oacute;n de Proceso:</label>';
                        echo '&nbsp;<select id="txt_proc_desc" name="txt_proc_desc" style="width: 200px" >';
                        echo $resp_proc;
                        echo '</select>'
                    ?>
                </li>
            </ul>
        </div>
    </form>
<script type="text/javascript" >
/* Funcion para cargar el documento BUSProceso */
$(document).ready(function(){
    var arr = $("#txt_proc_tem").val();
        if(arr!=''){
            $("#txt_proc_desc").val(arr);
        }else{
            var codproc = $("#txt_proc_desc").val();
            $("#txt_proc_tem").val(codproc);
        }
    /* Funcion para cambiar el codigo dependiendo de la descripcion del proceso */
    $("#txt_proc_desc").change(function(){
        var codproc = $("#txt_proc_desc").val();
        $("#txt_proc_tem").val(codproc);
   });
});
    </script>
</body> 
</html>