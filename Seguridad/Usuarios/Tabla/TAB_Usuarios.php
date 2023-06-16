<?php

/*
  |---------------------------------------------------------------
  | PHP TAB_Usuarios.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 31/10/2011
  | @Fecha de la ultima modificacion: 31/10/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se encuentra la estructura del JqGrid en JSON de las Usuarios del sistema
 */

include_once '../../../PHP/FERConexion.php';
$db = new MySQL();
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
if (!$sidx)
    $sidx = 1;

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
        if ($fie == 'Nombre') {
            $fie = "CONCAT(usu_vc80_nom,', ',usu_vc80_ape)";
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
$result = $db->consulta("SELECT COUNT(*) AS count FROM usuario WHERE usu_in1_est !=0 $filter ");
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

$SQL = "SELECT usu_in11_cod, CONCAT(usu_vc80_nom,', ',usu_vc80_ape) AS Nombre,
        usu_vc20_telef, usu_vc50_email, usu_vc15_anexo, CONCAT(usu_in11_cod,'_',usu_in1_est) AS usu_in1_est FROM usuario u WHERE usu_in1_est !=0 $filter ORDER BY $sidx $sord LIMIT $start , $limit";
$result = $db->consulta($SQL);
while ($row = $db->fetch_assoc($result)) {

    $responce->rows[$i]['id'] = $row['usu_in1_est'];
    $responce->rows[$i]['cell'] = array('', $row['Nombre'], $row['usu_vc20_telef'], $row['usu_vc15_anexo'], $row['usu_vc50_email'], $row['usu_in1_est']);
    $i++;
}
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