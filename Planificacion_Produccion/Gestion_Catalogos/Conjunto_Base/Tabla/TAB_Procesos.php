<?php
/*
|---------------------------------------------------------------
| PHP TAB_Procesos.php
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 16/12/2010
| @Fecha de modificacion: 25/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se encuentra la estructura del JqGrid en JSON del los procesos del conjunto base
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
            if($fie == "pro_in11_cod"){
                $fie = "p.pro_in11_cod";
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
                        $opt = "NOT LIKE";
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
if(isset ($_GET['usu2'])){
    $codusu = $_GET['usu2'];
    $SQL = "SELECT COUNT(*) AS count FROM proceso p, temporal_proceso t WHERE p.pro_in11_cod = t.pro_in11_cod AND t.usu_in11_cod = '$codusu' $filter ";
}
if(isset($_GET['cod'])){
    $cod = $_GET['cod'];
    $SQL = "SELECT COUNT(*) AS count FROM proceso_conjunto_base WHERE cob_vc50_cod = '$cod' $filter";
}
$result = $db->consulta($SQL);
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

if(isset($_GET['usu2'])){
    $codusu2 = $_GET['usu2'];
    $SQL = "SELECT t.tpr_in11_cod, p.pro_in11_cod, p.pro_vc50_desc
            FROM proceso p, temporal_proceso t
                WHERE p.pro_in11_cod = t.pro_in11_cod AND t.usu_in11_cod = '$codusu2' $filter ORDER BY $sidx $sord LIMIT $start , $limit";
}
if(isset($_GET['cod'])){
    $cod = $_GET['cod'];
    $SQL = "SELECT p.pro_in11_cod, p.pro_vc50_desc FROM proceso p, proceso_conjunto_base pc
            WHERE p.pro_in11_cod = pc.pro_in11_cod AND cob_vc50_cod='$cod' $filter ORDER BY $sidx $sord LIMIT $start , $limit";
}

$responce = new json();
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

$result = $db->consulta($SQL);
    while($row =  $db->fetch_assoc($result)) {
        if(isset ($_GET['usu2'])){
        $responce->rows[$i]['id']=$row['tpr_in11_cod'];
        $responce->rows[$i]['cell']=array('',$row['pro_in11_cod'],$row['pro_vc50_desc']);
        }
        if(isset ($_GET['cod'])){
        $responce->rows[$i]['id']=$i;
        $responce->rows[$i]['cell']=array($row['pro_in11_cod'],$row['pro_vc50_desc']);
        }
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
