<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_ingresoProd.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 19/03/2012
  | @Modificado por:    Frank A. Peña Ponce
  | @Fecha de la ultima modificacion: 26/03/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Página en donde se ingresa los proceso de los items de produccion
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
$consProc = $db->consulta("SELECT pro_in11_cod,pro_vc50_desc FROM proceso WHERE pro_in11_cod IN($cadProc) AND pro_in1_tip = 1 AND pro_in1_est !=0");
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
        <!-- caja de textos donde se guardan los atos del items y proceso -->        
        <input type="text" id="txt_conjunto" style="display: none;"><!-- guarda el codigo del conjunto -->
        <input type="text" id="txt_procesoID" style="display: none;"><!-- guarda el codigo del proceso -->
        <input type="text" id="txt_items" style="display: none;"><!-- guarda el codigo del item agregado -->
        <input type="text" id="txt_codSuper" style="display: none;" value="<?php echo $arrSession[0]; ?>"><!-- guarda el codigo del Inspector -->

        <!-- Session donde se encuentra el formulario para el ingreso de los codigos -->
        <form name="form" method="post">

            <!-- Titulo de la area / Muestra el area, proceso y nombre del inspector lblProcesoDes -->       
            <table>
                <tr><td><label class="clsProc"><?php echo $area . ':'; ?>
                        </label></td><td><select id="cboProc" style="width: auto; font-size: 9px;">
                                <option value="0">SELECIONE</option><?php while ($respProc = $db->fetch_assoc($consProc)): ?><option value="<?php echo $respProc['pro_in11_cod']; ?>">
    <?php echo $respProc['pro_vc50_desc'] ?></option><?php endwhile; ?>
                        </select></td></tr>
                <tr><td colspan="2"><label style="font-size: 12px; position: relative; float: left;"><b><?php echo $arrSession[3]; ?></b></label></td></tr>
            </table>

            <!-- Session donde se igresa el codigo del item -->
            <label style="font-weight: bold; color: #010101;">Ingreso de Items</label>
            <li>
                <label for="txt_prod_items" class="LetraPDA">&nbsp;Barras&nbsp;&nbsp;:&nbsp;</label><input type="text" name="txt_prod_items" id="txt_prod_items" style="width: 1px; height: 1px;" onkeypress="enter(event)"/><!-- Codigo de barras del codigo del item -->
            </li>
            <li>
                <label class="LetraPDA">&nbsp;Marca&nbsp;&nbsp;&nbsp;:&nbsp;</label><label id="lblMarca" style="font-weight: bold;"></label><!-- OT del items -->
            </li>
            <li>
                <label class="LetraPDA">&nbsp;Ítem&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;</label><label id="lblCore" style="font-weight: bold;"></label><!-- Correlativo del items -->
            </li>
            <li>
                <label class="LetraPDA">&nbsp;Lote&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;</label><label id="lblLote" style="font-weight: bold;"></label><!-- OT del items -->
            </li>
            <li>
                <label class="LetraPDA">&nbsp;OT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;</label><label id="lblOT" style="font-weight: bold;"></label><!-- OT del items -->
            </li>                
            <!-- Session donde se igresa el codigo del operario -->
            <br /><label style="font-weight: bold; color: #010101;">Ingreso del Operario</label>
            <li>
                <label  for="txt_prod_codOpe" class="LetraPDA">Código&nbsp;&nbsp;:&nbsp;</label><input type="text" name="txt_prod_codOpe" id="txt_prod_codOpe" style="width: 65px;" onkeypress="enterOpe(event)" maxlength="8"/>
            </li>               
            <label id="lblNomOpe" style="font-weight: bold; font-size: 10px;"></label><!-- Nombre del Operario -->
        </form>

        <!-- Session donde se encuentra el cerrar session y cambiar los procesos -->
        <div align="left" style="position: relative; float: left;">           
            <a href="login.php" class="button"><span style="font-weight: bold; margin-left: 0px;  width: 180px; text-align: center;" class="CerrarPDA">Cerrar Secci&oacute;n</span></a>
        </div>
    </body>
</html>
<?php //echo '<script type="text/javascript" src="js/Registro_Produccion/MAN_ingresoProd.js' . '?' . filemtime('js/Registro_Produccion/MAN_ingresoProd.js') . '"</script>';  ?>
<script type="text/javascript" src="js/Registro_Produccion/MAN_ingresoProd.js"></script>