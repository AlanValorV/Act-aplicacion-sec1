
create table if not exists productos (
    id integer primary key autoincrement,
    nombre varchar(50),
    descripcion varchar(50),
    precio float,
    stock integer,
);
insert into productos (nombre, descripcion, precio, stock) values('camiseta','playera unisex basica', 239.00, 50);
insert into productos (nombre, descripcion, precio, stock) values('camisa','camisa de lino unisex', 499, 50);
insert into productos (nombre, descripcion, precio, stock) values('pantalón', 'pantalón de mezclilla tipo baggy', 399, 50);
insert into productos (nombre, descripcion, precio, stock) values('short', 'short de vestir corte chino', 299, 50);
insert into productos (nombre, descripcion, precio, stock) values('cinturon', 'cinturon de cuero color café', 199, 50);

create table if not exists clientes(
    id integer primary key autoincrement,
    nombre varchar(50),
    direccion varchar(50),
    correo varchar(50),
    telefono int(10),

);
insert into clientes(nombre, direccion, correo, telefono) values('Juan','Constelaciones 2', 'JuanP@hotmail.com', '9381456579');
insert into clientes(nombre, direccion, correo, telefono) values('Fernando','Constelaciones 2','Ferf@hotmail.com', '9381234565');
insert into clientes(nombre, direccion, correo, telefono) values('María','Constelaciones 2','Mariafr@hotmail.com', '9384136576');
insert into clientes(nombre, direccion, correo, telefono) values('Fernanda','Constelaciones 2','Fernandat@hotmail.com', '9387658990');
insert into clientes(nombre, direccion, correo, telefono) values('Rodolfo','Constelaciones 2','RodolfoD@hotmail.com', '9381854321');

create table if not exists empleados(
    id integer primary key autoincrement,
    nombre varchar(50),
    apellido varchar(50),
    puesto varchar(50),
    salario float,
    Fecha date,
);
insert into empleados(nombre, apellido, puesto, salario, fecha) values('Dennis','Perez', 'Gerente', 8000);
insert into empleados(nombre, apellido, puesto, salario, fecha) values('Eduardo','Rodriguez', 'Vendedor', 6000);
insert into empleados(nombre, apellido, puesto, salario, fecha) values('Jose','Alonso', 'Vendedor', 6000);
insert into empleados(nombre, apellido, puesto, salario, fecha) values('Monse','Villanueva', 'Inventarista', 6000);

create table if not exists ventas(
    id integer primary key autoincrement,
    fecha date,
    total float
);
insert into ventas(fecha, total) values('1/04/2024', 877);
insert into ventas(fecha, total) values('1/04/2024', 638);
insert into ventas(fecha, total) values('1/04/2024', 1635);