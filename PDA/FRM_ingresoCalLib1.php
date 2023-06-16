<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_ingresoCalLib1.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 26/03/2012
  | @Modificado por:    Frank A. Peña Ponce
  | @Fecha de la ultima modificacion: 26/03/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Página en donde se ingresa los proceso de los items de calidad
 */
session_start();
include_once '../PHP/FERConexion.php';

//Validando que exista el login, si no existe el login lo envia a loguearse
if (!isset($_SESSION['UserPDA'])) {
    echo "<script type='text/javascript'>window.location = 'login.php';</script>";
}

//Variable de BD
$db = new MySql();

//Convirtiendo en array la session
$arrSession = explode('::', $_SESSION['UserPDA']);

//Calculando el area
$area = '';
($arrSession[1] == '1') ? $area = 'PRODUCCIÓN' : $area = 'CALIDAD';

//Obteniendo el codigo de las areas a ingresar items
$proc = '';
$cadProc = '';

//Concatenando los codigo de los procesos con apostrofes
$proc = explode(',', $arrSession[2]);
for ($i = 0; $i <= count($proc) - 2; $i++) {
    $cadProc = $cadProc . "'" . $proc[$i] . "',";
}

//Retirando la ultima coma
$cadProc = substr($cadProc, 0, strlen($cadProc) - 1);
//Listando los procesos deacuerdo a los codigo de los procesos
$consProc = $db->consulta("SELECT pro_in11_cod,pro_vc50_desc FROM proceso WHERE pro_in11_cod IN($cadProc) AND pro_in1_tip = 2 AND pro_in1_est !=0");
$respProc = $db->fetch_assoc($consProc);
//Especificaciones
$cad = '';
$cad.="<option value='ANSI / NAAM MBG533'>ANSI / NAAM MBG533</option>";
//Llenando los combos de variacion
$cad = '';
$cad.= "<option value=''>Sin especificar</option><option value='3'>3</option><option value='2'>2</option><option value='1'>1</option><option value='0'>0</option><option value='-1'>-1</option><option value='-2'>-2</option><option value='-3'>-3</option>";
?>

<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN"
    "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head><!-- Improtando las metas para el PDA -->
        <meta http-equiv="Content-Type" content="application/xhtml+xml;charset=utf-8" />
        <meta http-equiv="Cache-Control" content="max-age=3600"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <script type="text/javascript" src="../Script/jquery.js"></script>
    </head>   
    <link rel="stylesheet" type="text/css" href="css/style_login.css"/>
    <body>

        <!-- Titulo de la area / Muestra el area, proceso y nombre del inspector -->       
        <label style="font-size: 12px; position: relative; float: left; color: #010101;">
            <b>&nbsp;&nbsp;&nbsp;<?php echo $area . ' > '; ?><label id="lblProcesoDes" style="color: #5776fc;"><?php echo $respProc['pro_vc50_desc']; ?></label>
            </b></label><label style="font-size: 12px; position: relative; float: right;"><b>
                <?php echo $arrSession[3]; ?></b></label>

        <!-- caja de textos donde se guardan los atos del items y proceso -->        
        <input type="text" id="txt_procesoID" style="display: none;" value="<?php echo $respProc['pro_in11_cod']; ?>"><!-- guarda el codigo del proceso -->
        <input type="text" id="txt_items" style="display: none;"><!-- guarda el codigo del item agregado -->
        <input type="text" id="txt_codSuper" style="display: none;" value="<?php echo $arrSession[0]; ?>"><!-- guarda el codigo del Inspector -->        

        <!-- Session donde se encuentra el formulario para el ingreso de los codigos -->
        <form name="form" method="post">

            <!-- Session donde se igresa el codigo del item -->
            <label style="font-weight: bold; color: #010101;">Ingreso de Items</label>
                <li>
                    <label for="txt_prod_items" class="LetraPDA">Barras:&nbsp;&nbsp;&nbsp;</label><input type="text" name="txt_prod_items" id="txt_prod_items" style="width: 1px; height: 1px;" onkeypress="enter(event)"/><!-- Codigo de barras del codigo del item -->
                </li>
                <li>
                    <label class="LetraPDA">Marca:&nbsp;&nbsp;&nbsp</label><label id="lblMarca" style="font-weight: bold;"></label><!-- OT del items -->
                </li>
                <li>
                    <label class="LetraPDA">Ítem:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><label id="lblCore" style="font-weight: bold;"></label><!-- Correlativo del items -->
                </li>
                <li>
                    <label class="LetraPDA">Lote:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><label id="lblLote" style="font-weight: bold;"></label><!-- OT del items -->
                </li>
                <li>
                    <label class="LetraPDA">OT:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><label id="lblOT" style="font-weight: bold;"></label><!-- OT del items -->
                </li>
             <!-- Session donde se igresa los variables -->
            <br /><label style="font-weight: bold; color: #010101;">Ingreso de tolerancias</label>                       
                <label for="cbo_cal_varLarg" style="font-weight: bold; color: #010101; font-size: 14px;">Largo</label><label id="lblLargoVar"></label>
                <li>
                    <label for="cbo_cal_varLarg" class="LetraPDA">Variación:&nbsp;&nbsp;&nbsp;</label><select id="cbo_cal_varLarg" name="cbo_cal_varLarg" style="width: 40px;"><?php echo $cad; ?></select>
                </li>
                <label for="cbo_cal_varLong" style="font-weight: bold; color: #010101; font-size: 14px;">Longuitud</label><label id="lblLongVar"></label>
                <li>
                    <label for="cbo_cal_varLong" class="LetraPDA">Variación:&nbsp;&nbsp;&nbsp;</label><select id="cbo_cal_varLong" name="cbo_cal_varLong" style="width: 40px;"><?php echo $cad; ?><</select>
                </li>
            <!-- Session donde se igresa el codigo del supervisor para confiarma el item -->            
            <label style="font-weight: bold; color: #010101;">Supervisor</label>
                <li>
                    <label  for="txt_cal_super" class="LetraPDA">Código:&nbsp;</label><input type="text" name="txt_cal_super" id="txt_cal_super" style="width: 130px;" onkeypress="enterSup(event)" maxlength="8"/>
                </li>
        </form>
        
        <!-- Session donde se encuentra el cerrar session y cambiar los procesos -->
        <div align="left" style="position: relative; float: left;">
            <a href="login.php" class="button"><span style="font-weight: bold; margin-left: 0px;  width: 190px;" class="CerrarPDA">Cerrar Secci&oacute;n</span></a>
        </div>
    </body>
</html>
<?php //echo '<script type="text/javascript" src="js/Registro_Produccion/MAN_ingresoProd.js' . '?' . filemtime('js/Registro_Produccion/MAN_ingresoProd.js') . '"</script>';  ?>
<script type="text/javascript" src="js/Inspeccion_Calidad/Final/Final1.js"></script>