--REDDO pre acondicionamiento
DELETE FROM almacen_operacion;
DELETE FROM almacen_operacion_detalle;
DELETE FROM almacen_stock;

DELETE FROM plan_produccion_materia_prima;
DELETE FROM plan_produccion_pieza;
DELETE FROM plan_produccion_producto;

DELETE FROM actividad_plan_produccion;
DELETE FROM pieza_fallada_actividad;

UPDATE plan_produccion SET 
acondicionamiento_fecha = NULL,
estado_fase = 0,
estado_actividad = NULL,
acondicionamiento_costo_extra = NULL,
acondicionamiento_comentarios = NULL,
acondicionamiento_costo_materia_prima = NULL,
acondicionamiento_costo_materia_prima_ahorro = NULL,
fundicion_fecha = NULL,
fundicion_horas = NULL,
fundicion_comentarios = NULL,
fundicion_costo_extra = NULL
WHERE cod_plan_produccion = 32
