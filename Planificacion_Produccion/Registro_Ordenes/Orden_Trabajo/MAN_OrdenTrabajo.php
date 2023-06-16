<?php

/*
  |---------------------------------------------------------------
  | PHP MAN_OrdenTrabajo.php
  |---------------------------------------------------------------
  | @Autor: Kenyi M. Caycho Coyocusi
  | @Fecha de creacion: 29/01/2011
  | @Modificado por: Frank Peña Ponce,Jean Guzman Abregu
  | @Fecha de la ultima modificacion: 17/08/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran los mantenimientos de la pagina FRM_OrdenTrabajo.php
 */

# Zona de Recepcion de Datos
date_default_timezone_set('America/Lima');
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_OrdenTrabajo.php';
include_once '../../../Librerias/LibExcel/reader.php';
$db = new MySQL();
$Procedure_OrdenTrabajo = new Procedure_OrdenTrabajo();

$error = '';

/* Recuperando los datos de la Orden de Trabajo */
if (isset($_POST['con'])) {
    $cod_OT = $_POST['txt_nro'];
    $cod_ORT = $_POST['txt_ort_cod'];
    $txt_usu = $_POST['txt_usu'];
    //
    $fecha_objeto = strtotime($_POST['txt_fech_emi']);
    $txt_fech_emi = date('Y-m-d', $fecha_objeto);
    
    $txt_fech_ini = $_POST['txt_fech_ini'];
    $txt_fech_ent = $_POST['txt_fech_ent'];
    $txt_nro_ordencompra = $_POST['txt_nro_ordencompra'];
    $txt_fech_ordencompra = $_POST['txt_fech_ordencompra'];
    $txt_nro_presupuesto = $_POST['txt_nro_presupuesto'];
    $distanciaPort = $_POST['txt_portante'];
    $distanciaArris = $_POST['txt_arriostre'];
    $acabado = $_POST['cboacabado'];
    $cbo_tipconj = $_POST["cbo_bustipconj"];
    $cbo_razoncliente = intval($_POST['cbo_razoncliente']);
    $cbo_proyecto = intval($_POST['cbo_proyecto']);
    $cboEspDet = $_POST['cboEspCalDet'];
    $cboEspSol = $_POST['cboEspCalSol'];
    $year_fin = date('Y');


    if ($error == '') {

        $ot = ($cod_OT == '') ? 0 : 1;
        if ($ot == 1) {
            /* Sentencia para modificar las ordenes de trabajo de la tabla orden_trabajo */
            $Procedure_OrdenTrabajo->SP_Modifica_OrdenTrabajo($cod_OT, $cod_ORT, $txt_usu, $cbo_razoncliente, $cbo_proyecto, $txt_fech_emi, $txt_nro_ordencompra, $txt_fech_ordencompra, $txt_nro_presupuesto, $txt_fech_ini, $txt_fech_ent, $distanciaPort, $distanciaArris, $acabado, $cbo_tipconj, $cboEspDet, $cboEspSol);
            echo $cod_OT . ':: Se actualizo correctamente La Orden de Trabajo.';
        } else if ($ot == 0) {
            $row = $Procedure_OrdenTrabajo->SP_Validar_CodOrdenTrabajo($cod_ORT);
            if ($row != '0'):
                echo '2:: El codigo ya esta en uso.::,txt_ort_cod';
            else:
                /* Sentencia para grabar las ordenes de trabajo de la tabla orden_trabajo */
                $Procedure_OrdenTrabajo->SP_Graba_OrdenTrabajo($cod_ORT, $txt_usu, $year_fin, $cbo_razoncliente, $cbo_proyecto, $txt_fech_emi, $txt_nro_ordencompra, $txt_fech_ordencompra, $txt_nro_presupuesto, $txt_fech_ini, $txt_fech_ent, $distanciaPort, $distanciaArris, $acabado, $cbo_tipconj, $cboEspDet, $cboEspSol);
                echo '1:: Se Ingreso Correctamente los datos de la Orden de Trabajo.';
            endif;
        }
    }else {
        echo '0::' . $error;
    }
}

/* Sentencia para Eliminar la Orden de Trabajo */
if (isset($_POST['del'])) {
    $cod_OT = explode(',', $_POST['cod']);
    for ($i = 0; $i < count($cod_OT) - 1; $i++) {
        $Procedure_OrdenTrabajo->SP_Elimina_OrdenTrabajo($cod_OT[$i]);
    }
}

/* Para listar las partes definidas en las observaciones para las partes del peldaño */
if (isset($_REQUEST['valParPel'])) {
    $observ = $_REQUEST['observ'];
    $listParPel = $Procedure_OrdenTrabajo->SP_ListaPartesConjuntoPel($observ);
    echo $listParPel;
}

/* Busca los componentes que se ingresaron al peldaño  */
if (isset($_REQUEST['buscarComPel'])) {
    $conjunto = $_REQUEST['conjunto'];
    $operacion = $_REQUEST['operacion'];
    $usu = $_REQUEST['usu'];
    $parte = $_REQUEST['parte'];
    $busComPel = $Procedure_OrdenTrabajo->SP_BuscarComPel($conjunto, $operacion, $usu, $parte);
    echo $busComPel;
}

/* Valida de que si hay tapas en el peldaño, te oblique a ingresar primero la tapa y despues lo resto */
if (isset($_REQUEST['valParPelTapa'])) {
    $observ = $_REQUEST['observ'];
    $valTapa = $Procedure_OrdenTrabajo->SP_ValidarTapa($observ);
    echo $valTapa;
}

/* Valida si se a ingresado la tapa al peldaño, en caso contrario te vota un aviso */
if (isset($_REQUEST['valbuscarTapa'])) {
    $usu = $_REQUEST['usu'];
    $conCod = $_REQUEST['conjunto'];
    $ope = $_REQUEST['operacion'];
    $valTapa = $Procedure_OrdenTrabajo->SP_BuscarTapa($usu, $conCod, $ope);
    echo $valTapa;
}

/* Saca la longitud de la cantonera */
if (isset($_REQUEST['longcanto'])) {
    $usu = $_REQUEST['usu'];
    $conCod = $_REQUEST['conjunto'];
    $ope = $_REQUEST['operacion'];
    $long = $Procedure_OrdenTrabajo->SP_LongCanto($usu, $conCod, $ope);
    echo $long;
}



/* Para guardar en el temporal las partes adicionales del peldaño */
if (isset($_REQUEST['parmatTem'])) {
    $cboComp = $_REQUEST['cboComp'];
    $cbo_par_des = $_REQUEST['cbo_par_des'];
    $for_cant = $_REQUEST['for_cant'];
    $text_Ancho = $_REQUEST['txt_ancho'];
    $txt_li = $_REQUEST['txt_li'];
    $txt_espesor = $_REQUEST['txt_espesor'];
    $txt_long = $_REQUEST['text_Long'];
    $txt_PesoML = $_REQUEST['txt_PesoML'];
    $txt_pesoTU = $_REQUEST['txt_pesoTU'];
    $txt_pesoT = $_REQUEST['txt_pesoT'];
    $txt_usu = $_REQUEST['txt_usu'];
    $Procedure_OrdenTrabajo->SP_GrabarTemComPel($cbo_par_des, $cboComp, $for_cant, $text_Ancho, $txt_li, $txt_espesor, $txt_long, $txt_PesoML, $txt_pesoTU, $txt_pesoT, $txt_usu);
    echo "0::Se grabo correctamente";
}

/* Lista las partes agregadas temporalmente a un conjunto */
if (isset($_REQUEST['listConTem'])) {
    $usu = $_REQUEST['usu'];
    $operacion = $_REQUEST['ope'];
    $conjunto = $_REQUEST['conjunto'];
    $envio = $_REQUEST['listConTem'];
    $cboPartes = $Procedure_OrdenTrabajo->SP_listar_ParTempCodigo($usu, $envio, $operacion, $conjunto);
    echo $cboPartes;
}

/* Lista los detalles de las partes agregadas al conjunto temporalmente al seleccionar una */
if (isset($_REQUEST['listConTemDet'])) {
    $codCon = $_REQUEST['codCon'];
    $conjunto = $_REQUEST['conCod'];
    $ope = $_REQUEST['operador'];
    $data = $Procedure_OrdenTrabajo->SP_listar_ParTempDet($codCon, $conjunto, $ope);
    $json['cbo_par_des1'] = $data['par_in11_cod'];
    $json['cboComp1'] = $data['cmp_in11_cod'];
    $json['tedit_cantE'] = $data['ccp_in11_cant'];
    $json['tedit_LongE'] = $data['ccp_do_long'];
    $json['tedit_li'] = $data['ccp_do_li'];
    $json['tedit_espesor'] = $data['ccp_do_esp'];
    $json['tedit_PesoML'] = $data['ccp_do_ml'];
    $json['tedit_AnchE'] = $data['ccp_do_ml'];
    $json['tedit_pesoTU'] = $data['ccp_do_pesou'];
    $json['tedit_pesoT'] = $data['ccp_do_pesot'];
    echo (json_encode($json));
}

/* Funcion para eliminar la parte temporal seleccionado para el peldaño */
if (isset($_REQUEST['delTem'])) {
    $codCon = $_REQUEST['codCon'];
    $Procedure_OrdenTrabajo->SP_eliminarPartTemp($codCon);
}

/* Para editar las partes agregadas a un conjunto temporalmente peldaño */
if (isset($_REQUEST['parmatTemMod'])) {
    $codCon = $_REQUEST['codCon'];
    $cboComp = $_REQUEST['cboComp1'];
    $cbo_par_des = $_REQUEST['cbo_par_des1'];
    $for_cant = $_REQUEST['tedit_cantE'];
    $text_Ancho = $_REQUEST['txt_ancho'];
    $txt_li = $_REQUEST['tedit_li'];
    $txt_espesor = $_REQUEST['tedit_espesor'];
    $txt_long = $_REQUEST['tedit_LongE'];
    $txt_PesoML = $_REQUEST['tedit_PesoML'];
    $txt_pesoTU = $_REQUEST['tedit_pesoTU'];
    $txt_pesoT = $_REQUEST['tedit_pesoT'];
    $txt_usu = $_REQUEST['txt_usu'];
    $conCod = $_REQUEST['conCod'];
    $ope = $_REQUEST['operador'];
    $Procedure_OrdenTrabajo->SP_modificarPartes($ope, $conCod, $codCon, $cbo_par_des, $cboComp, $for_cant, $text_Ancho, $txt_li, $txt_espesor, $txt_long, $txt_PesoML, $txt_pesoTU, $txt_pesoT, $txt_usu);
}

/* Lista el detalle de un componente para peldaño segun voy eligiendo */
if (isset($_REQUEST['BuscaComp'])) {
    $compel = $_REQUEST['compel'];
    $cons = $db->consulta("select * from componentespel WHERE cmp_in1_est !=0 AND cmp_in11_cod ='$compel'");
    $data = $db->fetch_assoc($cons);
    if ($_REQUEST['BuscaComp'] == '1') {
        $json['txt_li'] = $data['cmp_do_l1'];
        $json['txt_espesor'] = $data['cmp_do_esp'];
        $json['txt_PesoML'] = $data['cmp_do_pml'];
        $json['txt_ancho'] = $data['cmp_do_anch'];
        $json['sp_posini'] = $pos_real;
        $json['sp_postot'] = $con_pos;
    } else {
        $json['tedit_li'] = $data['cmp_do_l1'];
        $json['tedit_espesor'] = $data['cmp_do_esp'];
        $json['tedit_largoE'] = $data['cmp_do_anch'];
        $json['tedit_PesoML'] = $data['cmp_in11_pml'];
        $json['sp_posini'] = $pos_real;
        $json['sp_postot'] = $con_pos;
    }
    echo (json_encode($json));
}

/* Lista el detalle de un componente para peldaño segun voy eligiendo pero en editando */
if (isset($_REQUEST['BuscaComp2'])) {
    $compel = $_REQUEST['compel2'];
    $cons = $db->consulta("select * from componentespel WHERE cmp_in1_est !=0 AND cmp_in11_cod ='$compel'");
    $data = $db->fetch_assoc($cons);
    $json['tedit_li'] = $data['cmp_do_l1'];
    $json['tedit_espesor'] = $data['cmp_do_esp'];
    $json['tedit_AnchE'] = $data['cmp_do_anch'];
    $json['tedit_PesoML'] = $data['cmp_do_pml'];
    $json['sp_posini'] = $pos_real;
    $json['sp_postot'] = $con_pos;
    echo (json_encode($json));
}

/* Funcion para listar los componentes para peldaños segun la parte a agregar */
if (isset($_REQUEST['listConPel'])) {
    $codPart = $_REQUEST['codPar'];
    $cad = $Procedure_OrdenTrabajo->SP_listar_ComPel($codPart);
    echo $cad;
}

/* Funcion para listar los componentes para peldaños */
if (isset($_REQUEST['listConPelAll'])) {
    $cad = $Procedure_OrdenTrabajo->SP_listar_ComPelAll();
    echo $cad;
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
    $Procedure_OrdenTrabajo->SP_eliminarPartFisicas($cocConCod);
}


/* Sentencia que sirve para la paginacion del formulario de la Orden de Trabajo */
if (isset($_GET['m'])) {
    $pag = $_GET['pag'];
    $cod_ot = $_GET['id'];
    $res_pos = $db->consulta("SELECT * FROM orden_trabajo WHERE ort_in1_est = '1' ORDER BY ort_ch10_num DESC");
    $con_pos = 0;
    $post_ot = 0;
    $row_val = 0;
    while ($row_pos = $db->fetch_assoc($res_pos)) {
        $row_val = $row_pos['ort_ch10_num'];
        if ($cod_ot == $row_val) {
            $post_ot = $con_pos;
        }
        $con_pos++;
    }
    if ($pag == 'none') {
        $cod = $_GET['id'];
    } else {
        if ($pag == "prev") {
            if ($post_ot - 1 == '-1') {
                $post_ot = $post_ot;
            } else {
                $post_ot = $post_ot - 1;
            }
        }
        if ($pag == "next") {
            if ($post_ot + 1 > $con_pos - 1) {
                $post_ot = $post_ot;
            } else {
                $post_ot = $post_ot + 1;
            }
        }
        if ($pag == "first") {
            $post_ot = "0";
        }
        if ($pag == "last") {
            $post_ot = $con_pos - 1;
        }
        $res_pag = $db->consulta("SELECT * FROM orden_trabajo WHERE ort_in1_est = '1' ORDER BY ort_ch10_num DESC LIMIT $post_ot , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $cod = $row['ort_ch10_num'];
    }
    $pos_real = $post_ot + 1;
    $cons = $db->consulta("SELECT ot.*, (SELECT cob_vc50_cod FROM conjunto c, conjunto_orden_trabajo ct
                           WHERE ct.con_in11_cod=c.con_in11_cod AND ort_ch10_num = '$cod' LIMIT 0,1) AS cob_vc50_cod
                           FROM orden_trabajo ot WHERE ort_ch10_num = '$cod'");
    $data = $db->fetch_assoc($cons);
    $json['txt_nro'] = $cod;
    $json['txt_ort_cod'] = $data['ort_vc20_cod'];
    $json['cbo_razoncliente'] = $data['cli_in11_cod'];
    $json['cbo_proyecto'] = $data['pyt_in11_cod'];
    $json['txt_fech_emi'] = $data['ort_da_fechemi'];
    $json['txt_nro_ordencompra'] = $data['ort_vc11_nroordencom'];
    $json['txt_fech_ordencompra'] = $data['ort_da_fechordencom'];
    $json['txt_nro_presupuesto'] = $data['ort_vc11_numpres'];
    $json['txt_fech_ini'] = $data['ort_da_fechinicio'];
    $json['txt_fech_ent'] = $data['ort_da_fechentre'];
    $json['txt_portante'] = $data['cob_do_disport'];
    $json['txt_arriostre'] = $data['cob_do_disarri'];
    $json['cboacabado'] = $data['tpa_vc4_cod'];
    $json['cbo_bustipconj'] = $data['con_vc11_codtipcon'];
    $json['sp_codfer'] = $data['cob_vc50_cod'];
    $json['txt_CodProd'] = $data['cob_vc50_cod'];
    $json['cboEspCalDet'] = $data['ort_vc50_sDet'];
    $json['cboEspCalSol'] = $data['ort_vc50_sSol'];

    $json['sp_posini'] = $pos_real;
    $json['sp_postot'] = $con_pos;
    echo (json_encode($json));
}
//*************************************** MANTENIMIENTO DE CONJUNTO TEMPORAL *************************************************************

/* Recuperando los datos del Conjunto del formulario BusConjunto */
if (isset($_POST['a'])) {
    $ope = $_POST['a'];
    $codCon = $_POST['txt_busconj_cod'];
    $countplano = $_POST['countplano'];
    $cbo_fermar = $_POST["codfer"];
    $txt_usu = intval($_POST["txt_usu"]);
    $txt_plano = $_POST['txt_busplano'];
    $txt_marca = $_POST['txt_busmarca'];
    $txt_cant = intval($_POST['txt_buscant']);
    $txt_largo = intval($_POST['txt_buslargo']);
    $txt_ancho = intval($_POST['txt_busancho']);
    $txt_obs = $_POST['txt_busobs'];
    if ($_POST['chk_busdetalle'] == 'true')
        $chk_detalle = '1'; else
        $chk_detalle = '0';
    if ($error == '') {
        $chk_detalle=intval($chk_detalle);
        //$con = ($ope == '2') ? 0 : 1;
        if ($ope == '2') {
            /* Sentencia para Modificar el conjunto temporal de la tabla conjunto */
            $Procedure_OrdenTrabajo->SP_ModificaTemConjunto($codCon, $txt_usu, $cbo_fermar, $txt_plano, $txt_marca, $txt_cant, $txt_largo, $txt_ancho, $chk_detalle, $txt_obs);
            echo $codCon . '::Se Actualizo correctamente El Conjunto.';
        } else if ($ope == '1') {
            /* Sentencia para Grabar el conjunto temporal de la tabla temporal_conjunto */
            $Procedure_OrdenTrabajo->SP_GrabatemConjunto($txt_usu, $cbo_fermar, $txt_plano, $txt_marca, $txt_cant, $txt_largo, $txt_ancho, $chk_detalle, $txt_obs, $countplano);
            echo '1:: Se Ingreso Correctamente los datos del Conjunto.';
        }
    } else {
        echo '0::' . $error;
    }
}
/* Lista los Conjuntos Temporales */
if (isset($_POST['codtemCon'])) {
    $codtemCon = intval($_POST['codtemCon']);
    $data = $Procedure_OrdenTrabajo->SP_Lista_temporalConjunto($codtemCon);
    echo $data['tco_in11_cod'] . '::' . $data['tco_vc50_cob'] . '::' . $data['tco_vc20_nroplano'] . '::' . $data['tco_vc20_marcli'] . '::' . $data['tco_in11_cant'] . '::' . $data['tco_do_largo'] . '::' . $data['tco_do_ancho'] . '::' . $data['tco_vc11_codtipcon'] . '::' . $data['tco_in1_detalle'] . '::' . $data['tco_vc50_obser'];
}
/* Sentencia que nos sirve para eliminar los Conjuntos Temporales */
if (isset($_POST['delCon'])) {
    $codCon = explode(",", $_POST['codCon']);
    $cod_usu = $_POST['codus'];
    for ($i = 0; $i < count($codCon) - 1; $i++) {
        $Procedure_OrdenTrabajo->SP_EliminatemConjunto($codCon[$i], $cod_usu);
    }
}
/* Sentencia Para mostrar las partes y materiales en la tabla temporal dependiendo del conjunto base */
if (isset($_POST['GrabaConTemp'])) {
    $cod_OT = $_POST['codCon'];
    $codusu = $_POST['codus'];
    $Procedure_OrdenTrabajo->SP_MostrarTemConjunto($cod_OT, $codusu);
}
/* Sentencia para mostrar el Grabado del conjunto base a la tabla temporal_conbase  */
if (isset($_POST['GrabaBaseTemp'])) {
    $cod_CB = $_POST['codBase'];
    $codusu = $_POST['codus'];
    $Procedure_OrdenTrabajo->SP_GrabaConBaseTemp($cod_CB, $codusu);
}
//*************************************** MANTENIMIENTO DE CONJUNTO BASE DEL CONJUNTO *************************************************************
if (isset($_POST['parmat'])) {
    /* Recuperando los datos de las Partes y Materiales del Conjunto Base */
    $codtempor = strip_tags(trim($_POST['txt_parte_temp']));
    $txt_usu = strip_tags((trim($_POST['txt_usu'])));
    $txt_parte_cod = strip_tags(trim($_POST['txt_codPar']));
    $txt_mat_cod = strip_tags(trim($_POST['txt_codMate']));
    if ($error == '') {
        $cb = ($codtempor == '') ? 0 : 1;
        if ($cb == 1) {
            /* Sentencia para Modificar las Partes y Materiales del conjunto base en la tabla temporal_conbase */
            $Procedure_OrdenTrabajo->SP_ModificaConBaseTemp($codtempor, $txt_usu, $txt_parte_cod, $txt_mat_cod);
            echo $codtempor . ':: Se actualizo correctamente las Partes';
        }
    }
}
/* Sentencia para Listar los Conjuntos temporales al momento de editar un conjunto seleccionado */
if (isset($_GET['ListaConjunto'])) {
    $codtemCon = $_GET['codConjunto'];
    $data = $Procedure_OrdenTrabajo->SP_Lista_temporalConjunto($codtemCon);
    $json['txt_busconj_cod'] = $data['tco_in11_cod'];
    $json['txt_busplano'] = $data['tco_vc20_nroplano'];
    $json['txt_busmarca'] = $data['tco_vc20_marcli'];
    $json['txt_buscant'] = $data['tco_in11_cant'];
    $json['cbo_busfermar'] = $data['tco_vc50_cob'];
    $json['txt_buslargo'] = $data['tco_do_largo'];
    $json['txt_busancho'] = $data['tco_do_ancho'];
    $json['chk_busdetalle'] = $data['tco_in1_detalle'];
    $json['txt_busobs'] = $data['tco_vc50_obser'];
    echo (json_encode($json));
}
/* Sentencia para Listar las Partes dependiendo la descripion de la Parte */
if (isset($_GET['BuscaPartes'])) {
    $codtem = $_GET['codTemp'];
    $data = $Procedure_OrdenTrabajo->SP_Lista_Partes($codtem);
    $json['txt_codPar'] = $data['par_in11_cod'];
    $json['cbo_descPar'] = $data['par_in11_cod'];
    $json['txt_codMate'] = $data['mat_vc3_cod'];
    $json['cbo_descMate'] = $data['mat_vc3_cod'];
    $json['txt_largoMate'] = $data['mat_do_largo'];
    $json['txt_anchoMate'] = $data['mat_do_ancho'];
    $json['txt_espesorMate'] = $data['mat_do_espesor'];
    $json['txt_diameMate'] = $data['mat_do_diame'];
    echo (json_encode($json));
}
/* Sentencia para Listar los Materiales dependiendo la descripcion del Material */
if (isset($_GET['BuscaMaterial'])) {
    $codtem = $_GET['cod_mat'];
    $data = $Procedure_OrdenTrabajo->SP_Lista_Material($codtem);
    $json['txt_codMate'] = $data['mat_vc3_cod'];
    $json['cbo_descMate'] = $data['mat_vc3_cod'];
    $json['txt_largoMate'] = $data['mat_do_largo'];
    $json['txt_anchoMate'] = $data['mat_do_ancho'];
    $json['txt_espesorMate'] = $data['mat_do_espesor'];
    $json['txt_diameMate'] = $data['mat_do_diame'];
    echo (json_encode($json));
}
/* Sentencia para Eliminar la tabla temporal_conbase segun el codigo del usuario */
if (isset($_POST['DelTemporal'])) {
    $cod = $_POST['cod'];
    $Procedure_OrdenTrabajo->SP_EliminaTemporal($cod);
}
/* Setencia para recuperar el ultimo registro grabado del conjunto de la tabla temporal_conjunto */
if (isset($_GET['RecuperaDatos'])) {
    $usu = $_GET['usu'];
    $data = $Procedure_OrdenTrabajo->SP_RecuperaDatos($usu);
    $plano = strlen($data['tco_vc20_nroplano']);

    $json['txt_busplano'] = $data['tco_vc20_nroplano'];
    $json['txt_busmarca'] = $data['tco_vc20_nroplano'];
    $json['txt_marcaUL'] = $data['tco_vc20_marcli'];
    $json['cbo_busfermar'] = $data['tco_vc50_cob'];
    echo (json_encode($json));
}

# --Jean-- 16/08/2012
//Recupera los archivos adjuntados
if (isset($_POST['transExcelOT'])) {
    $codusu = intval($_POST['codUsu']);
    $codConjuntoBase = $_POST['codConjunto'];
    //Obtiene la ruta y el nombre del archivo mas la extension
    $file = '../../../Reportes/Orden_trabajo/formatoOT_' . $codusu . '.xls';
    SP_LeeExcel($file, $codusu, $codConjuntoBase);
}

//Elimina al archivo
if (isset($_POST['ExcelDelOT'])) {
    $codusu = $_POST['codUsu'];
    //Obtiene la ruta y el nombre del archivo mas la extension
    $file = '../../../Reportes/Orden_trabajo/formatoOT_' . $codusu . '.xls';
    unlink($file);
}

//Recupera los archivos adjuntados
function SP_LeeExcel($file, $codusu, $codConjuntoBase) {
    $Procedure_OrdenTrabajo = new Procedure_OrdenTrabajo();
    $Obs = 0;
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('CP1251');
    $data->read($file); //Lee la ruta y el nombre del archivo adjuntado
    error_reporting(E_ALL ^ E_NOTICE);
    $columna = $data->sheets[0]['cells'][1][1];

    if ($columna == "Plano") {
        //Registra el excel
        for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
            $nomPlano = strtoupper($data->sheets[0]['cells'][$i][1]);
            if ($nomPlano !== '') { //Valida filas vacias
                $nomMarca = strtoupper($data->sheets[0]['cells'][$i][2]);
                $desc = strtoupper($data->sheets[0]['cells'][$i][6]);
                $validaMarca = $Procedure_OrdenTrabajo->SP_ValidarMarcaCliente($nomPlano, $nomMarca, $codusu);
                $validaDesc = $Procedure_OrdenTrabajo->SP_ValidarDescripcion($desc);
                if ($validaMarca == 0) {
                    if ($validaDesc == 1) {// si existe +k+D
                        $Procedure_OrdenTrabajo->SP_GrabatemConjuntoExcel($codusu, $codConjuntoBase, strtoupper($data->sheets[0]['cells'][$i][1]), strtoupper($data->sheets[0]['cells'][$i][2]), strtoupper($data->sheets[0]['cells'][$i][3]), strtoupper($data->sheets[0]['cells'][$i][4]), strtoupper($data->sheets[0]['cells'][$i][5]), 0, strtoupper($data->sheets[0]['cells'][$i][6]), strtoupper($data->sheets[0]['cells'][$i][7]));
                        $Obs = "1::Los conjuntos se registraron correctamente";
                    } else {
                        $Obs = "2::La descripcion " . $desc . " no existe en el sistema";
                    }
                } else {
                    $Obs = "3::La marca " . $nomMarca . " no se registró, porque está duplicada !";
                }
            }
        }
        echo $Obs;
    }   
}

//Validando la tabla conjuntos temporal
if(isset($_REQUEST['valtempconju'])){
    $usu = $_REQUEST['usu'];
    $consval = $db->consulta("SELECT COUNT(*) AS 'count' FROM temporal_conjunto WHERE usu_in11_cod = '$usu';");
    $rowval = $db->fetch_assoc($consval);
    echo $rowval['count'] ;
}

/* elimina las tablas temporales segun el codigo del usuario */
if (isset($_REQUEST['listOTall'])) {
    echo $Procedure_OrdenTrabajo->SP_ListarOTall();
}
?>