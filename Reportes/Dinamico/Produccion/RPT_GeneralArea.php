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
        <title></title>
        <script type="text/javascript" src="../../../Script/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="../../../Script/jquery.periodicalupdater.js"></script>
        <link href="../../../Styles/estilos_resumen.css" rel="stylesheet" type="text/css" />
         <script type="text/javascript">
            fecha = new Date();
            var dia = fecha.getDate();
            var mes = (fecha.getMonth() + 1);
            var anio = fecha.getFullYear();
                
            function ffecha(){                                
                var elemento = $('id_1');
                elemento.innerHTML = dia+'/'+mes+'/'+anio;
            }
            var xscroll = 0;
            function OnScrollDiv (div) {
                var d1 = document.getElementById ("dv_conten2");
                var body = document.getElementById ("idbody");
                d1.scrollLeft = body.scrollLeft;
                div.scrollLeft = d1.scrollLeft;                
                xscroll = body.scrollLeft;
            }
            
            function OnScrollDiv2 (div) {
                var d1 = document.getElementById ("dv_conten2");
                var body = document.getElementById ("idbody");
                body.scrollLeft = d1.scrollLeft;
                d1.scrollLeft = div.scrollLeft;
                xscroll = body.scrollLeft;

            }
        </script>
    </head>
    <body onscroll="OnScrollDiv (this)" id="idbody">
        <table id="mytable" cellspacing="0" style="width: 200%;">
            <tr>
                <td style="text-align: left; font-weight: bold;">
                    <?php echo utf8_decode("Fecha emisi&oacute;n: "); ?><label id="id_1" ></label>
                </td>
            </tr>
        </table>
        <?php echo utf8_decode("<div id='dvTable'><h2>Espere unos segundos antes que cargue el reporte...</h2></div>"); ?>
    </body>
</html>
<?php $cod = $_REQUEST['cod']; ?>
<script type="text/javascript">    
//    new Ajax.PeriodicalUpdater('dvTable', 'MAN_RPT_GeneralArea.php?cod=<?php //echo $cod; ?>&dia='+dia+'&mes='+mes+'&anio='+anio, { 
//        method: 'get', frequency: 10, decay: 1 
//    });
    $(document).ready(function(){
        $.PeriodicalUpdater({
            url : 'MAN_RPT_GeneralArea.php?cod=<?php echo $cod; ?>&dia='+dia+'&mes='+mes+'&anio='+anio,
            minTimeout: 5000,
            maxTimeout: 5000
        },
        function(data){
            $('#dvTable').html(data);
            var d1 = document.getElementById ("dv_conten2");                
            d1.scrollLeft = xscroll;            
        });
    });
    ffecha();    
</script>