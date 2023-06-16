<?php
/*
|---------------------------------------------------------------
| PHP TAB_Proceso.php
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 15/12/2010
| @Modificado por: Frank Peña Ponce
| @Fecha de la ultima Modificacion: 21/03/2012
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se encuentra la estructura del JqGrid en JSON de los Procesos
*/

include_once '../../../../PHP/FERConexion.php';
$db = new MySQL();
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
if(!$sidx) $sidx =1;

/* En caso estemos realizando una busqueda o un filtro. */
$filter = "";
$search_ind = $_GET['_search'];
if($search_ind=='true'){
    $filter_cad = json_decode(stripslashes($_GET['filters']));
    $filter_opc = $filter_cad->{'groupOp'};
    $filter_rul = $filter_cad->{'rules'};
    $cont = 0;
    foreach($filter_rul as $key => $value){
        $fie = $filter_rul[$key]->{'field'};
        $opt = Search($filter_rul[$key]->{'op'});
        $dat = $filter_rul[$key]->{'data'};
        if($cont==0){
           if($fie == 'area'){
               $fie = "(CASE WHEN pro_in1_tip = 1 THEN 'PRODUCCIÓN'
                       WHEN pro_in1_tip = 2 THEN 'CALIDAD' END)";
           } 
           if($opt=="REGEXP1"){
                $opt = "LIKE";
                $filter .= " AND ($fie $opt '$dat%'";
            }else{
                if($opt=="REGEXP2"){
                    $opt = "LIKE";
                    $filter .= " AND ($fie $opt '%$dat'";
                }else{
                    if($opt=="NOT REGEXP1"){
                        $opt = "NOT LIKacabaE";
                        $filter .= " AND ($fie $opt '$dat%'";
                    }else{
                        if($opt=="NOT REGEXP2"){
                            $opt = "NOT LIKE";
                            $filter .= " AND ($fie $opt '%$dat'";
                        }else{
                            if($opt=='LIKE' || $opt=='NOT LIKE'){
                                $filter .= " AND ($fie $opt '%$dat%'";
                            }else{
                                $filter .= " AND ($fie $opt '$dat'";
                            }
                        }
                    }
                }
            }
            $cont++;
        }else{
            if($opt=="REGEXP1"){
                $opt = "LIKE";
                $filter .= " $filter_opc $fie $opt '$dat%'";
            }else{
                if($opt=="REGEXP2"){
                    $opt = "LIKE";
                    $filter .= " $filter_opc $fie $opt '%$dat'";
                }else{
                    if($opt=="NOT REGEXP1"){
                        $opt = "NOT LIKE";
                        $filter .= " $filter_opc $fie $opt '$dat%'";
                    }else{
                        if($opt=="NOT REGEXP2"){
                            $opt = "NOT LIKE";
                            $filter .= " $filter_opc $fie $opt '%$dat'";
                        }else{
                            if($opt=='LIKE' || $opt=='NOT LIKE'){
                                $filter .= " $filter_opc $fie $opt '%$dat%'";
                            }else{
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
$result = $db->consulta("SELECT COUNT(*) AS count FROM proceso WHERE pro_in1_est != '0' $filter ");
$Cons = $db->fetch_assoc($result);
$count = $Cons['count'];

if($count > 0 && $limit > 0) {
    $total_pages = ceil($count / $limit);
}else{
    $total_pages = 0;
}
if($page > $total_pages) $page = $total_pages;
$start = $limit * $page - $limit;
if($start < 0) $start = 0;

class json{
    var $page = 0;
    var $total = 0;
    var $records = 0;
}

$responce = new json();
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

$SQL = "SELECT pro_in11_cod, pro_vc50_desc, pro_vc10_alias, (CASE WHEN pro_in1_tip = 1 THEN 'PRODUCCIÓN'
        WHEN pro_in1_tip = 2 THEN 'CALIDAD' END) AS area FROM proceso WHERE pro_in1_est != '0' $filter ORDER BY $sidx $sord LIMIT $start , $limit";
$result = $db->consulta($SQL);
while($row =  $db->fetch_assoc($result)) {
    $cont = strlen($row['pro_in11_cod']);
    /* Sentencia para Listar todos los Procesos Concatenados */
    switch($cont){
        case 1: $cod = 'A0000000'.$row['pro_in11_cod'];break;
        case 2: $cod = 'A000000'.$row['pro_in11_cod'];break;
	case 3: $cod = 'A00000'.$row['pro_in11_cod'];break;
        case 4: $cod = 'A0000'.$row['pro_in11_cod'];break;
        case 5: $cod = 'A000'.$row['pro_in11_cod'];break;
        case 6: $cod = 'A00'.$row['pro_in11_cod'];break;
        case 7: $cod = 'A0'.$row['pro_in11_cod'];break;
        case 8: $cod = 'A'.$row['pro_in11_cod'];break;

    }
    $responce->rows[$i]['id']=$row['pro_in11_cod'];
    $responce->rows[$i]['cell']=array('',utf8_encode($cod),$row['pro_vc50_desc'],utf8_encode($row['pro_vc10_alias']),$row['area']);
        $i++;
}
echo json_encode($responce);
/* Función Search
 * Descripción : Sirve para cambiar a operador SQL, de acuerdo al tipo de busqueda enviado. */
function Search($oper){
	switch($oper){
		case "eq" : $oper = "="; break;
		case "ne" : $oper = "!="; break;
		case "lt" : $oper = "<"; break;
		case "le" : $oper = "<="; break;
		case "gt" : $oper = ">"; break;
		case "ge" : $oper = ">="; break;
		case "bw" : $oper = "REGEXP1"; break;
		case "bn" : $oper = "NOT REGEXP1"; break;
		case "in" : $oper = "IN"; break;
		case "ni" : $oper = "NOT IN"; break;
		case "ew" : $oper = "REGEXP2"; break;
		case "en" : $oper = "NOT REGEXP2"; break;
		case "cn" : $oper = "LIKE"; break;
		case "nc" : $oper = "NOT LIKE"; break;
	}
	return $oper;
}
?>