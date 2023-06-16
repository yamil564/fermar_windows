<?php

/*
  |---------------------------------------------------------------
  | PHP TAB_ListConjunto.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 18/10/2011
  | @Fecha de la ultima modificacion: 18/10/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se encuentra la estructura del JqGrid en JSON del  Packing List de Orden de Producción
 */

include_once '../../../../PHP/FERConexion.php';
$db = new MySQL();
//$codusu = $_GET['usu'];
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
if (isset($_GET['con'])) {
    $codusu = $_GET['con'];
    $SQL = "SELECT COUNT(*) as count FROM conjunto c, conjunto_orden_trabajo ct
            WHERE c.con_in11_cod=ct.con_in11_cod AND ct.orp_in11_numope = '$codusu' $filter ";
}

$result = $db->consulta($SQL);
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

if (isset($_GET['con'])) {
    $codusu = $_GET['con'];
    $SQL = "SELECT c.con_in11_cod, con_in11_cant, con_do_largo, con_do_ancho, con_vc50_observ, con_vc20_marcli,
            con_vc20_nroplano FROM conjunto c, conjunto_orden_trabajo ct
            WHERE c.con_in11_cod=ct.con_in11_cod AND ct.orp_in11_numope = '$codusu' ORDER BY c.con_in11_cod ASC LIMIT $start, $limit";
}
$responce = new json();
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i = 0;
$result = $db->consulta($SQL);
while ($row = $db->fetch_assoc($result)) {
    if (isset($_GET['con'])) {
        $responce->rows[$i]['id'] = $row['con_in11_cod'];
        $responce->rows[$i]['cell'] = array($row['con_in11_cod'], $row['con_in11_cant'], $row['con_do_largo'], $row['con_do_ancho'], $row['con_vc50_observ'], $row['con_vc20_marcli'], $row['con_vc20_nroplano']);
    }
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