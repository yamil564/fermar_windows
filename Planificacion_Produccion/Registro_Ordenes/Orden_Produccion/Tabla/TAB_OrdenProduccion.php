<?php

/*
  |---------------------------------------------------------------
  | PHP TAB_OrdenProduccion.php
  |---------------------------------------------------------------
  | @Autor: Kenyi M. Caycho Coyocusi
  | @Fecha de creacion: 11/01/2011
  | @Fecha de la ultima modificacion: 19/01/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se encuentra la estructura del JqGrid en JSON de la Orden de Produccion
 */
include_once '../../../../PHP/FERConexion.php';
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
        if ($fie == 'ort_vc20_cod') {
            $fie = 'op.ort_vc20_cod';
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
$result = $db->consulta("SELECT COUNT(*) AS count FROM orden_produccion op, orden_trabajo ot WHERE op.ort_vc20_cod=ot.ort_vc20_cod AND orp_in1_est != '0' $filter ");

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


$SQL = "SELECT orp_in11_numope, ort_ch10_num, op.ort_vc20_cod, usu_in11_cod, orp_da_fech, orp_do_pesototal,
       orp_do_areatotal, con_vc11_codtipcon, orp_in1_est FROM orden_produccion op, orden_trabajo ot
       WHERE op.ort_vc20_cod=ot.ort_vc20_cod AND orp_in1_est != '0' $filter ORDER BY $sidx $sord LIMIT $start, $limit";
$result = $db->consulta($SQL);
while ($row = $db->fetch_assoc($result)) {
    $responce->rows[$i]['id'] = utf8_encode($row['orp_in11_numope']) . "::" . utf8_encode($row['con_vc11_codtipcon']);
    $responce->rows[$i]['cell'] = array('', utf8_encode($row['ort_vc20_cod']), utf8_encode($row['orp_da_fech']), utf8_encode($row['orp_do_pesototal']), utf8_encode($row['orp_do_areatotal']), $row['con_vc11_codtipcon']);
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