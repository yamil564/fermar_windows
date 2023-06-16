<?php
/*
  |-------------------------------------------------------------------------
  | PHP FRM_Cambiar_Clave.php
  |-------------------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 03/10/2011
  | @Fecha de modificacion: 03/10/2011
  | @Modificado por: Frank Peña Ponce
  | @Organizacion: KND S.A.C.
  |-------------------------------------------------------------------------
  | Se encarga de el modificado y validacion de seguridad de la contraseña.
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <div id="tabsp">
            <span id="sp_accion" style="display: none;"><?php echo $per . '::' . $usu . '::' . $nomform; ?></span>
            <div id="ul">
                <?php //echo '<ul class="tabs">' . $SP_ProcedureAll->Vista($per, $usu, $nomform) . '</ul>'; ?>
            </div>
            <div id="tab_container" class="tab_containerShow">               
                <div id="herramienta">
                    <img src="Images/key.png" style="width: 22px; height: 22px; padding: 3px 4px 3px 4px;" id="btn_guardar" class="btn_action disabled" alt="password" title="Cambiar contraseña" />
                </div>
                <div id="pagina1">
                    <div id="div_pagina1" style="padding: 0.4em 0.2em 0.2em 0.3em"><b>SGP Fermar</b></div>
                </div>
                <div id="tabs-2" class="tab_content">
                    <table border="0" width="100%">
                        <tr>
                            <td style="width: 100px;"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td><label style="width: 172px;">Ingrese contraseña anterior:</label></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="password" id="txt_anterior" class="data-entry" style="width:200px;" />                                
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><label style="width: 172px;">Ingrese nueva contraseña:</label></td><td>&nbsp;<label style="width: 172px;">Confirme nueva contraseña:</label></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>                            
                            <td valign="top"><input type="password" class="data-entry" id="txt_password" style="width:200px;" />
                                &nbsp;<br />&nbsp;
                                <label style="width: 172px;">Fortaleza de la contraseña:</label>
                                <span style="color: white" id='result'>
                                    <div>
                                        <img src="Images/silver.png" alt="Fondo" style="width: 200px; height: 22px;" />
                                        <div style="position: relative; top: -18px; z-index: 1000; left : 20px;">
                                            <label style="width: 172px;">Ingrese nueva contraseña</label>
                                        </div>
                                    </div>
                                </span>
                            </td>
                            <td valign="top">&nbsp;<input type="password" id="txt_confirmar" class="data-entry" style="width:200px;" />
                                <br />
                                <label>&nbsp;</label>
                                <span style="color: white" id='result_confirm'>
                                    <div>
                                        &nbsp;<img src="Images/silver.png" alt="Fondo" style="width: 200px; height: 22px; margin-top: 14px;" />
                                        <div style="position: relative; top: -18px; z-index: 1000; left : 20px;">
                                            <label style="width: 172px;">Confirme nueva contraseña</label>
                                        </div>
                                    </div>
                                </span>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <br />
                                <div class="comment-content" style="position: relative; margin-left: 210px; margin-top: 173px;   margin-top: 39px;">
                                    <br />
                                    <ul>
                                        <li><?php echo ('●') ?> Si es la primera vez que usa el sistema SGP Fermar, le aconsejamos que cambie su contraseña que le asignó el administrador.</li>
                                        <br />
                                        <li><?php echo ('●') ?> Actualice su contraseña cada cierto tiempo, para evitar futuros riesgos de seguridad.</li>
                                        <br />
                                        <li><?php echo ('●') ?> Nunca revele su contraseña.</li>
                                        <br />
                                    </ul>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div id="password_real"></div>
        <div id="password_anterior"></div>
        <script type="text/javascript" src="Script/enter_press.js"></script>
        <?php echo '<script type="text/javascript" src="Script/Seguridad/Cambiar_Clave.js' . '?' . filemtime('../../Script/Seguridad/Cambiar_Clave.js') . '"></script>'; ?>
    </body>
</html>