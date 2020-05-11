<?php
define("C_SW","SW");

define("C_ELE_SCRIPT","frm");
define("C_FSCRIPT","frm");

// ==================== cfg_actualizar ====================
define("C_REGISTRO","cfg_registro_aux");
define("C_MODO","cfg_modo_aux");
define("C_PASSWORD_INVALIDO","¡¡intro-pass!!");
define("C_SEP_DET_CAMPO","|");
define("C_SEP_DET_REGISTRO","{:}");

// ==================== cfg_autentificacion ====================
define ("C_NRO_ERR_INTENTOS","1");
define ("C_NRO_ERR_CLAVE","2");
define ("C_NRO_ERR_USUARIO","3");
define ("C_NRO_ERR_USR_NO_GRUPO","4");
define ("C_NRO_ERR_CLV_VENCIDA","5");
define ("C_NRO_ERR_NO_SISTEMA","6");
define ("C_NRO_ERR_STATUS","7");
//define ("C_NRO_ERR_INTENTOS","8");
define ("C_NRO_ERR_USUARIO_CLAVE","9");

// ==================== cfg_campos ====================
define("C_PREFIJO_CFG","cfg_");

define("C_VALOR_DEFAULT","0");
define("C_VALOR_LITERAL","0");
define("C_VALOR_EXPRESION","1");
define("C_VALOR_FIJO","2");

define("C_TIPO_NORMAL","0");
define("C_TIPO_TEXTO","1");
define("C_TIPO_SELECCION","2");
define("C_TIPO_SELECCION_MULT","3");
define("C_TIPO_FECHA","4");
define("C_TIPO_HORA","5");
define("C_TIPO_SUB_FORMULARIO","6");
define("C_TIPO_OTRO","7");
define("C_TIPO_NINGUNO","100");

define("C_CTRL_TEXT","1");
define("C_CTRL_HIDDEN","2");
define("C_CTRL_PASSWORD","3");
define("C_CTRL_TEXTAREA","4");
define("C_CTRL_SELECT","5");
define("C_CTRL_RADIO","6");
define("C_CTRL_CHECKBOX","7");
define("C_CTRL_MULTIPLE","8");
define("C_CTRL_MULTIPLE2","44");
define("C_CTRL_LABEL","9");
define("C_CTRL_FILE","10");
define("C_CTRL_CESTA","20");
define("C_CTRL_CALENDARIO","21");
define("C_CTRL_DATE_TEXT","22");
define("C_CTRL_DATE_LIST","23");
define("C_CTRL_TIME_TEXT","31");
define("C_CTRL_TIME_LIST","32");
define("C_CTRL_SET","41");
define("C_CTRL_SET2","42");
define("C_CTRL_GRID","51");
define("C_CTRL_TEXT_DESCRIP","61");
define("C_CTRL_COMBO_TEXT","62");


define("C_CTRL_SG_SELECT","63");
define("C_CTRL_SG_CALENDAR","64");
define("C_CTRL_SG_COLOR","65");


// ==================== cfg_consulta ====================
define("C_CON_OBJ_CELDA",1);
define("C_CON_OBJ_IMAGEN",2);
define("C_CON_OBJ_VINCULO",3);
define("C_CON_OBJ_ACCION",4);
define("C_CON_OBJ_LISTA",10);
define("C_CON_OBJ_PASSWORD",12);
define("C_CON_OBJ_OCULTO",20);

define("C_CON_TOBJ_CELDA","celda");
define("C_CON_TOBJ_IMAGEN","imagen");
define("C_CON_TOBJ_VINCULO","vinculo");
define("C_CON_TOBJ_ACCION","accion");
define("C_CON_TOBJ_LISTA","lista");
define("C_CON_TOBJ_PASSWORD","password");
define("C_CON_TOBJ_OCULTO","oculto");

define("C_CON_GRID_NORMAL","0");
define("C_CON_GRID_BASICO","1");
define("C_CON_GRID_UPDATE","3");
define("C_CON_GRID_SUBFORM","4");
// ==================== cfg_estructura ====================
define("C_PLANTILLA_DEFAULT","0");
define("C_PLANTILLA_DIAGRAMA","1");
define("C_PLANTILLA_ARCHIVO","2");

// ==================== cls_menu ====================
define("C_MENU_POR_DEFECTO","-1");	
define("C_MENU_VERTICAL","1");	
define("C_MENU_HORIZONTAL","2");	
define("C_MENU_BIDIMENSIONAL","3");	
define("C_MENU_SUBMENU","4");
define("C_SMENU_AUTO","5");

define("C_MENU_NORMAL","1");
define("C_MENU_PATRON","2");
define("C_MENU_DISENO","3");
define("C_MENU_ARCHIVO","4");	

define("C_MENU_IMG_POR_DEFECTO","-1");	
define("C_MENU_IMG_SOLO_TEXTO","1");	
define("C_MENU_IMG_SOLO_IMAGEN","2");	

define("C_MENU_IMG_TXT_DERECHA","3");	
define("C_MENU_IMG_TXT_IZQUIERDA","4");	
define("C_MENU_IMG_TXT_ARRIBA","5");	
define("C_MENU_IMG_TXT_ABAJO","6");	
define("C_MENU_MEN_TXT_DERECHA","7");
define("C_MENU_MEN_TXT_IZQUIERDA","8");

// ==================== cls_navegador ====================
define("C_NAV_NORMAL","0");
define("C_NAV_PATRON","1");




// Tablas del SIGEFOR
define ("C_CFG_ACCIONES","cfg_acciones");
define ("C_CFG_CAMPOS","cfg_campos");
define ("C_CFG_USR_ACC","cfg_usr_acc");
define ("C_CFG_USR_MEN","cfg_usr_men");
define ("C_CFG_USR_NAV","cfg_usr_nav");
define ("C_CFG_EST_MEN","cfg_est_men");
define ("C_CFG_EST_ELE","cfg_est_ele");
define ("C_CFG_ESTRUCTURAS","cfg_estructuras");
define ("C_CFG_FORMAS","cfg_formas");
define ("C_CFG_FORMULARIOS","cfg_formularios");
define ("C_CFG_GPO_USR","cfg_gpo_usr");
define ("C_CFG_GPO_EST","cfg_gpo_est");
define ("C_CFG_GPO_NAV","cfg_gpo_nav");
define ("C_CFG_GPO_ACC","cfg_gpo_acc");

define ("C_CFG_GPO_MEN_ACC","cfg_gpo_men_acc");
define ("C_CFG_GPO_NAV_ACC","cfg_gpo_nav_acc");

define ("C_CFG_GRUPOS","cfg_grupos");
define ("C_CFG_MEN_ACC","cfg_men_acc");
define ("C_CFG_MENUS","cfg_menus");
define ("C_CFG_NAV_ACC","cfg_nav_acc");
define ("C_CFG_NAVEGADORES","cfg_navegadores");
define ("C_CFG_USUARIOS","cfg_usuarios");
define ("C_CFG_CONSULTAS","cfg_consultas");
define ("C_CFG_CAMPOS_CON","cfg_campos_con");
define ("C_CFG_COMANDOS","cfg_comandos");
define ("C_CFG_PROCEDIMIENTOS","cfg_procedimientos");
define ("C_CFG_MODULOS","cfg_modulos");
define ("C_CFG_SECUENCIAS","cfg_secuencias");
define ("C_CFG_TEMAS","cfg_temas");
define ("C_CFG_OBJETOS","cfg_objetos");
define ("C_CFG_OBJ_ACC"," cfg_obj_acc");

define ("C_CFG_ELEMENTOS","cfg_elementos");


define ("C_CFG_FLUJOS","cfg_flujos");
define ("C_CFG_NODOS","cfg_nodos");

define ("C_CFG_PLANTILLAS","cfg_plantillas");
define ("C_CFG_PLA_PAN","cfg_pla_pan");
define ("C_CFG_ARTICULOS","cfg_articulos");

define ("C_CFG_PAGINAS","cfg_paginas");
define ("C_CFG_PAG_ART","cfg_pag_art");
define ("C_CFG_CATALOGOS","cfg_catalogos");

define ("C_CFG_REPORTES","cfg_reportes");
define ("C_CFG_CAMPOS_REP","cfg_campos_rep");

//============== Constantes para ****[cls_estructura.php]*** =============
define ("V_OBJETO","cfg_objeto_aux");
define ("V_PARAMETRO","cfg_parametro_aux");
define ("V_ACCION","cfg_accion_aux");
define ("V_MODO","cfg_modo_aux");
define ("V_REG","cfg_reg_aux");
define ("V_PAGINA","cfg_pagina_aux");
define ("V_PAG_FORM","cfg_pag_form_aux");
define ("V_VISTA","cfg_vista_aux");
define ("V_FORM","cfg_form_aux");
define ("V_EST","cfg_est_aux");
define ("V_CMD","cfg_prop_aux");
define ("V_PROC1","cfg_prmto1_aux");
define ("V_PROC2","cfg_prmto2_aux");
define ("V_SW","cfg_sw_aux");
define ("V_SW2","cfg_sw_aux");
define ("V_MES","cfg_mes_aux");
define ("V_VSESION","_FoRm_");

define ("C_VSF","VSF_SG_AUX");
define ("CFG_MENU_SEPARADOR","|");

define ("C_OBJ_NO_APLICA","0");
define ("C_OBJ_FORMULARIO","1");
define ("C_OBJ_CONSULTA","2");
define ("C_OBJ_BUSQUEDA","3");
define ("C_OBJ_REPORTE","4");
define ("C_OBJ_ARTICULO","5");
define ("C_OBJ_PAGINA","6");
define ("C_OBJ_ENLACE","7");
define ("C_OBJ_IFRAME","8");
define ("C_OBJ_MENU","9");

define ("C_OBJ_VISTA","11");



define ("C_OBJ_CATALOGO","14");
define ("C_OBJ_OBJETO","20");
define ("C_OBJ_ACCION","40");
define ("C_OBJ_GRAFICO","50");
define ("C_OBJ_NINGUNO","100");



define ("C_ELEM_NO_APLICA","0");
define ("C_ELEM_FORMULARIO","1");
define ("C_ELEM_CONSULTA","2");
define ("C_ELEM_BUSQUEDA","3");
define ("C_ELEM_REPORTE","4");
define ("C_ELEM_ARTICULO","5");
define ("C_ELEM_PAGINA","6");
define ("C_ELEM_ENLACE","7");
define ("C_ELEM_IFRAME","8");
define ("C_ELEM_MENU","9");

define ("C_ELEM_VISTA","11");
define ("C_ELEM_CATALOGO","14");
define ("C_ELEM_COMANDO","20");
define ("C_ELEM_REFERENCIA","30");
define ("C_ELEM_ACCION","40");
define ("C_ELEM_OBJETO","41");
define ("C_ELEM_GRAFICO","50");

define ("C_ELEM_NODO","200");

define ("C_ELEM_NINGUNO","100");


define ("C_ELEM_FORMA","100");
define ("C_ELEM_DEFAULT","101");
define ("C_ELEM_VACIO","102");

define ("C_ITEM_SUBMIT","1");
define ("C_ITEM_BUTTON","2");
define ("C_ITEM_RESET","3");


define ("C_TABLA_AUX","_ttt_");
define ("C_MODO_INSERT","1");
define ("C_MODO_UPDATE","2");
define ("C_MODO_DELETE","3");
define ("C_MODO_CONSULTA","4");
define ("C_MODO_PETICION","5");
define ("C_MODO_BUSQUEDA","6");
define ("C_MODO_ACTUAL","100");
define ("C_ACCION_NINGUNA","0");
define ("C_ACCION_GUARDAR","1");
define ("C_ACCION_SESSION","2");
define ("C_ACCION_VALIDAR","4");
define ("C_ACCION_SALIR","5");
define ("C_ACCION_EN_SESION","6");
define ("C_ACCION_GRID_AGREGAR","7");
define ("C_ACCION_GUARDAR_AGREGAR","8");


define ("C_NAV_SEP_NINGUNO","0");
define ("C_NAV_SEP_LINEA","1");
define ("C_NAV_SEP_DBLLINEA","2");
define ("C_NAV_SEP_REGLA","3");
define ("C_NAV_SEP_ESPACIO","4");
define ("C_NAV_SEP_DBLESPACIO","5");
define ("C_NAV_SEP_TRIESPACIO","6");
define ("C_NAV_SEP_HTML","7");


//============== Constantes para ****[cfg_campos.php]*** =============
define("C_NORMAL","0");
define("C_TEXTO","1");
define("C_LISTA","2");
define("C_MULTIPLE","3");
define("C_FECHA","4");
define("C_HORA","5");
define("C_ESPECIAL","6");
define("C_NINGUNO","7");




define("C_TIPO_DEFAULT","0");
define("C_TIPO_EXPRESION","1");
define("C_TIPO_CALCULO","2");
define("C_TIPO_VISTA","1");
define("C_TIPO_CONSULTA","2");

//============== Constantes para ****[cfg_campos.php]*** =============

define("C_CLAVE_NORMAL","0");
define("C_CLAVE_SERIAL","1");

//============== Basicas
define ("C_SI",true);
define ("C_NO",false);

//============== Transacciones
define ("C_COMMIT",1);
define ("C_ROLLBACK",2);
define ("C_IGNORAR_TRANS",0);

//============== Tipos Metas:
define("C_TIPO_I","I");
define("C_TIPO_C","C");
define("C_TIPO_X","X");
define("C_TIPO_N","N");
define("C_TIPO_D","D");
define("C_TIPO_T","T");

//============== Parametros iniciales para la consulta
define("C_CFG_CAMPOS_VIST_NO","0");
define("C_CFG_CAMPOS_VIST_SI","1");
define("C_CFG_CAMPOS_VIST_FORM","2");



define("C_SEP_L",",");
define("C_SEP_Q",";");
define("C_SEP_V",":");
define("C_SEP_E","=");
define("C_SEP_P","\|");


define("C_IDENT_VAR_FORM","#");
define("C_IDENT_VAR_SES","@");
define("C_IDENT_VAR_REG","&");
define("C_IDENT_VAR_PARA","&PR_");
define("C_IDENT_VAR_EXP","&EX_");


//====================================================================
define("C_PROP_DESHABILITADO","deshabilitado");
define("C_PROP_SOLO_LECTURA","solo_lectura");
define("C_PROP_NO_EDITABLE","no_editable");
define("C_PROP_SI","si");
define("C_PROP_NO","no");
/*cls_articulos.php*/
define ("C_ART_RESUMEN","1");
define ("C_ART_CONTENIDO","2");
?>