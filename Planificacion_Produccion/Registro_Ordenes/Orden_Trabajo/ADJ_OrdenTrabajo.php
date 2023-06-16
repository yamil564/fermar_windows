<?php
/*
  |-------------------------------------------------------------------------------------
  | PHP ADJ_OrdenTrabajo.php
  |-------------------------------------------------------------------------------------
  | @Autor: Jean Guzman Abregu
  | @Fecha de creación: 15/08/2012
  | @Organizacion: KND S.A.C.
  | @Modificado por: Jean Guzman Abregu
  | @Ultima fecha de modificacion: 15/08/2012
  | @Organizacion: KND S.A.C.
  |--------------------------------------------------------------------------------------
  | Pagina donde el frm para adjuntar OT
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="../Styles/estilos_generales.css">
    </head>
    <body>
        <?php date_default_timezone_set('America/New_York');
        ?>
        <form name="frmOrdenTrabajoExcel" id='frmOrdenTrabajoExcel'  action="PHP/upload_resizable.php?adjuntar_excelOT=1" method="post" enctype="multipart/form-data" target='upload_target'>
            <table border="0" style="width: 100%;">
                <tr>
                    <td style="width: 30%;"></td>
                    <td style="width: 70%;"></td>
                </tr>
                <tr>
                    <td style="width: 25%;">Seleccionar:</td>
                    <td>
                        <div>
                            <input type='file' id='myfileExcel' name='myfileExcel' onchange="Adj_ExcelOrdenTrabajo()" style="cursor: pointer;background-color: transparent; z-index: 1001;" />
                        </div>
                    </td>
                </tr>
                <tr> 
                    <td>Registrar:</td>
                    <td>
                        <img style='cursor: pointer;  width: 25px;' onclick="fun_transOT()"  title='Transferir archivo OT' alt='Transferir' id='btn_transfer'  src='Images/save.png'></img>
                    </td>
                </tr>
                <tr>
                    <td>Formato Demo:</td>
                    <td>
                        <img style='cursor: pointer;  width: 25px;' onclick="fun_dowFormatoOT()" title='Descargar formato OT' alt='descargar' src='Images/export_excel.png'></img>
                    </td>
                </tr>
            </table>
            <input type='text' id='txtCodUsu' name='txtCodUsu' style='display: none'/>
            <iframe id="upload_target" name="upload_target" style="width: 150px; height: 150px; border: 1px solid; display: none;">
            </iframe>
        </form>
    </body>
</html>

<script>
    var cod_usu = $("#sp-codus").html();
    //Llamo la id del abjuntar 
    $(document).ready(function(){
        $('#txtCodUsu').val(cod_usu);
    });
</script>