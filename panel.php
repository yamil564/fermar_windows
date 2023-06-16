<?php
/*
  |---------------------------------------------------------------
  | PHP panel.php
  |---------------------------------------------------------------
  | @Autor: Kenyi M. Caycho Coyocusi
  | @Fecha de creacion: 07/12/2010
  | @Modificado por: Jean Guzman Abregu, Frank Peña Ponce
  | @Fecha de la ultima modificacion: 03/11/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina en donde se lista el panel de trabajo
 */

/* Inicializamos las sessiones y preguntamos si existe la session del usuario logeado. */
session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Panel Control</title>

        <link rel="stylesheet" type="text/css" href="Styles/Formulario.css"/>
        <link rel="stylesheet" type="text/css" href="Styles/reset.css"/>
        <link rel="stylesheet" type="text/css" href="Styles/panel.css"/>
        <link rel="stylesheet" type="text/css" href="Styles/tab.css"/>
        <link rel="stylesheet" type="text/css"  href="Styles/ui.multiselect.css" />
        <link type="text/css" href="Styles/custom-theme/jquery-ui-1.8.5.custom.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css"  href="Styles/timePicker.css" />
        <link rel="stylesheet" type="text/css"  href="Styles/message.css" />

        <script type="text/javascript" src="Script/jquery.js"></script>
        <script src="Script/jquery.timePicker.js" type="text/javascript"></script>
        <script src="Script/jquery.si.js" type="text/javascript"></script>
        <script type="text/javascript" src="Script/jquery-ui-1.8.5.custom.min.js"></script>       
        <script type="text/javascript" src="Script/ui.multiselect.js"></script>
        <script type="text/javascript" src="Script/utf8_decode.js"></script>
        <script type="text/javascript" src="Script/number-format.js"></script>
        <script src="Script/JQValidacion.js" type="text/javascript"></script>
        <script src="Script/grid.locale-sp.js" type="text/javascript"></script>
        <script src="Script/jquery.jqGrid.min.js" type="text/javascript"></script>
        <script src="Script/jquery.jqGrid.js" charset="utf-8" type="text/javascript"></script>
        <script type="text/javascript" src="Script/TRA_loading.js"></script>
        <script type="text/javascript" src="Script/passwordStrengthMeter.js"></script>
        <script src="Script/panel.js" type="text/javascript"></script>
        <script src="Script/noexiste.js" type="text/javascript"></script>
    </head>
    <body class="bg">

        <?php
        if (!isset($_SESSION['User-Login-Pas'])) {
            echo "<script type='text/javascript'>window.location = 'login.php';</script>";
        }
        ?>
        <div id="dialog-Procesos" style="display: none;"></div>
        <div id="dialog-window" style="display: none;"></div>
        <div id="dialog-reports" style="display: none;"></div>
        <div id="dialog_RTP_RegDiarioAvan" style="display: none;"></div>
        <div id="dialog-window_alternativo" style="display: none;"></div>
        <div id="dialog-window_PackingList" style="display: none;"></div>
        <div id="dialog" style="display: none;" class="dialog "></div>
        <div id="nav_sup">
            <img align="left" src="Images/fermar.jpg" alt="logo" width="57" height="40" title="logo" />
            <img align="right" src="Images/fermar.jpg" alt="logo" width="57" height="40" title="logo" />
            <div id="message">FERMAR SGP</div>
        </div>
        <div id="tituloGrid" class="tituloGrid">

        </div>
        <div id="contenedor">
            <div id="nav_izquierda">
                <?php
                if (isset($_SESSION['User-Login-Pas'])) {
                    $usuario = explode("-", $_SESSION['User-Login-Pas']);
                }
                ?>
                <div class="alerta">
                    <span style="float: left; margin-right: 0.3em; position: relative; top: -2px;">
                        <img src="Images/user.png" alt="user" title="Usuario" />
                    </span> Usuario: <b><?php echo $usuario[0]; ?></b><span style="display: none;" id="sp-codus"><?php echo $usuario[2]; ?></span>
                    <span style="display: none;" id="sp-codTra"><?php echo $usuario[3]; ?></span>
                </div>
                <div class="alerta">
                    <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span><a href="login.php" title="Cerrar Sesion" id="logout">
                        <b>Cerrar Sesion</b></a><a href="login.php" title="Cerrar Sesion"><img style="margin-left: 5px;" src="Images/logout.png" alt="Cerrar" width="22px;" height="22px;" /></a>
                </div>
                <div class="accordion">
                    <?php
                    /* Listamos el Menu lateral segun los permisos que tiene el usuario */
                    include_once 'PHP/FERConexion.php';
                    include_once 'Store_Procedure/SP_ProcedureAll.php';
                    $db = new MySQL();
                    $SP_ProcedureAll = new SP_Procedure();
                    $i = 0;
                    $ConsPermiso = $SP_ProcedureAll->SP_PermisoxUsuario($usuario[0]);
                    while ($data = $db->fetch_assoc($ConsPermiso)) {
                        echo '<h3 id="0" class="' . ($i + 1) . '" title="' . $data['per_vc40_desc'] . '"><img alt="" src="Images/' . $data['per_in2_ord'] . '.png" width="32" height="32" /><a href="#ge' . ($i + 1) . '">' . $data['per_vc40_desc'] . '</a></h3>';
                    ?>
                        <div>
                            <ul>
                                <li>
                                    <div class="mn_cpt">
                                    <?php
                                    $i++;
                                    if ($data['per_in11_cod'] != 3 && $data['per_in11_cod'] != 2 && $data['per_in11_cod'] != 4 && $data['per_in11_cod'] != 5) {
                                        /* Listamos el Menu del MODULO DE PLANIFICACION DE LA PRODUCCION:
                                         * Catalogo de Producto Base
                                         * Catalogo de Producto
                                         * Orden de Trabajo
                                         * Orden de Produccion
                                         * Requisicion de Materiales
                                         * Requisicion de Servicios
                                         * Servicios
                                          de acuerdo a los permisos del usuario */
                                    ?>
                                        <h3 id="off" class="a1" title="Gestion de Catalogos">
                                        <?php echo '<img src="Images/a.png" width="16" height="16" id="a" alt="->" /><a href="#tr' . ($i + 1) . '">Gestion de Catalogos</a>'; ?>
                                    </h3>
                                    <div class="no-border lvlacordion" >
                                        <?php
                                        echo '<ul>';
                                        $ConsForm = $SP_ProcedureAll->SP_FormularioxUsuario($usuario[0], 'Gestion de Catalogos', $data['per_in11_cod']);
                                        while ($RespForm = $db->fetch_assoc($ConsForm)) {
                                            echo '<li title="' . $RespForm['acc_vc50_nom'] . '">';
                                            echo '<img src="Images/' . $RespForm['acc_vc40_tip'] . '.png" height="16"  width="16" alt="' . $RespForm['acc_vc40_tip'] . '"/>';
                                            echo '<a href="#" class="a_form" id="' . $RespForm['acc_vc100_url'] . '?per=' . $RespForm['per_in11_cod'] . '&us=' . $RespForm['usu_in11_cod'] . '&nom=' . $RespForm['acc_vc50_nom'] . '">' . $RespForm['acc_vc50_nom'] . '</a>';
                                            echo '<li>';
                                        }
                                        echo '</ul>'; ?>
                                    </div>
                                    <h3 id="off2" class="a1" title="Registro de Ordenes">
                                        <?php echo '<img src="Images/a.png" width="16" height="16" id="a" alt="->" /><a href="#tr' . ($i + 1) . '">Registro de Ordenes</a>'; ?>
                                    </h3>
                                    <div class="no-border lvlacordion" >
                                        <?php
                                        echo '<ul>';
                                        $ConsForm = $SP_ProcedureAll->SP_FormularioxUsuario($usuario[0], 'Registro de Ordenes', $data['per_in11_cod']);
                                        while ($RespForm = $db->fetch_assoc($ConsForm)) {
                                            echo '<li title="' . $RespForm['acc_vc50_nom'] . '">';
                                            echo '<img src="Images/' . $RespForm['acc_vc40_tip'] . '.png" height="16"  width="16" alt="' . $RespForm['acc_vc40_tip'] . '"/>';
                                            echo '<a href="#" class="a_form" id="' . $RespForm['acc_vc100_url'] . '?per=' . $RespForm['per_in11_cod'] . '&us=' . $RespForm['usu_in11_cod'] . '&nom=' . $RespForm['acc_vc50_nom'] . '">' . $RespForm['acc_vc50_nom'] . '</a>';
                                            echo '<li>';
                                        }
                                        echo '</ul>'; ?>
                                    </div>
                                    <h3 id="off3" class="c1" title="Gestion de Requisicion">
                                        <?php echo '<img src="Images/a.png" width="16" height="16" id="c" alt="->" /><a href="#tr' . ($i + 1) . '">Gestion de Requisicion</a>'; ?>
                                    </h3>
                                    <div class="no-border lvlacordion" >
                                        <?php
                                        echo '<ul>';
                                        $ConsForm = $SP_ProcedureAll->SP_FormularioxUsuario($usuario[0], 'Gestion de Requisicion', $data['per_in11_cod']);
                                        while ($RespForm = $db->fetch_assoc($ConsForm)) {
                                            echo '<li title="' . $RespForm['acc_vc50_nom'] . '">';
                                            echo '<img src="Images/' . $RespForm['acc_vc40_tip'] . '.png" height="16"  width="16" alt="' . $RespForm['acc_vc40_tip'] . '"/>';
                                            echo '<a href="#" class="a_form" id="' . $RespForm['acc_vc100_url'] . '?per=' . $RespForm['per_in11_cod'] . '&us=' . $RespForm['usu_in11_cod'] . '&nom=' . $RespForm['acc_vc50_nom'] . '">' . $RespForm['acc_vc50_nom'] . '</a>';
                                            echo '</li>';
                                        }
                                        echo '<ul>';
                                        ?>
                                    </div>
                                    <h3 id="off4" class="c1" title="Servicios">
                                        <?php echo '<img src="Images/a.png" width="16" height="16" id="c" alt="->" /><a href="#tr' . ($i + 1) . '">Servicios</a>'; ?>
                                    </h3>
                                    <div class="no-border lvlacordion" >
                                        <?php
                                        echo '<ul>';
                                        $ConsForm = $SP_ProcedureAll->SP_FormularioxUsuario($usuario[0], 'Servicios', $data['per_in11_cod']);
                                        while ($RespForm = $db->fetch_assoc($ConsForm)) {
                                            echo '<li title="' . $RespForm['acc_vc50_nom'] . '">';
                                            echo '<img src="Images/' . $RespForm['acc_vc40_tip'] . '.png" height="16"  width="16" alt="' . $RespForm['acc_vc40_tip'] . '"/>';
                                            echo '<a href="#" class="a_form" id="' . $RespForm['acc_vc100_url'] . '?per=' . $RespForm['per_in11_cod'] . '&us=' . $RespForm['usu_in11_cod'] . '&nom=' . $RespForm['acc_vc50_nom'] . '">' . $RespForm['acc_vc50_nom'] . '</a>';
                                            echo '</li>';
                                        }
                                        echo '<ul>';
                                        ?>
                                    </div>
                                    <?php } else if ($data['per_in11_cod'] != 3 && $data['per_in11_cod'] != 2 && $data['per_in11_cod'] != 1 && $data['per_in11_cod'] != 5) {
                                    ?>
                                        <h3 id="off" class="a1" title="Registro de Producción">
                                        <?php echo '<img src="Images/a.png" width="16" height="16" id="a" alt="->" /><a href="#tr' . ($i + 1) . '">Registro de Producción</a>'; ?>
                                    </h3>
                                    <div class="no-border lvlacordion" >
                                        <?php
                                        echo '<ul>';
                                        $ConsForm = $SP_ProcedureAll->SP_FormularioxUsuario($usuario[0], 'Registro de Producción', $data['per_in11_cod']);
                                        while ($RespForm = $db->fetch_assoc($ConsForm)) {
                                            echo '<li title="' . $RespForm['acc_vc50_nom'] . '">';
                                            echo '<img src="Images/' . $RespForm['acc_vc40_tip'] . '.png" height="16"  width="16" alt="' . $RespForm['acc_vc40_tip'] . '"/>';
                                            echo '<a href="#" class="a_form" id="' . $RespForm['acc_vc100_url'] . '?per=' . $RespForm['per_in11_cod'] . '&us=' . $RespForm['usu_in11_cod'] . '&nom=' . $RespForm['acc_vc50_nom'] . '">' . $RespForm['acc_vc50_nom'] . '</a>';
                                            echo '<li>';
                                        }
                                        echo '</ul>';
                                        ?>
                                    </div>
                                    <h3 id="off1" class="a1" title="Inspección de Calidad">
                                        <?php echo '<img src="Images/a.png" width="16" height="16" id="a" alt="->" /><a href="#tr' . ($i + 1) . '">Inspección de Calidad</a>'; ?>
                                    </h3>
                                    <div class="no-border lvlacordion" >
                                        <?php
                                        echo '<ul>';
                                        $ConsForm = $SP_ProcedureAll->SP_FormularioxUsuario($usuario[0], 'Inspección de Calidad', $data['per_in11_cod']);
                                        while ($RespForm = $db->fetch_assoc($ConsForm)) {
                                            echo '<li title="' . $RespForm['acc_vc50_nom'] . '">';
                                            echo '<img src="Images/' . $RespForm['acc_vc40_tip'] . '.png" height="16"  width="16" alt="' . $RespForm['acc_vc40_tip'] . '"/>';
                                            echo '<a href="#" class="a_form" id="' . $RespForm['acc_vc100_url'] . '?per=' . $RespForm['per_in11_cod'] . '&us=' . $RespForm['usu_in11_cod'] . '&nom=' . $RespForm['acc_vc50_nom'] . '">' . $RespForm['acc_vc50_nom'] . '</a>';
                                            echo '<li>';
                                        }
                                        echo '</ul>';
                                        ?>
                                    </div>
                                    <?php } else if ($data['per_in11_cod'] != 1 && $data['per_in11_cod'] != 3 && $data['per_in11_cod'] != 4 && $data['per_in11_cod'] != 5) {
                                    ?>
                                        <h3 id="off" class="a1" title="Control de Avance">
                                        <?php echo '<img src="Images/a.png" width="16" height="16" id="a" alt="->" /><a href="#tr' . ($i + 1) . '">Herramientas de Analisis</a>'; ?>
                                    </h3>
                                    <div class="no-border lvlacordion" >
                                        <?php
                                        echo '<ul>';
                                        $ConsForm = $SP_ProcedureAll->SP_FormularioxUsuario($usuario[0], 'Herramientas de Analisis', $data['per_in11_cod']);
                                        while ($RespForm = $db->fetch_assoc($ConsForm)) {
                                            echo '<li title="' . $RespForm['acc_vc50_nom'] . '">';
                                            echo '<img src="Images/' . $RespForm['acc_vc40_tip'] . '.png" height="16"  width="16" alt="' . $RespForm['acc_vc40_tip'] . '"/>';
                                            echo '<a href="#" class="a_form" id="' . $RespForm['acc_vc100_url'] . '?per=' . $RespForm['per_in11_cod'] . '&us=' . $RespForm['usu_in11_cod'] . '&nom=' . $RespForm['acc_vc50_nom'] . '">' . $RespForm['acc_vc50_nom'] . '</a>';
                                            echo '<li>';
                                        }
                                        echo '</ul>';
                                        ?>
                                    </div>
                                    <?php } else {
                                    ?>
                                        <h3 id="off5" class="d1" title="Seguridad">
                                        <?php echo '<img src="Images/a.png" width="16" height="16" id="a" alt="->" /><a href="#tr' . ($i + 1) . '">Seguridad</a>'; ?>
                                    </h3>
                                    <div class="no-border lvlacordion" >
                                        <?php
                                        echo '<ul>';
                                        $ConsForm = $SP_ProcedureAll->SP_FormularioxUsuario($usuario[0], 'Seguridad', $data['per_in11_cod']);
                                        while ($RespForm = $db->fetch_assoc($ConsForm)) {
                                            echo '<li title="' . $RespForm['acc_vc50_nom'] . '">';
                                            echo '<img src="Images/' . $RespForm['acc_vc40_tip'] . '.png" height="16"  width="16" alt="' . $RespForm['acc_vc40_tip'] . '"/>';
                                            echo '<a href="#" class="a_form" id="' . $RespForm['acc_vc100_url'] . '?per=' . $RespForm['per_in11_cod'] . '&us=' . $RespForm['usu_in11_cod'] . '&nom=' . $RespForm['acc_vc50_nom'] . '">' . $RespForm['acc_vc50_nom'] . '</a>';
                                            echo '</li>';
                                        }
                                        echo '</ul>';
                                        ?>
                                    </div>
                                    <?php } ?>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div id="frm_control">
                <div id="loading" style="display: none; position: absolute; top: 300px; left: 500px;"><img src='Images/ajax-loader.gif' alt=""/></div>
                <div id="panel"></div>
            </div>
            <div id="btnOcultar" class="HideMenu"></div>
        </div>



        <script type="text/javascript">
            var perBotones = '';
            function ListaAccionDet(accion,type){
                var arr = accion.split('::');
                var per = arr[0];
                var usu = arr[1];
                var nom = arr[2];
                var dato = '';
                $.post('PHP/LIS_Accion.php',{
                    per:per,
                    usu:usu,
                    nom:nom,
                    type:type
                },
                function(data){
                    perBotones = data;

                }
            )
            }
            $(function() {
                var objP = new id();
                $('div.accordion:eq(0)> div').hide();
                $('div.accordion:eq(0)> h3').click(function() {
                    objP.id = $(this).attr('class');
     
                    $(this).css({'background':'url("Images/menu-bg.GIF") repeat-x scroll left -379px #F1F1F1'});
                    $(this).attr({'id':'1'});
                    var aux = $(this).children().next().html();
                    $(this).next().slideToggle('fast');
    
                    $('h3').each(function(){
                        if($(this).attr('id') == 1){
                            if($(this).children().next().html() != aux){
                                $(this).css({'background':'white'});
                            }
                        }

                    });
                });
                SubMenu(objP.id);

                function SubMenu(id){
                    var aux = '',src = '';
                    $('div.mn_cpt('+id+') > div').hide();
                    $('div.mn_cpt('+id+') > h3').click(function() {
                        aux = $(this).attr('class');
                        src = $(this).children().attr('src');
                        objP.src = $(this).children().attr('id');
                        src = src.split('/');
                        OpenClose(aux,src[1]);
                        $(this).next().slideToggle('fast');
                    });
                }

  
                function id(id,src){
                    this.id = id;
                    this.src = src;
                }
                function OpenClose(id,src){
                    if($('.'+id).attr('id') == 'on'){
                        $('.'+id).children().attr({'src':'Images/'+objP.src+'.png'});
                        $('.'+id).attr('id','off');
                    }else{
                        $('.'+id).attr('id','on');
                        src = src.split('.');
                        $('.'+id).children().attr({'src':'Images/'+src[0]+'o.png'});
                    }
                }
            });
        </script>
    </body>
</html>