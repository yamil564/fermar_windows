<?php

/*
  |---------------------------------------------------------------
  | PHP TAB_Inspeccion_Prod.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 10/11/2011
  | @Fecha de la ultima modificacion: 10/11/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se encuentra la estructura del JqGrid en JSON de Inspección
 */

date_default_timezone_set("America/Lima");
include_once '../../../../PHP/FERConexion.php';
$db = new MySQL();

$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$usu = $_GET['usu']; 
$fecha = date("d/m/Y");
if (!$sidx) $sidx = 1;

/* En caso estemos realizando una busqueda o un filtro. */
$filter = "";
$search_ind = $_GET['_search'];
if ($search_ind == 'true') {
    $filter_cad = json_decode(stripslashes($_GET['filters']));
    $filter_opc = $filter_cad->{'groupOp'};
    $filter_rul = $filter_cad->{'rules'};
    $cont = 0;
    foreach ($filter_rul as $key => $value) {
        $fie = $filter_rul[$key]->{'field'};
        $opt = Search($filter_rul[$key]->{'op'});
        $dat = $filter_rul[$key]->{'data'};
        if($fie == 'supervisor'){
            $fie = "(SELECT CONCAT(tra_vc150_ape,', ',tra_vc150_nom) AS nombre FROM trabajador tra
                    WHERE tra.tra_in11_cod=dip.tra_in11_sup)";
        }
        if($fie == 'operario'){
            $fie = "(SELECT CONCAT(tra_vc150_ape,', ',tra_vc150_nom) AS nombre FROM trabajador tra2
                    WHERE tra2.tra_in11_cod=dip.tra_in11_ope)";
        }
        if($fie == 'fecha'){
           $fi= "(Date_format(det_dt_fech,'%d/%m/%Y'))";
        }
        if($fie == 'hora'){
           $fi= "(Date_format(det_tm_hora, '%h:%i:%s %p'))";
        }
        if ($cont == 0) {
            if ($opt == "REGEXP1") {
                $opt = "LIKE";
                $filter .= " AND ($fie $opt '$dat%'";
            } else {
                if ($opt == "REGEXP2") {
                    $opt = "LIKE";
                    $filter .= " AND ($fie $opt '%$dat'";
                } else {
                    if ($opt == "NOT REGEXP1") {
                        $opt = "NOT LIKE";
                        $filter .= " AND ($fie $opt '$dat%'";
                    } else {
                        if ($opt == "NOT REGEXP2") {
                            $opt = "NOT LIKE";
                            $filter .= " AND ($fie $opt '%$dat'";
                        } else {
                            if ($opt == 'LIKE' || $opt == 'NOT LIKE') {
                                $filter .= " AND ($fie $opt '%$dat%'";
                            } else {
                                $filter .= " AND ($fie $opt '$dat'";
                            }
                        }
                    }
                }
            }
            $cont++;
        } else {
            if ($opt == "REGEXP1") {
                $opt = "LIKE";
                $filter .= " $filter_opc $fie $opt '$dat%'";
            } else {
                if ($opt == "REGEXP2") {
                    $opt = "LIKE";
                    $filter .= " $filter_opc $fie $opt '%$dat'";
                } else {
                    if ($opt == "NOT REGEXP1") {
                        $opt = "NOT LIKE";
                        $filter .= " $filter_opc $fie $opt '$dat%'";
                    } else {
                        if ($opt == "NOT REGEXP2") {
                            $opt = "NOT LIKE";
                            $filter .= " $filter_opc $fie $opt '%$dat'";
                        } else {
                            if ($opt == 'LIKE' || $opt == 'NOT LIKE') {
                                $filter .= " $filter_opc $fie $opt '%$dat%'";
                            } else {
                                $filter .= " $filter_opc $fie $opt '$dat'";
                            }
                        }
                    }
                }
            }
        }
    }
    $filter .= ")";
}
$result = $db->consulta("SELECT COUNT(*) AS count FROM detalle_inspeccion_prod dip, proceso pro, orden_conjunto orc
                         WHERE dip.pro_in11_cod=pro.pro_in11_cod AND dip.orc_in11_cod=orc.orc_in11_cod AND tra_in11_sup = '$usu' AND
                         (Date_format(det_dt_fech,'%d/%m/%Y')) = '$fecha' AND det_in1_sta !=0 $filter ");

$Cons = $db->fetch_assoc($result);
$count = $Cons['count'];

if ($count > 0 && $limit > 0) {
    $total_pages = ceil($count / $limit);
} else {
    $total_pages = 0;
}
if ($page > $total_pages)
    $page = $total_pages;
$start = $limit * $page - $limit;
if ($start < 0)
    $start = 0;

class json {

    var $page = 0;
    var $total = 0;
    var $records = 0;

}

$responce = new json();
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i = 0;


$SQL = "SELECT det_in11_cod, ort_vc20_cod, det_in11_items, orc_vc20_marclis, pro_vc50_desc,
        (SELECT CONCAT(tra_vc150_ape,', ',tra_vc150_nom) AS nombre FROM trabajador tra
        WHERE tra.tra_in11_cod=dip.tra_in11_sup) AS supervisor,
        (SELECT CONCAT(tra_vc150_ape,', ',tra_vc150_nom) AS nombre FROM trabajador tra2
        WHERE tra2.tra_in11_cod=dip.tra_in11_ope) AS operario, (Date_format(det_dt_fech,'%d/%m/%Y')) AS fecha,
        (Date_format(det_tm_hora, '%h:%i:%s %p')) AS hora FROM detalle_inspeccion_prod dip, proceso pro, orden_conjunto orc
        WHERE dip.pro_in11_cod=pro.pro_in11_cod AND dip.orc_in11_cod=orc.orc_in11_cod AND tra_in11_sup = '$usu' AND
        (Date_format(det_dt_fech,'%d/%m/%Y')) = '$fecha' AND det_in1_sta !=0 $filter ORDER BY $sidx $sord LIMIT $start, $limit";


$result = $db->consulta($SQL);
while ($row = $db->fetch_assoc($result)) {
    $responce->rows[$i]['id'] = $row['det_in11_cod'];
    $responce->rows[$i]['cell'] = array('', $row['ort_vc20_cod'], $row['det_in11_items'], $row['orc_vc20_marclis'], $row['pro_vc50_desc'], $row['supervisor'] , $row['operario'], $row['fecha'], $row['hora']);
    $i++;
};
echo json_encode($responce);
/* Función Search
 * Descripción : Sirve para cambiar a operador SQL, de acuerdo al tipo de busqueda enviado. */

function Search($oper) {
    switch ($oper) {
        case "eq" : $oper = "=";
            break;
        case "ne" : $oper = "!=";
            break;
        case "lt" : $oper = "<";
            break;
        case "le" : $oper = "<=";
            break;
        case "gt" : $oper = ">";
            break;
        case "ge" : $oper = ">=";
            break;
        case "bw" : $oper = "REGEXP1";
            break;
        case "bn" : $oper = "NOT REGEXP1";
            break;
        case "in" : $oper = "IN";
            break;
        case "ni" : $oper = "NOT IN";
            break;
        case "ew" : $oper = "REGEXP2";
            break;
        case "en" : $oper = "NOT REGEXP2";
            break;
        case "cn" : $oper = "LIKE";
            break;
        case "nc" : $oper = "NOT LIKE";
            break;
    }
    return $oper;
}

?>