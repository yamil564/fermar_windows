<?php

/*
  |---------------------------------------------------------------
  | PHP MAN_OrdenProduccion.php
  |---------------------------------------------------------------
  | @Autor: Kenyi M. Caycho Coyocusi
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion: 12/09/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran los mantenimientos de la pagina FRM_OrdenProduccion.php
 */
# Zona de Recepcion de Datos
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_OrdenProduccion.php';
$db = new MySQL();
$Procedure_OrdenProduccion = new Procedure_OrdenProduccion();

$error = '';

if (isset($_REQUEST['op'])) {
    $cod_op = $_REQUEST['txt_numero_op'];
    $codupdate = $_REQUEST['txt_ordenpro'];
    $cbo_ordenpro = $_REQUEST['cbo_ordenTra'];
    $txt_usu = $_REQUEST['txt_usu'];
    $txt_fecha = (strip_tags($_REQUEST['txt_fecha']) == '') ? $error .= ",txt_fecha" : strip_tags($_REQUEST['txt_fecha']);
    $chkProd = $_REQUEST['chkProd'];
    $ids = $_REQUEST['ids'];
    $cod_ordentra =$_REQUEST['cbo_ordenpro'];
    if ($error == '') {
        $op = ($cod_op == '') ? 0 : 1;
        if ($op == 1) {
            /* Sentencia para modificar las ordenes de trabajos de la tabla orden_produccion */
            $Procedure_OrdenProduccion->SP_ModifcarOrdenProd($codupdate, $cod_op, $txt_fecha, $txt_usu, $chkProd, $ids);
            echo $cod_op . ':: Se actualizo correctamente La Orden de Produccion.';
        } else if ($op == 0) {
            /* Sentencia para grabar las ordenes de trabajo de la tabla Orden_Produccion */
            $Procedure_OrdenProduccion->SP_GrabaOrdenProd($cbo_ordenpro, $txt_fecha, $txt_usu, $chkProd, $ids, $cod_ordentra);
            echo '1:: Se Ingreso Correctamente los datos de la Orden de Produccion.';
        }
    } else {
        echo '0::' . $error;
    }
}

/* Funcion para eliminar la parte temporal seleccionado */
if (isset($_REQUEST['delTem'])) {
    $codCon = $_REQUEST['codCon'];
    $Procedure_OrdenProduccion->SP_eliminarPartTemp($codCon);
}

/* Valida si es que el conjunto se le agrego partes */
if (isset($_REQUEST['buscaPartes'])) {
    $codCon = $_REQUEST['codCon'];
    $usu = $_REQUEST['usu'];
    $valPart = $Procedure_OrdenProduccion->SP_BuscarPartesCon($codCon,$usu);
    echo $valPart;
}

/* Valida de que se halla echo la codificacion unitaria */
if (isset($_REQUEST['valcofiuni'])) {
    $usu = $_REQUEST['usu'];
    $valCodUni = $Procedure_OrdenProduccion->SP_ValidarCodificacionU($usu);
    echo $valCodUni;
}

/* Funcion para eliminar la parte temporal seleccionado */
if (isset($_REQUEST['delPartFis'])) {
    $cocCon = '';
    $cocConCod = '';
    $codCon = substr($_REQUEST['codCon'], 0, strlen($_REQUEST['codCon']) - 1);
    $codConArr = explode(",", $codCon);
    for ($i = 0; $i <= count($codConArr) - 1; $i++):
        $cocCon.="'" . $codConArr[$i] . "',";
    endfor;
    $cocConCod = substr($cocCon, 0, strlen($cocCon) - 1);
    $Procedure_OrdenProduccion->SP_eliminarPartFisicas($cocConCod);
}

if (isset($_REQUEST['del'])) {
    $db = new MySQL();$op=2;$cod="";
    if(isset($_REQUEST['opc'])){$op = $_REQUEST['opc'];}
    if($op == '2'){
        $cod = explode(',', $_REQUEST['cod']);
        for ($i = 0; $i < count($cod) - 1; $i++) {//Solo si la orden de trabajo tiene orden de produccion
            $cons = $db->consulta("SELECT ort_vc20_cod FROM orden_produccion WHERE orp_in11_numope = '$cod[$i]'");
            $row = $db->fetch_assoc($cons);
            $Procedure_OrdenProduccion->SP_EliminaOrdenProduccion($cod[$i],$row['ort_vc20_cod'],$op);
        }
    }else{
         $cod = explode(',', $_REQUEST['cod']);
         $Procedure_OrdenProduccion->SP_EliminaOrdenProduccion($cod[0],'',$op);
    }
}

if (isset($_REQUEST['parmatTem'])) {
    $codpartem = (strip_tags($_REQUEST['for_codCom']));
    $orp_in11_numope = (strip_tags($_REQUEST['codop']));
    $cbo_descPar = (strip_tags($_REQUEST['cbo_descPar']));
    $cboComp = (strip_tags($_REQUEST['cboComp']));
    $text_cant = (strip_tags($_REQUEST['for_cant']));
    $text_largo = (strip_tags($_REQUEST['text_largo']));
    $text_Ancho = (strip_tags($_REQUEST['text_Ancho']));
    $text_Long = (strip_tags($_REQUEST['text_Long']));
    $txt_PesoML = (strip_tags($_REQUEST['txt_PesoML']));
    $txt_PesoM2 = (strip_tags($_REQUEST['txt_PesoM2']));
    $txt_PesoTU = (strip_tags($_REQUEST['txt_pesoTU']));
    $txt_pesoT = (strip_tags($_REQUEST['txt_pesoT']));
    $txt_AreaPT = (strip_tags($_REQUEST['txt_area']));
    $usu = (strip_tags($_REQUEST['txt_usu']));
    $Procedure_OrdenProduccion->SP_grabarTemp_partes($codpartem, $orp_in11_numope, $cbo_descPar, $cboComp, $text_cant, $text_largo,
    $text_Ancho, $text_Long, $txt_PesoML, $txt_PesoM2, $txt_PesoTU, $txt_pesoT, $txt_AreaPT, $usu);
}

if (isset($_REQUEST['guardarhaTemp'])) {
    $cod = $_REQUEST['codCon'];
    $usu = $_REQUEST['usu'];
    $Procedure_OrdenProduccion->SP_grabarFisica_Temp($cod, $usu);
}

/* Funcion para listar las partes del conjunto segun la observacion */
if (isset($_REQUEST['ListParte'])) {
    $usu = $_REQUEST['usu'];
    $codjun = $_REQUEST['codjun'];
    $list_Obser = $Procedure_OrdenProduccion->SP_ListaPartesConjunto($codjun, $usu);
    echo $list_Obser;
}


/* Lista los componentes deacuerdo a la parte elejida */
if (isset($_REQUEST['listComPart'])) {
    $cod = $_REQUEST['cod'];
    $cons = $Procedure_OrdenProduccion->SP_ListarComp_Part($cod);
    echo $cons;
}

if (isset($_REQUEST['reloadCbo'])) {
    $cbo = $Procedure_OrdenProduccion->SP_ListaOrdenTrabajo();
    echo $cbo;
}

if (isset($_GET['BuscaComp'])) {
    $codtem = $_GET['cod_comp'];
    $data = $Procedure_OrdenProduccion->SP_Listar_peso_Comp($codtem);
    if ($_GET['BuscaComp'] == 1) {
        $json['txt_PesoML'] = $data['com_do_pesoml'];
        $json['txt_PesoM2'] = $data['com_do_pesom2'];
    } else {
        $json['tedit_PesoMLE'] = $data['com_do_pesoml'];
        $json['tedit_PesoM2E'] = $data['com_do_pesom2'];
    }
    echo (json_encode($json));
}

/* Lista las partes agregadas temporalmente a un conjunto */
if (isset($_REQUEST['listConTem'])) {
    $codCon = $_REQUEST['codCon'];
    $cboPartes = $Procedure_OrdenProduccion->SP_listar_ParTempCodigo($codCon);
    echo $cboPartes;
}

/* Para editar las partes agregadas a un conjunto temporalmente */
if (isset($_REQUEST['parmatTemMod'])) {
    $codCon = $_REQUEST['codCon'];
    $cboComp = $_REQUEST['cboComp1'];
    $cant = $_REQUEST['tedit_cantE'];
    $largo = $_REQUEST['tedit_largoE'];
    $ancho = $_REQUEST['tedit_AnchoE'];
    $longitud = $_REQUEST['tedit_LongE'];
    $pesoml = $_REQUEST['tedit_PesoMLE'];
    $pesom2 = $_REQUEST['tedit_PesoM2E'];
    $pesou = $_REQUEST['tedit_pesoTUE'];
    $pesot = $_REQUEST['tedit_pesoTE'];
    $area = $_REQUEST['tedit_areaE'];
    $Procedure_OrdenProduccion->SP_modificarPartes($codCon, $cboComp, $cant, $largo, $ancho, $longitud, $pesoml, $pesom2, $pesou, $pesot, $area);
}

/* Lista los detalles de las partes agregadas al conjunto temporalmente al seleccionar una */
if (isset($_REQUEST['listConTemDet'])) {
    $codCon = $_REQUEST['codCon'];
    $data = $Procedure_OrdenProduccion->SP_listar_ParTempDet($codCon);
    $json['cboComp1'] = $data['com_vc10_cod'];
    $json['tedit_cantE'] = $data['coc_in11_cant'];
    $json['tedit_largoE'] = $data['coc_do_largo'];
    $json['tedit_AnchoE'] = $data['coc_do_ancho'];
    $json['tedit_LongE'] = $data['coc_do_long'];
    $json['tedit_PesoMLE'] = $data['coc_do_psml'];
    $json['tedit_PesoM2E'] = $data['coc_do_psm2'];
    $json['tedit_pesoTUE'] = $data['coc_do_psu'];
    $json['tedit_pesoTE'] = $data['coc_do_psto'];
    $json['tedit_areaE'] = $data['coc_do_arto'];
    echo (json_encode($json));
}

/* Sentencia que sirve para la paginacion del formulario de la Orden de Produccion */
if (isset($_GET['m'])) {
    $pag = $_GET['pag'];
    $cod_op = $_GET['id'];
    $res_pos = $db->consulta("SELECT * FROM orden_produccion WHERE orp_in1_est = '1' ORDER BY orp_in11_numope DESC");
    $con_pos = 0;
    $post_op = 0;
    $row_val = 0;
    while ($row_pos = $db->fetch_assoc($res_pos)) {
        $row_val = $row_pos['orp_in11_numope'];
        if ($cod_op == $row_val) {
            $post_op = $con_pos;
        }
        $con_pos++;
    }
    if ($pag == 'none') {
        $cod = $_GET['id'];
    } else {
        if ($pag == "prev") {
            if ($post_op - 1 == '-1') {
                $post_op = $post_op;
            } else {
                $post_op = $post_op - 1;
            }
        }
        if ($pag == "next") {
            if ($post_op + 1 > $con_pos - 1) {
                $post_op = $post_op;
            } else {
                $post_op = $post_op + 1;
            }
        }
        if ($pag == "first") {
            $post_op = "0";
        }
        if ($pag == "last") {
            $post_op = $con_pos - 1;
        }
        $res_pag = $db->consulta("SELECT * FROM orden_produccion WHERE orp_in1_est = '1' ORDER BY orp_in11_numope DESC LIMIT $post_op , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $cod = $row['orp_in11_numope'];
    }
    $pos_real = $post_op + 1;
    $cons = $db->consulta("SELECT * FROM orden_produccion WHERE orp_in11_numope ='" . $cod . "'");
    $data = $db->fetch_assoc($cons);
    $json['txt_numero_op'] = $cod;
    $json['txt_fecha'] = $data['orp_da_fech'];
    $json['txt_ordenpro'] = $data['ort_vc20_cod'];
    $json['cbo_ordenpro'] = $data['ort_vc20_cod'];
    $json['sp_posini'] = $pos_real;
    $json['sp_postot'] = $con_pos;
    echo (json_encode($json));
}
/* * ************************************* MANTENIMIENTO DE CONJUNTO TEMPORAL ************************************************************ */
if (isset($_REQUEST['a'])) {
    $codCon = (strip_tags(trim($_REQUEST['txt_busconj_cod2'])));
    $cbo_fermar = (strip_tags(trim($_REQUEST["cbo_busfermar2"])));
    $txt_usu = (strip_tags(trim($_REQUEST["txt_usu2"])));
    $txt_plano = (trim($_REQUEST['txt_busplano2']) == '') ? $error .= ",txt_busplano2" : (strip_tags(trim($_REQUEST['txt_busplano2'])));
    $txt_marca = (trim($_REQUEST['txt_busmarca2']) == '') ? $error .= ",txt_busmarca2" : (strip_tags(trim($_REQUEST['txt_busmarca2'])));
    $txt_cant = (trim($_REQUEST['txt_buscant2']) == '') ? $error .= ",txt_buscant2" : (strip_tags(trim($_REQUEST['txt_buscant2'])));
    $txt_largo = (trim($_REQUEST['txt_buslargo2']) == '') ? $error .= ",txt_buslargo2" : (strip_tags(trim($_REQUEST['txt_buslargo2'])));
    $txt_ancho = (trim($_REQUEST['txt_busancho2']) == '') ? $error .= ",txt_busancho2" : (strip_tags(trim($_REQUEST['txt_busancho2'])));
    $cbo_tipconj = (strip_tags(trim($_REQUEST["cbo_bustipconj2"])));
    if($_REQUEST['chk_busdetalle2'] == 'true'){$chk_detalle = '1';}else{$chk_detalle='0';}
    $txt_obs = (strip_tags(trim($_REQUEST['txt_busobs2'])));
    if ($error == '') {
        $con = ($codCon == '') ? 0 : 1;
        if ($con == 1) {
            /* Sentencia para modificar los el conjunto temporal de la tabla conjunto */
            $var = $Procedure_OrdenProduccion->SP_ModificaConjunto($codCon, $txt_usu, $cbo_fermar, $txt_plano, $txt_marca, $txt_cant, $txt_largo, $txt_ancho, $cbo_tipconj, $chk_detalle, $txt_obs);
            echo $var;
            //echo $codCon.'::Se Actualizo correctamente El Conjunto.';
        } else if ($con == 0) {
            /* Sentencia para grabar el conjunto temporal de la tabla temporal_conjunto */
//                $Procedure_OrdenProduccion->SP_GrabatemConjunto($txt_usu, $cbo_fermar, $txt_plano, $txt_marca, $txt_cant, $txt_largo, $txt_ancho, $cbo_tipconj, $chk_detalle, $txt_obs);
//                echo '1:: Se Ingreso Correctamente los datos del Conjunto.';
        }
    } else {
        echo '0::' . $error;
    }
}
/* Funcion para Grabar las partes y materiales del conjunto base en la tabla temporal_base */
if (isset($_REQUEST['GrabaBaseTemp'])) {
    $cod_CB = $_REQUEST['codBase'];
    $codusu = $_REQUEST['codus'];
    $Procedure_OrdenProduccion->SP_GrabaConBaseTemp($cod_CB, $codusu);
}
/* Lista los Conjuntos Temporales */
if (isset($_REQUEST['codtemCon'])) {
    $codtemCon = $_REQUEST['codtemCon'];
    $data = $Procedure_OrdenProduccion->SP_Lista_temporalConjunto($codtemCon);
    echo $data['tco_in11_cod'] . '::' . $data['tco_vc50_cob'] . '::' . $data['tco_vc20_nroplano'] . '::' . $data['tco_vc20_marcli'] . '::' . $data['tco_in11_cant'] . '::' . $data['tco_do_largo'] . '::' . $data['tco_do_ancho'] . '::' . $data['tco_vc11_codtipcon'] . '::' . $data['tco_in1_detalle'] . '::' . $data['tco_vc50_obser'];
}
/* Sentencia que nos sirve para eliminar los Conjuntos Temporales */
if (isset($_REQUEST['delCon'])) {
    $codCon = explode(",", $_REQUEST['codCon']);
    for ($i = 0; $i < count($codCon) - 1; $i++) {
        $Procedure_OrdenProduccion->SP_EliminatemConjunto($codCon[$i]);
    }
}

/* Graba los Conjuntos de la Orden de Produccion en la tabla temporal_conjunto */
if (isset($_REQUEST['GrabaConTemp'])) {
    $cod_OT = $_REQUEST['codCon'];
    $codusu = $_REQUEST['codus'];
    $Procedure_OrdenProduccion->sp_GrabaConTemp($cod_OT, $codusu);
}

if (isset($_REQUEST['GrabaConTemp2'])) {
    $cod_OT = $_REQUEST['codCon'];
    $codusu = $_REQUEST['codus'];
    $Procedure_OrdenProduccion->sp_GrabaConTempBus($cod_OT, $codusu);
}

/* Sentencia para listar la Codificacion Unitaria */
if (isset($_REQUEST['CodiUnit'])) {
    $cod = explode(",", $_REQUEST['cod']);
    $txt_usu = $_REQUEST['usu'];
    //$cod_con = $_REQUEST['codCon'];
    $Procedure_OrdenProduccion->SP_CodificacionUnitaria($txt_usu);
}
/* Lista los conjuntos temporales */
if (isset($_GET['ListaConjunto'])) {
    $codtemCon = $_GET['codConjunto'];
    $data = $Procedure_OrdenProduccion->SP_Lista_temporalConjunto($codtemCon);
    $json['txt_busconj_cod2'] = $data['tco_in11_cod'];
    $json['txt_busplano2'] = $data['tco_vc20_nroplano'];
    $json['txt_busmarca2'] = $data['tco_vc20_marcli'];
    $json['txt_buscant2'] = $data['tco_in11_cant'];
    $json['cbo_busfermar2'] = $data['tco_vc50_cob'];
    $json['txt_buslargo2'] = $data['tco_do_largo'];
    $json['txt_busancho2'] = $data['tco_do_ancho'];
    $json['cbo_bustipconj2'] = $data['tco_vc11_codtipcon'];
    $json['chk_busdetalle2'] = $data['tco_in1_detalle'];
    $json['txt_busobs2'] = $data['tco_vc50_obser'];
    echo (json_encode($json));
}
/* elimina las tablas temporales segun el codigo del usuario */
if (isset($_REQUEST['DelTemporal'])) {
    $cod = $_REQUEST['cod'];
    $Procedure_OrdenProduccion->SP_EliminaTemporal($cod);
}
/* elimina las tablas temporales segun el codigo del usuario */
if (isset($_REQUEST['listOTall'])) {
    echo $Procedure_OrdenProduccion->SP_ListarOTall();
}
?>
