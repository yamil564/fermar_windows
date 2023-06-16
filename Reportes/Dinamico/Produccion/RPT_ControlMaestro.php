<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_Resumen_General.php
  |---------------------------------------------------------------
  | @Autor: Jean Guzman Abregu
  | @Fecha de creacion: 26/07/2012
  | @Modificado por: Jean Guzman Abregu
  | @Fecha de la ultima modificacion: 26/07/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran el diseño del resumen general de avance de produccion
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Reporte Control Maestro VIVO!</title>

        <script type="text/javascript">   
            var xscroll = 0;
            function OnScrollDiv (div) {
                var d1 = document.getElementById ("dv_conten");
                var body = document.getElementById ("idbody");
                d1.scrollLeft = body.scrollLeft;
                div.scrollLeft = d1.scrollLeft;                
                xscroll = body.scrollLeft;
            }
            
            function OnScrollDiv2 (div) {
                var d1 = document.getElementById ("dv_conten");
                var body = document.getElementById ("idbody");
                body.scrollLeft = d1.scrollLeft;
                d1.scrollLeft = div.scrollLeft;
                xscroll = body.scrollLeft;

            }
        </script>

<!--        <script type="text/javascript" src="../../../Script/prototype.js"></script>-->
        <script type="text/javascript" src="../../../Script/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="../../../Script/jquery.periodicalupdater.js"></script>
        <link href="../../../Styles/estilos_resumen.css" rel="stylesheet" type="text/css" />
    </head>

    <body onscroll="OnScrollDiv (this)" id="idbody">
        <?php echo utf8_decode("<div id='dvTable'><h2>Espere unos segundos antes que cargue el reporte...</h2></div>"); ?>
    </body>

</html>
<?php $cod = $_REQUEST['cod']; ?>
<script type="text/javascript">
//    new Ajax.PeriodicalUpdater('dvTable', 'MAN_ControlMaestro.php?cod=<?php echo $cod; ?>', { 
//        method: 'get', frequency: 30, decay: 1
//    });    
    $(document).ready(function(){
        $.PeriodicalUpdater({
            url : 'MAN_ControlMaestro.php?cod=<?php echo $cod; ?>',
            minTimeout: 20000,
            maxTimeout: 20000
        },
        function(data){
            $('#dvTable').html(data);
            var d1 = document.getElementById ("dv_conten");                
            d1.scrollLeft = xscroll;            
        });
    });
</script>