alter table fe_empresa add cod_empresa varchar(30)


INSERT INTO [dbo].[fe_empresa]
           ([nombre]
           ,[rut]
           ,[dv]
           ,[direccion]
		   ,active
		   ,created_at
		   ,updated_at
		   ,cod_empresa
          )
     VALUES
           ('Comercial Lircay S.A'
           ,96516320
           ,'4'
           ,'AV. MATTA                221              19La Serena             4123'
		   ,1
           ,getdate()
		   ,getdate()
		   ,'CB20986212'
		   )


CREATE TABLE TBL_TIPO_DOCUMENTO (
ID INT,
DESCRIPCION VARCHAR(30)
)


INSERT INTO TBL_TIPO_DOCUMENTO (
ID,
descripcion
)
SELECT	ID, DESCRIPCION
FROM	tipo_documento

insert into TBL_TIPO_DOCUMENTO (id,descripcion) values (120,'BOLETA ELECTRONICA')

DROP TABLE tipo_documento


SELECT		*
INTO		tipo_documento
FROM		TBL_TIPO_DOCUMENTO	


/***************** 2020-12-21 **********************/
alter table detalle_factura_cliente add codigo varchar(30)
alter table detalle_factura_cliente add nombre_producto varchar(100)
alter table detalle_factura_cliente add unidad varchar(30)
ALTER TABLE caf add fecha_vencimiento date


update	c
set		fecha_vencimiento = '20201231'
from	caf c



insert into fe_usuario_empresa (
idusuario
,id_empresa
)
values(
1
,10122)

alter table empresa alter column giro varchar(1000)


alter table factura_clientes add rut_cliente int
alter table factura_clientes add dv_cliente char(1)
alter table factura_clientes add giro_cliente varchar(1000)
alter table factura_clientes add cod_act_econ_cli varchar(50)
alter table factura_clientes add dir_cliente varchar(250)
alter table factura_clientes add com_cliente varchar(100)
alter table factura_clientes add ciu_cliente varchar(100)
alter table factura_clientes add raz_soc_cliente varchar(250)



alter table factura_clientes add vendedor varchar(250)
alter table factura_clientes add condicion_pago varchar(100)




create table referencias (
id int identity,
idfactura int,
nrolinref int,
tpodocref varchar(10),
folioref   varchar(10),
fecha_creacion datetime default getdate(),
user_creacion varchar(100) default suser_name())


alter table factura_clientes add fecha_creacion datetime default getdate()
alter table factura_clientes add user_creacion varchar(100) default suser_name()


alter table detalle_factura_cliente add fecha_creacion datetime default getdate()
alter table detalle_factura_cliente add user_creacion varchar(100) default suser_name()


alter table folios_caf add dte_cliente varchar(max) 
alter table folios_caf add archivo_dte_cliente varchar(50)

insert into tipo_caf(id,nombre) values (39,'Boleta Electrónica')
