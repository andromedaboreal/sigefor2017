INSERT INTO cfg_campos
(formulario, tabla, campo, alias, titulo, clase, tipo, control, configuracion, 
tipo_valor, valor_ini, parametros, parametros_act, validaciones, eventos, 
referenciar, aux, html, estilo, propiedades, estilo_titulo, 
propiedades_titulo, estilo_det, propiedades_det, comentario)


SELECT 'reg_sedes', c.tabla, c.campo, c.alias, c.titulo, c.clase, c.tipo, c.control, c.configuracion, c.tipo_valor, c.valor_ini, c.parametros, c.parametros_act, c.validaciones, c.eventos, c.referenciar, c.aux, c.html, c.estilo, c.propiedades, c.estilo_titulo, c.propiedades_titulo, c.estilo_det, c.propiedades_det, c.comentario
FROM cfg_campos as c
LEFT JOIN cfg_campos c2 ON /*c.formulario = c2.formulario AND*/ c.tabla = c2.tabla AND c.campo = c2.campo AND c2.formulario='reg_sedes'


WHERE c.formulario='sedes' AND c2.campo IS NULL