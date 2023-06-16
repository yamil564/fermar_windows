<?php

/*
  |---------------------------------------------------------------
  | PHP SP_Herramienta_Analisis.php
  |---------------------------------------------------------------
  | @Autor: Jean Guzman Abregu
  | @Fecha de creacion: 28/03/2011
  | @Modificado por: Frank Peña Ponce,Jean Guzman Abregu
  | @Fecha de la ultima modificacion: 16/08/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina en donde se encuentra todas las funciones de mantenimientos del formulario tipo FRM_RPT_Inspeccion.php
 */

/* Clase para la Pagina FRM_Conjunto_Inspeccionado_Habilitado.php */

class Procedure_Herramientas_Analisis {
    
    #funcion que retorna las areas 
    function SP_LisArea() {
        $db = new MySql();
        $cons = $db->consulta("SELECT pro_in11_cod,pro_vc50_desc FROM proceso WHERE pro_in1_tip =1 AND pro_in1_est !=0");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['pro_in11_cod'] . '">' . $resp['pro_vc50_desc'] . '</option>';
        }
        return $cad;
    }
    
    
    #funcion que retorna los anios
    function SP_LisAnio() {
        date_default_timezone_set('America/Lima');
        $anio= date('Y');
        $cad = '';
        for($i=$anio;$i>=2010;$i--){
        //for($i=2010;$i<=$anio;$i++){
            $cad.= '<option value="' . $i . '">' . $i . '</option>';
        }
        return $cad;
    }
    
    /* Funcion para listar todas las ordenes de produccion */
    function SP_lista_Orden_Trabajo() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT ort_vc20_cod FROM orden_produccion WHERE orp_in1_est != 0 ORDER BY orp_in11_numope DESC;");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['ort_vc20_cod'] . '">' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }

    /* Lista las OT que tienen inpeccion de calidad Armado*/

    function SP_lista_Orden_Calidad_Armado() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT orp_in11_numope, ort_vc20_cod FROM orden_produccion WHERE orp_in1_est!=0;");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['orp_in11_numope'] . '">' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }

     /* Lista las OT que tienen inpeccion de calidad Armado*/

    function SP_lista_Orden_Calidad_Final() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT orp_in11_numope, ort_vc20_cod FROM orden_produccion WHERE orp_in1_est!=0;");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['orp_in11_numope'] . '">' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }

     /* Lista las OT que tienen inpeccion de calidad Armado*/

    function SP_lista_Orden_Calidad_Soldado() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT isc_in11_cod, op.ort_vc20_cod FROM inspeccion_calidad isc, orden_produccion op WHERE op.orp_in11_numope=isc.isc_vc20_ot AND pro_in11_cod = 5");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['isc_in11_cod'] . '">' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }
    
/* Lista las OT que tienen inpeccion de calidad Armado*/

    function SP_lista_Orden_Calidad_Detalle() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT isc_in11_cod, op.ort_vc20_cod FROM inspeccion_calidad isc, orden_produccion op WHERE op.orp_in11_numope=isc.isc_vc20_ot AND pro_in11_cod = 4");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['isc_in11_cod'] . '">' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }
    /* Funcion que me lista los clientes */

    function SP_lista_Clientes() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT cli_in11_cod, cli_vc20_razsocial FROM cliente WHERE cli_in1_est !=0");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['cli_in11_cod'] . '">' . $resp['cli_vc20_razsocial'] . '</option>';
        }
        return $cad;
    }

    /* Funcion que me lista los proyectos */

    function SP_lista_Proyecto() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT pyt_in11_cod, pyt_vc150_nom FROM proyecto WHERE pyt_in1_est !=0");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['pyt_in11_cod'] . '">' . $resp['pyt_vc150_nom'] . '</option>';
        }
        return $cad;
    }

    /* Funcion para listar todas las ordenes de inspeccion */

    function SP_lista_Orden_Inspeccion() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT orp_in11_numope, ort_vc20_cod FROM orden_produccion WHERE orp_in1_est !=0 ORDER BY orp_in11_numope DESC");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value=' . $resp['orp_in11_numope'] . '>' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }
    
    /* Funcion que lista los procesos */
    function SP_lista_ProcesoDirario(){
        $db = new MySQL();
        $cons = $db->consulta("SELECT pro_in11_cod, pro_vc50_desc FROM proceso WHERE pro_in1_tip = '1' ORDER BY pro_in11_cod ASC");        
        $cad = '';
        $cad = '<option value="0">TODOS</option>';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value=' . $resp['pro_in11_cod'] . '>' . $resp['pro_vc50_desc'] . '</option>';
        }
        return $cad;
    }

    /* Funcion para listar todas las ordenes de produccion segun rango de fechas */

    function SP_lista_Orden_Trabajo_Rango($fechaI, $fechaF) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT ort_vc20_cod FROM orden_produccion WHERE orp_in1_est != 0 AND orp_da_fech between '$fechaI' AND '$fechaF'");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['ort_vc20_cod'] . '">' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }
    
     /* Funcion para listar todas las ordenes de produccion segun rango de fechas */

    function SP_lista_OT_InsCalArm($fechaI, $fechaF) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT orp_in11_numope, ort_vc20_cod FROM orden_produccion WHERE ort_vc20_cod IN 
                               (SELECT DISTINCT ort_vc20_cod FROM detalle_inspeccion_calidad WHERE pro_in11_cod = '11'
                               AND dic_dt_fech BETWEEN ('$fechaI') AND ('$fechaF')) AND orp_in1_est !=0");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['orp_in11_numope'] . '">' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }

    /* Lista todas las orden de trabajo con inspeccion de calidad */
     function SP_lista_OT_InsCalAllArm() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT orp_in11_numope, ort_vc20_cod FROM orden_produccion WHERE ort_vc20_cod IN 
                               (SELECT DISTINCT ort_vc20_cod FROM detalle_inspeccion_calidad WHERE pro_in11_cod = '11')
                               AND orp_in1_est !=0");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['orp_in11_numope'] . '">' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }

      /* Funcion para listar todas las ordenes de produccion segun rango de fechas */

    function SP_lista_OT_InsCalSol($fechaI, $fechaF) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT isc_in11_cod, op.ort_vc20_cod FROM inspeccion_calidad isc, orden_produccion op
                               WHERE op.orp_in11_numope=isc.isc_vc20_ot AND pro_in11_cod = 5 AND isc_da_apert BETWEEN '$fechaI' AND '$fechaF'");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['isc_in11_cod'] . '">' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }

    /* Lista todas las orden de trabajo con inspeccion de calidad */
     function SP_lista_OT_InsCalAllSol() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT isc_in11_cod, op.ort_vc20_cod FROM inspeccion_calidad isc, orden_produccion op
                               WHERE op.orp_in11_numope=isc.isc_vc20_ot AND pro_in11_cod = 5");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['isc_in11_cod'] . '">' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }

          /* Funcion para listar todas las ordenes de produccion segun rango de fechas */

    function SP_lista_OT_InsCalDet($fechaI, $fechaF) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT isc_in11_cod, op.ort_vc20_cod FROM inspeccion_calidad isc, orden_produccion op
                               WHERE op.orp_in11_numope=isc.isc_vc20_ot AND pro_in11_cod = 4 AND isc_da_apert BETWEEN '$fechaI' AND '$fechaF'");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['isc_in11_cod'] . '">' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }

             /* Funcion para listar todas las ordenes de produccion segun rango de fechas */

    function SP_lista_OT_InsCalFin($fechaI, $fechaF) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT orp_in11_numope, ort_vc20_cod FROM orden_produccion WHERE orp_da_fech BETWEEN ('$fechaI') AND ('$fechaF') AND orp_in1_est!=0;");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['orp_in11_numope'] . '">' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }

    /* Lista todas las orden de trabajo con inspeccion de calidad */
     function SP_lista_OT_InsCalAllFin() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT orp_in11_numope, ort_vc20_cod FROM orden_produccion WHERE orp_in1_est!=0;");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['orp_in11_numope'] . '">' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }

    /* Lista todas las orden de trabajo con inspeccion de calidad */
     function SP_lista_OT_InsCalAllDet() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT isc_in11_cod, op.ort_vc20_cod FROM inspeccion_calidad isc, orden_produccion op
                               WHERE op.orp_in11_numope=isc.isc_vc20_ot AND pro_in11_cod = 4");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['isc_in11_cod'] . '">' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }

    /* Funcion que lista por rango de fecha las inspecciones de produccion */

    function SP_lista_InspProd_Rango($fechaI, $fechaF) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT orp_in11_numope, ort_vc20_cod FROM orden_produccion WHERE orp_da_fech BETWEEN ('$fechaI') AND ('$fechaF') AND orp_in1_est!=0;");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['orp_in11_numope'] . '">' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }
    
    function SP_lista_InspProd_RangoReg($fechaI, $fechaF) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT orp_in11_numope, ort_vc20_cod FROM orden_produccion WHERE orp_da_fech BETWEEN ('$fechaI') AND ('$fechaF') AND orp_in1_est!=0;");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['orp_in11_numope'] . '">' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }

    /* Funcion que lista las inspecciones de produccion */

    function SP_lista_InspProd_All() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT orp_in11_numope, ort_vc20_cod FROM orden_produccion WHERE orp_in1_est !=0 ORDER BY orp_in11_numope DESC");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value=' . $resp['orp_in11_numope'] . '>' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }
    
    /* Funcion para listar las configuraciones del reporte de area general */
    function SP_lista_ConfigArea(){
        $db = new MySQL();$cad = "";
        $cons = $db->consulta("SELECT reac_in11_cod, CONCAT(reac_vc80_des,' - ',DATE_FORMAT(reac_date_fec,'%d/%m/%Y')) AS descript FROM reporte_area_cabe WHERE reac_in1_sta !=0 ORDER BY reac_in11_cod DESC");
        $cad.="<option value=0>Seleccione Configuraci&oacute;n</option>";
        while($row = $db->fetch_assoc($cons)){
            $cad.="<option value=".$row['reac_in11_cod'].">".$row['descript']."</option>";
        }
        return $cad;
    }

}