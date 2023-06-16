<?php
/*
 |---------------------------------------------------------------
 | PHP message.php
 |---------------------------------------------------------------
 | @Autor: Kenyi M. Caycho Coyocusi
 | @Fecha de creacion: 07/12/2010
 | @Organizacion: KND S.A.C.
 |---------------------------------------------------------------
 | Pagina para el mostrado de los mensajes
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <table>
            <tr>
                <td height="20" valign="top"><font color="#1A3B82"><b><label><?php echo $_POST['title']; ?></label></b></font></td>
            </tr>
            <tr>
                <td height="50"  valign="top">
                    <span  style="margin-left: 5px;"><?php echo '<img src="Images/'.$_POST['type'].'.gif" />'; ?></span>
                    <span class="fixtext"><label><?php echo $_POST['message']; ?></label></span>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <?php if($_POST['funaceptar']!=''){
                    echo '<input id="btnAcept" type="button" value="Aceptar" onclick="'.$_POST['funaceptar'].'('.stripslashes($_POST['aceptar']).');" style=" padding-left: 10px; padding-bottom:4px; padding-right: 10px;" />';
                        }
                    if($_POST['cancelar']!=''){
                    echo '<input type="button" onclick="'.$_POST['cancelar'].'" value="Cancelar" style="padding-left: 10px; padding-bottom:4px; padding-right: 10px;" />';
                    } ?>
                </td>
            </tr>
        </table>
        <script type="text/javascript">
         $("#btnAcept").focus();
        </script>
    </body>
</html>
