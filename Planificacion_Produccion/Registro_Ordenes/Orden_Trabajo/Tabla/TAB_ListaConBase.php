<?php
/*
|---------------------------------------------------------------
| PHP TAB_ListaBase.php
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 26/01/2011
| @Fecha de la ultima modificacion: 
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se encuentra la estructura del JqGrid en JSON de las partes y materiales del Conjunto Base para El Conjunto Principal
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
            if($fie == "par_in11_cod"){
             $fie = "p.par_in11_cod"   ;
            }
            if($fie == "mat_vc3_cod"){
                $fie = "m.mat_vc3_cod";
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
if(isset($_GET['codus'])){
    $codusu = $_GET['codus'];
    $SQL = "SELECT COUNT(*) AS count FROM parte p, materia m, temporal_conbase t
                WHERE p.par_in11_cod = t.par_in11_cod AND m.mat_vc3_cod = t.mat_vc3_cod AND t.usu_in11_cod = '".$codusu."' $filter ";
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

$responce = new json();
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

if(isset($_GET['codus'])){
    $codusu = $_GET['codus'];
    $SQL = "SELECT t.tcb_in11_cod, p.par_in11_cod, p.par_vc50_desc, m.mat_vc3_cod, m.mat_vc50_desc, m.mat_do_largo, m.mat_do_ancho, m.mat_do_espesor, m.mat_do_diame
            FROM parte p, materia m, temporal_conbase t
                WHERE p.par_in11_cod = t.par_in11_cod AND m.mat_vc3_cod = t.mat_vc3_cod AND t.usu_in11_cod = '".$codusu."' $filter ORDER BY $sidx $sord LIMIT $start, $limit";
}

$result = $db->consulta($SQL);
while($row =  $db->fetch_assoc($result)) {
     if(isset ($_GET['codus'])){
        $responce->rows[$i]['id']=$row['tcb_in11_cod'];
        $responce->rows[$i]['cell']=array('',$row['par_in11_cod'],$row['par_vc50_desc'],$row['mat_vc3_cod'],$row['mat_vc50_desc'],$row['mat_do_largo'],$row['mat_do_ancho'],$row['mat_do_espesor'],$row['mat_do_diame']);
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
