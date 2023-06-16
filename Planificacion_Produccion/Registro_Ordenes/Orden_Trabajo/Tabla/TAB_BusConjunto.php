<?php
/*
|---------------------------------------------------------------
| PHP TAB_Conjunto.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Modificado por: Frank Peña Ponce
| @Fecha de la ultima modificacion: 12/09/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se encuentra la estructura del JqGrid en JSON del Conjunto de la Orden de Trabajo
*/
include_once '../../../../PHP/FERConexion.php';
$db = new MySQL();
//$codusu = $_GET['usu'];
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
if(isset($_GET['usu'])){
    $codusu = $_GET['usu'];
    $SQL = "SELECT COUNT(*) AS count FROM temporal_conjunto WHERE usu_in11_cod = '".$codusu."' $filter";
}
if(isset($_GET['cod'])){
    $cod = $_GET['cod'];
    $SQL = "SELECT COUNT(*) AS count FROM conjunto_orden_trabajo WHERE ort_ch10_num = '".$cod."' $filter";
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
    if(isset($_GET['usu'])){
        $codusu = $_GET['usu'];
        $SQL = "SELECT tco_in11_cant,con_in11_cod,tco_do_largo,tco_do_ancho,tco_vc50_obser,tco_vc20_marcli,tco_in11_cod,tco_vc50_cob,tco_vc20_nroplano
                     FROM temporal_conjunto  WHERE usu_in11_cod ='".$codusu."' $filter ORDER BY $sidx $sord LIMIT $start, $limit";
    }
    if(isset ($_GET['cod'])){
        $cod = $_GET['cod'];
        $SQL = "SELECT c.con_in11_cod, c.cob_vc50_cod, c.con_vc20_nroplano, c.con_vc20_marcli, c.con_in11_cant, c.con_do_largo, c.con_do_ancho, c.con_vc11_codtipcon, c.con_vc50_observ
                FROM conjunto c, conjunto_orden_trabajo co
                WHERE c.con_in11_cod = co.con_in11_cod AND c.con_in1_est='1' AND co.ort_ch10_num= '".$cod."' $filter ORDER BY $sidx $sord LIMIT $start, $limit";
    }
$responce = new json();
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
$result = $db->consulta($SQL);
    while($row =  $db->fetch_assoc($result)) {
        if(isset ($_GET['usu'])){
            $responce->rows[$i]['id']=$row['tco_in11_cod'];//temporal
            $responce->rows[$i]['cell']=array('',$row['tco_in11_cant'],$row['tco_do_largo'],$row['tco_do_ancho'],$row['tco_vc50_obser'],$row['tco_vc20_marcli'],$row['tco_vc50_cob'],$row['tco_vc20_nroplano']);
        }
        if(isset ($_GET['cod'])){//tabla real
            $responce->rows[$i]['id']=$i;
            $responce->rows[$i]['cell']=array($row['con_vc20_nroplano'],$row['con_vc20_marcli'],$row['con_vc11_codtipcon'],$row['cob_vc50_cod'],$row['con_in11_cant'],$row['con_do_largo'],$row['con_do_ancho'],$row['con_vc50_observ']);
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