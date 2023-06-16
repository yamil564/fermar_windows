<?php

/*
  |---------------------------------------------------------------
  | PHP TAB_Permisos.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 24/10/2011
  | @Modificado por: Frank Peña Ponce
  | @Fecha de ultima modificación: 24/10/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina que se encarga de formar la estructura del jqGrid en
  | formato XML con las condiciones enviadas por medio de parametros del grid de PERMISOS.
 */

include_once("../../../PHP/FERConexion.php");
$db = new MySQL();

$codusu = $_REQUEST['usu'];
$filter = (strip_tags(trim($_REQUEST['filter'])));

if (isset($_REQUEST["nodeid"])) {
    $node = 1;
    $cod = $_REQUEST["nodeid"];
    $n_lvl = (integer) $_REQUEST["n_level"];
} else {
    $node = 0;
    $n_lvl = 0;
}

header("Content-type: text/xml;charset=utf-8");

echo "<?xml version='1.0' encoding='utf-8'?>";
echo "<rows>";
echo "<page>1</page>";
echo "<total>1</total>";
echo "<records>1</records>";

if ($node != 0) {
    $n_lvl = $n_lvl + 1;
}

if ($n_lvl == 0) {//Nivel de usuario
    $ResUsu = $db->consulta("SELECT DISTINCT * FROM usuario u WHERE u.usu_in1_est='1' AND CONCAT(u.usu_vc80_ape,' ',u.usu_vc80_nom)
                             LIKE '%$filter%' AND u.usu_in11_cod!='0' GROUP BY u.usu_in11_cod ORDER BY u.usu_vc80_ape");
    while ($RowUsu = $db->fetch_assoc($ResUsu)) {
        echo "<row>";
        echo "<cell>" . $RowUsu['usu_in11_cod'] . "</cell>";
        echo "<cell><![CDATA[" . $RowUsu['usu_vc80_ape'] . " " . $RowUsu['usu_vc80_nom'] . "]]>::people.gif</cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell>" . $n_lvl . "</cell>";
        echo "<cell>NULL</cell>";
        echo "<cell>false</cell>";
        echo "<cell>false</cell>";
        echo "</row>";
    }
}

if ($n_lvl == 1) {//Nivel de modulo
    $ResMod = $db->consulta("SELECT * FROM permiso WHERE per_in1_est !=0");
    while ($RowMod = $db->fetch_assoc($ResMod)) {
        echo "<row>";
        echo "<cell>" . $RowMod['per_in11_cod'] . "-$cod</cell>";
        if ($RowMod['per_vc40_desc'] == 'Planificacion de Produccion') {
            echo "<cell>" . $RowMod['per_vc40_desc'] . "::2.png</cell>";
        } else if ($RowMod['per_vc40_desc'] == 'Control de Avance de Produccion') {
            echo "<cell>" . $RowMod['per_vc40_desc'] . "::3.png</cell>";
        } else if ($RowMod['per_vc40_desc'] == 'Control de Calidad de Produccion') {
            echo "<cell>" . $RowMod['per_vc40_desc'] . "::4.png</cell>";
        } else if ($RowMod['per_vc40_desc'] == 'Seguridad') {
            echo "<cell>" . $RowMod['per_vc40_desc'] . "::5.png</cell>";
        }

        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell>" . $n_lvl . "</cell>";
        echo "<cell>" . $cod . "</cell>";
        echo "<cell>false</cell>";
        echo "<cell>false</cell>";
        echo "</row>";
    }
}

if ($n_lvl == 2) {//Nivel de carpetas por modulo
    $cod_usu = explode("-", $cod);
    $ResAcc = $db->consulta("SELECT  DISTINCT(acc_vc40_tip) AS tipo FROM accion WHERE per_in11_cod = '$cod_usu[0]'");
    $i = 0;
    while ($RowAcc = $db->fetch_assoc($ResAcc)) {
        $i++;
        echo "<row>";
        echo "<cell>" . $cod . "-" . str_replace(' ', '', $RowAcc['tipo']) . "-" . $i . "</cell>";
        echo "<cell>" . $RowAcc['tipo'] . "::a.png</cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell></cell>";
        echo "<cell>" . $n_lvl . "</cell>";
        echo "<cell>" . $cod . "</cell>";
        echo "<cell>false</cell>";
        echo "<cell>false</cell>";
        echo "</row>";
    }
}

if ($n_lvl == 3) {//Nivel por formulario
    $cod_usu = explode("-", $cod);
    $ResAcc = $db->consulta("SELECT * FROM accion WHERE usu_in11_cod = '$cod_usu[1]' AND replace(`acc_vc40_tip`, ' ', '') = '$cod_usu[2]' AND per_in11_cod = '$cod_usu[0]'");
    echo "SELECT * FROM accion WHERE usu_in11_cod = '$cod_usu[1]' AND replace(`acc_vc50_nom`, ' ', '') = '$cod_usu[2]' AND acc_vc40_tip = '$cod_usu[2]'";
    $i = 0;
    while ($RowAcc = $db->fetch_assoc($ResAcc)) {
        $i++;
        echo "<row>";
        echo "<cell>" . $cod . "-" . $RowAcc['per_in11_cod'] . "-" . $i . "</cell>";
        echo "<cell>" . $RowAcc['acc_vc50_nom'] . "::" . $RowAcc['acc_vc40_tip'] . ".png</cell>";
        echo "<cell>" . $RowAcc['acc_in1_nue'] . "::$codusu::" . $RowAcc['per_in11_cod'] . "::acc_in1_nue::" . str_replace(' ', '', $RowAcc['acc_vc50_nom']) . "::new::Nuevo::" . $RowAcc['acc_vc50_nom'] . "::" . $cod_usu[1] . "</cell>";
        echo "<cell>" . $RowAcc['acc_in1_eli'] . "::$codusu::" . $RowAcc['per_in11_cod'] . "::acc_in1_eli::" . str_replace(' ', '', $RowAcc['acc_vc50_nom']) . "::delete::Eliminar::" . $RowAcc['acc_vc50_nom'] . "::" . $cod_usu[1] . "</cell>";
        echo "<cell>" . $RowAcc['acc_in1_edi'] . "::$codusu::" . $RowAcc['per_in11_cod'] . "::acc_in1_edi::" . str_replace(' ', '', $RowAcc['acc_vc50_nom']) . "::pencil::Editar::" . $RowAcc['acc_vc50_nom'] . "::" . $cod_usu[1] . "</cell>";
        echo "<cell>" . $RowAcc['acc_in1_adj'] . "::$codusu::" . $RowAcc['per_in11_cod'] . "::acc_in1_adj::" . str_replace(' ', '', $RowAcc['acc_vc50_nom']) . "::atach::Adjuntar::" . $RowAcc['acc_vc50_nom'] . "::" . $cod_usu[1] . "</cell>";
        echo "<cell>" . $RowAcc['acc_in1_xls'] . "::$codusu::" . $RowAcc['per_in11_cod'] . "::acc_in1_xls::" . str_replace(' ', '', $RowAcc['acc_vc50_nom']) . "::xls::Exportar a excel::" . $RowAcc['acc_vc50_nom'] . "::" . $cod_usu[1] . "</cell>";
        echo "<cell>" . $RowAcc['acc_in1_pdf'] . "::$codusu::" . $RowAcc['per_in11_cod'] . "::acc_in1_pdf::" . str_replace(' ', '', $RowAcc['acc_vc50_nom']) . "::pdf::Exportar a pdf::" . $RowAcc['acc_vc50_nom'] . "::" . $cod_usu[1] . "</cell>";
        echo "<cell>" . $RowAcc['acc_in1_imp'] . "::$codusu::" . $RowAcc['per_in11_cod'] . "::acc_in1_imp::" . str_replace(' ', '', $RowAcc['acc_vc50_nom']) . "::printer::Imprimir::" . $RowAcc['acc_vc50_nom'] . "::" . $cod_usu[1] . "</cell>";
        echo "<cell>" . $RowAcc['acc_in1_cor'] . "::$codusu::" . $RowAcc['per_in11_cod'] . "::acc_in1_cor::" . str_replace(' ', '', $RowAcc['acc_vc50_nom']) . "::email::Enviar por correo::" . $RowAcc['acc_vc50_nom'] . "::" . $cod_usu[1] . "</cell>";
        echo "<cell>" . $RowAcc['acc_in1_aud'] . "::$codusu::" . $RowAcc['per_in11_cod'] . "::acc_in1_aud::" . str_replace(' ', '', $RowAcc['acc_vc50_nom']) . "::audit::Auditoría::" . $RowAcc['acc_vc50_nom'] . "::" . $cod_usu[1] . "</cell>";
        echo "<cell>" . $RowAcc['acc_in1_est'] . "::$codusu::" . $RowAcc['per_in11_cod'] . "::acc_in1_est::" . str_replace(' ', '', $RowAcc['acc_vc50_nom']) . "::status::Estado::" . $RowAcc['acc_vc50_nom'] . "::" . $cod_usu[1] . "</cell>";
        echo "<cell>" . $n_lvl . "</cell>";
        echo "<cell>" . $cod . "</cell>";
        echo "<cell>true</cell>";
        echo "<cell>false</cell>";
        echo "</row>";
    }
}

echo "</rows>";
?>