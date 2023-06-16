/*
|---------------------------------------------------------------
| JS Seguridad.js
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 24/10/2011
| @Fecha de la ultima modificacion: 28/10/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina LIST_Seg.php
*/

var texto = '';

$(document).ready(function(){
    var usu = $("#sp-codus").html();
    // Llamando a la funcion CargaGrid
    CargaGrid(usu, '');
   
});

//
function fun_save(){
    $.post("Seguridad/Permisos/MAN_Permisos.php",{
        permiso:permiso_1,
        estado:permiso_2,
        codent:codent
    },function(data){
        message('Permisos','info',"Proceso realizado correctamente","messageclose","",'');
    });
}


// Funcion que carga el grid
function CargaGrid(usu, filter){
    jQuery("#tbl_permisos").jqGrid({
        treeGrid: true,
        treeGridModel : 'adjacency',
        ExpandColumn : 'usuario',
        url: 'Seguridad/Permisos/Tablas/TAB_Permisos.php?usu='+usu+"&filter="+filter,
        datatype: "xml",
        mtype: "POST",
        colNames:["id","Usuario",'','','','','','','','','',''],
        colModel:[
        {
            name:'id',
            index:'id',
            width:1,
            hidden:true,
            key:true
        },

        {
            name:'usuario',
            index:'usuario',
            width:320,
            formatter:url_usu
        },

        {
            name:'acciones1',
            index:'acciones1',
            width:35,
            align:"center",
            formatter:url
        },

        {
            name:'acciones2',
            index:'acciones2',
            width:35,
            align:"center",
            formatter:url
        },

        {
            name:'acciones3',
            index:'acciones3',
            width:35,
            align:"center",
            formatter:url
        },

        {
            name:'acciones4',
            index:'acciones4',
            width:35,
            align:"center",
            formatter:url
        },

        {
            name:'acciones5',
            index:'acciones5',
            width:35,
            align:"center",
            formatter:url
        },

        {
            name:'acciones6',
            index:'acciones6',
            width:35,
            align:"center",
            formatter:url
        },

        {
            name:'acciones7',
            index:'acciones7',
            width:35,
            align:"center",
            formatter:url
        },

        {
            name:'acciones8',
            index:'acciones8',
            width:35,
            align:"center",
            formatter:url
        },

        {
            name:'acciones9',
            index:'acciones8',
            width:35,
            align:"center",
            formatter:url
        },

        {
            name:'acciones10',
            index:'acciones8',
            width:35,
            align:"center",
            formatter:url
        }
        ],
        height:274,
        width:814,
        rowNum:0,
        shrinkToFit:false,
        toolbar: [true,"top"],
        pager : "#pager",
        caption: "Fermar",
        gridComplete: function(){
            $("#txtBuscar").focus();
            $("#txtBuscar").val(texto);
        }
    });
    $("#t_tbl_permisos").append("<div>&nbsp; Buscar Usuario: <input type='text' id='txtBuscar' onkeypress='BuscarUsuario(event)' name='txtBuscar' style='width: 260px;' /></div>");
}

// Funcion que transforma una celda en texto con icono
function url_usu(cellvalue,options,rowObject){
    var data=cellvalue.split("::");
    return "<img src='Images/"+data[1]+"' style='width: 18px; height: 18px;' /> "+data[0];
}

// Funcion que transforma una celda en boton imagen
function url(cellvalue,options,rowObject){
    if(cellvalue!='' && cellvalue!=null){
        var data=cellvalue.split("::");
        var id = data[0]+"_"+data[1]+"_"+data[2]+"_"+data[3]+"_"+data[4];
        var fid = data[0]+"::"+data[1]+"::"+data[2]+"::"+data[3]+"::"+data[4]+"::"+data[7];
        if(data[0]!='' && data[0]!=null){
            if(data[0]=='1'){
                
                if(data[6]=='Estado'){
                    return "<img id='"+id+"' src='Images/"+data[5]+".png' onclick=\"boton_click('"+fid+"::"+id+"','"+data[8]+"');\" title='Habilitado' alt='"+data[6]+"' class='enable' style='width: 20px; height: 20px; cursor: pointer;'> ";
                }else{
                    return "<img id='"+id+"' src='Images/"+data[5]+".png' onclick=\"boton_click('"+fid+"::"+id+"','"+data[8]+"');\" title='"+data[6]+"' alt='"+data[6]+"' class='enable' style='width: 20px; height: 20px; cursor: pointer;'> ";
                }
            }else{
                if(data[6]=='Estado'){
                    return "<img id='"+id+"' src='Images/"+data[5]+".png' onclick=\"boton_click('"+fid+"::"+id+"','"+data[8]+"');\" title='Deshabilitado' alt='"+data[6]+"' class='disable' style='width: 20px; height: 20px; cursor: pointer;'> ";
                }else{
                    return "<img id='"+id+"' src='Images/"+data[5]+".png' onclick=\"boton_click('"+fid+"::"+id+"','"+data[8]+"');\" title='"+data[6]+"' alt='"+data[6]+"' class='disable' style='width: 20px; height: 20px; cursor: pointer;'> ";
                }
            }
        }else{
            return "";
        }
    }else{
        return "";
    }
}

var permiso_1 = new Array();
var permiso_2 = new Array();
var contador=0;

// Funcion que agrega la accion seleccionada a un array
function boton_click(type,usu){
    var item = type.split("::");
    var est = 0;
    if($("#"+item[6]).attr("class")=='enable'){
        est = 1;
        if(type=='estado'){
            $("#"+item[6]).attr("title","Deshabilitado");
        }
        $("#"+item[6]).removeClass("enable");
        $("#"+item[6]).addClass("disable");
    }else{
        est = 0;
        if(type=='estado'){
            $("#"+item[6]).attr("title","Habilitado");
        }
        $("#"+item[6]).removeClass("disable");
        $("#"+item[6]).addClass("enable");
    }

    $.post("Seguridad/Permisos/MAN_Permisos.php", {
        upPer:1,
        est:est,
        usu:usu,
        per:item[2],
        coln:item[3],
        from:item[5]
    }, function(data){       
        });
}

// Funcion de la caja de texto de Busqueda que realiza el filtro por usuario
function BuscarUsuario(e){
    //alert("TE FALTA DPS");
    var usu = $("#sp-codus").html();
    var filter=$("#txtBuscar").val();
    if(e.which==13 || e.keyCode == 13){
        texto=filter;
        $("#jqgrid").html('');
        $("#jqgrid").html('<table id="tbl_permisos"></table><div id="pager"></div>');
        CargaGrid(usu, filter);
    }
}

//Funcion del llamado del mensaje
function message(title, type, message,funaceptar, aceptar, cancelar){
    $.post('Scripts/message.php',{
        title:title,
        type:type,
        message:message,
        funaceptar:funaceptar,
        aceptar:aceptar,
        cancelar:cancelar
    },function(data){
        $("#dialog").removeAttr('style');
        $("#dialog").html(data);
    });
}

// Funcion que cierra el cuadro de mensaje
function messageclose(){
    $("#dialog").attr('style','display:none;');
}