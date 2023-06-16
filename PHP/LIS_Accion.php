<?php

/*
  |---------------------------------------------------------------
  | PHP LIS_Accion.PHP
  |---------------------------------------------------------------
  | @Autor: Kenyi Caycho Coyocusi
  | @Fecha de creacion: 07/12/2010
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion: 12/10/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------|
  | Pagina donde se realiza el mostrado de los botones de las acciones segun los permisos
 */

include_once 'FERConexion.php';
$db = new MySQL();
$actions = "";

$per = $_POST['per'];
$usu = $_POST['usu'];
$nom = $_POST['nom'];
$type = $_POST['type'];

$qry_acc = "SELECT * FROM accion WHERE per_in11_cod = '$per' AND usu_in11_cod = '$usu' AND acc_vc50_nom = '$nom'";
$res_acc = $db->consulta($qry_acc);
while ($row_acc = $db->fetch_assoc($res_acc)) {
    if ($type == "Grid") {
        if ($row_acc['acc_in1_nue'] == '1') {
            $actions .= "<img src='Images/new.png' id='btnNue' name='btnNue' class='btnMenu enabled' onclick=\"fun_new('$per::$usu::$nom');\" alt='Nuevo' title='Nuevo' /><img src='Images/separator.png' class='separator' alt='|' />";
        } else {
            $actions .= "<img src='Images/new.png' class='btnMenu disabled' alt='Nuevo' /><img src='Images/separator.png' class='separator' alt='|' />";
        }
        if ($row_acc['acc_in1_eli'] == '1') {
            $actions .= "<img src='Images/delete.png' id='btnDel' name='btnDel' class='btnMenu enabled' onclick=\"fun_del3();\" alt='Eliminar' title='Eliminar' /><img src='Images/separator.png' class='separator' alt='|' />&nbsp;";
        } else {
            $actions .= "<img src='Images/delete.png' class='btnMenu disabled' alt='Eliminar' /><img src='Images/separator.png' class='separator' alt='|' />";
        }
        if ($row_acc['acc_in1_adj'] == '1') {
            $actions .= "<img src='Images/atach.png' class='btnMenu enabled' onclick=\"fun_ata();\" alt='Adjuntar' title='Adjuntar archivo' />";
        } else {
            $actions .= "<img src='Images/atach.png' class='btnMenu disabled' alt='Adjuntar'/>";
        }
        if ($row_acc['acc_in1_xls'] == '1') {
            $actions .= "<img src='Images/xls.png' class='btnMenu enabled' onclick=\"fun_xls();\" alt='Exportar a Excel' title='Exportar a excel' />";
        } else {
            $actions .= "<img src='Images/xls.png' class='btnMenu disabled' alt='Exportar a Excel'/>";
        }
        if ($row_acc['acc_in1_pdf'] == '1') {
            $actions .= "<img src='Images/pdf.png' class='btnMenu enabled' onclick=\"fun_pdf();\" alt='Exportar a PDF' title='Exportar a PDF' />";
        } else {
            $actions .= "<img src='Images/pdf.png' class='btnMenu disabled' alt='Exportar a PDF' />";
        }
        if ($row_acc['acc_in1_imp'] == '1') {
            $actions .= "<img src='Images/printer.png' class='btnMenu enabled' onclick=\"fun_pri();\" alt='Imprimir' title='Imprimir' />";
        } else {
            $actions .= "<img src='Images/printer.png' class='btnMenu disabled' alt='Imprimir' />";
        }
        if ($row_acc['acc_in1_cor'] == '1') {
            $actions .= "<img src='Images/email.png' class='btnMenu enabled' onclick=\"fun_cor();\" alt='Correo' title='Correo electrónico' />";
        } else {
            $actions .= "<img src='Images/email.png' class='btnMenu disabled' alt='Correo electrónico' title='Correo electrónico' />";
        }
        if ($row_acc['acc_in1_aud'] == '1') {
            $actions .= "<img src='Images/audit.png' class='btnMenu enabled' onclick=\"fun_aud();\" alt='Mostrar auditoria' title='Mostrar auditoria' /><img src='Images/separator.png' class='separator' alt='|' />";
        } else {
            $actions .= "<img src='Images/audit.png' class='btnMenu disabled' alt='Mostrar auditoria' /><img src='Images/separator.png' class='separator' alt='|' />";
        }
    }
    if ($type == "New") {
        $actions .= "<img src='Images/SaveNew.png' class='btnMenu enabled' onclick='fun_saveandnew();' alt='Guardar y nuevo' title='Guardar y nuevo' /><img src='Images/save.png' class='btnMenu enabled' onclick=\"fun_save('$per::$usu::$nom');\" alt='Guardar' title='Guardar' /><img src='Images/separator.png' class='separator' alt='|' />";
        if ($row_acc['acc_in1_imp'] == '1') {
            $actions .= "<img src='Images/printer.png' class='btnMenu enabled' onclick=\"fun_pri();\" alt='Imprimir' title='Imprimir' />";
        } else {
            $actions .= "<img src='Images/printer.png' class='btnMenu disabled' alt='Imprimir' />";
        }
        if ($row_acc['acc_in1_cor'] == '1') {
            $actions .= "<img src='Images/email.png' class='btnMenu enabled' onclick=\"fun_cor();\" alt='Correo' title='Correo electrónico' /><img src='Images/separator.png' class='separator' alt='|' />";
        } else {
            $actions .= "<img src='Images/email.png' class='btnMenu disabled' alt='Correo electrónico'/><img src='Images/separator.png' class='separator' alt='|' />";
        }
    }
    if ($type == "Update") {
        if ($row_acc['acc_in1_nue'] == '1') {
            $actions .= "<img src='Images/new.png' id='btnNue' name='btnNue' class='btnMenu enabled' onclick=\"fun_new('$per::$usu::$nom');\" alt='Nuevo' title='Nuevo' />";
        } else {
            $actions .= "<img src='Images/new.png' class='btnMenu disabled' alt='Nuevo'/>";
        }
        $actions .= "<img src='Images/SaveNew.png' class='btnMenu disabled'  alt='Guardar y nuevo' /><img src='Images/save.png' class='btnMenu enabled' onclick=\"fun_save('$per::$usu::$nom');\" alt='Guardar' title='Guardar' /><img src='Images/separator.png' class='separator' alt='|' />";
        if ($row_acc['acc_in1_eli'] == '1') {
            $actions .= "<img src='Images/delete.png' class='btnMenu enabled' onclick=\"fun_del2();\" alt='Eliminar' title='Eliminar' />";
        } else {
            $actions .= "<img src='Images/delete.png' id='btnDel' name='btnDel' class='btnMenu disabled' alt='Eliminar' />";
        }
        if ($row_acc['acc_in1_imp'] == '1') {
            $actions .= "<img src='Images/printer.png' class='btnMenu enabled' onclick=\"fun_pri();\" alt='Imprimir' title='Imprimir' />";
        } else {
            $actions .= "<img src='Images/printer.png' class='btnMenu disabled' alt='Imprimir' />";
        }
        if ($row_acc['acc_in1_cor'] == '1') {
            $actions .= "<img src='Images/email.png' class='btnMenu enabled' onclick=\"fun_cor();\" alt='Correo' title='Correo electrónico' />";
        } else {
            $actions .= "<img src='Images/email.png' class='btnMenu disabled' alt='Correo electrónico' />";
        }
    }
    if ($type == "Detail") {
        if ($row_acc['acc_in1_nue'] == '1') {
            $actions .= "<img src='Images/new.png' class='btnMenu enabled' onclick=\"fun_new('$per::$usu::$nom');\" alt='Nuevo' title='Nuevo' /><img src='Images/separator.png' class='separator' alt='|' />";
        } else {
            $actions .= "<img src='Images/new.png' class='btnMenu disabled' alt='Nuevo' /><img src='Images/separator.png' class='separator' alt='|' />";
        }
        if ($row_acc['acc_in1_eli'] == '1') {
            $actions .= "<img src='Images/delete.png' class='btnMenu enabled' onclick=\"fun_del2('$per::$usu::$nom');\" alt='Eliminar' title='Eliminar' /><img src='Images/separator.png' class='separator' alt='|' />";
        } else {
            $actions .= "<img src='Images/delete.png' class='btnMenu disabled' alt='Eliminar' /><img src='Images/separator.png' class='separator' alt='|' />";
        }
        if ($row_acc['acc_in1_imp'] == '1') {
            $actions .= "<img src='Images/printer.png' class='btnMenu enabled' onclick=\"fun_pri();\" alt='Imprimir' title='Imprimir' />";
        } else {
            $actions .= "<img src='Images/printer.png' class='btnMenu disabled' alt='Imprimir' />";
        }
        if ($row_acc['acc_in1_cor'] == '1') {
            $actions .= "<img src='Images/email.png' class='btnMenu enabled' onclick=\"fun_cor();\" alt='Correo' title='Correo electrónico' />";
        } else {
            $actions .= "<img src='Images/email.png' class='btnMenu disabled' alt='Correo electrónico'/>";
        }
        $actions.= "<img src='Images/separator.png' class='separator' alt='|' /><img src='Images/first.png' class='btnMenu enabled' onclick='fun_first();' alt='Primero' title='Ir al primer registro' /><img src='Images/prev.png' class='btnMenu enabled' onclick='fun_prev();' alt='Anterior' title='Ir al registro anterior' /><img src='Images/next.png' class='btnMenu enabled' onclick='fun_next();' alt='Siguiente' title='Ir al siguiente registro' /><img src='Images/last.png' class='btnMenu enabled btnpag' onclick='fun_last();' alt='Último' title='Ir al último registro' />&nbsp;<img src='Images/separator.png' class='separator' alt='|' />&nbsp;";
        $actions .= "Posicion <span id='sp_posini'></span> de <span id='sp_postot'></span> Registro(s)";
    }
    if ($type == "EditDel") {
        if ($row_acc['acc_in1_edi'] == '1') {
            $actions .= "1";
        } else {
            $actions .= "0";
        }
        if ($row_acc['acc_in1_eli'] == '1') {
            $actions .= "::1";
        } else {
            $actions .= "::0";
        }
    }
}
echo $actions;
?>