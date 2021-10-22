create database tpfinalpw2;

use tpfinalpw2;

create table Usuario (idUsuario smallint not null auto_increment,
nombreDeUsuario varchar(25),
contrasenia int,
tipoDeUsuario int default '0',/* el usuario 0 es el comun*/ 
constraint id_prov primary key (idUsuario)
);

insert into Usuario (nombreDeUsuario , contrasenia, tipoDeUsuario)
values ("admin", "123456789", 1);

create table TipoDeViaje (idTipoDeViaje int not null auto_increment,
tipoDeViaje varchar(200) ,
constraint PKVIAJE primary key (idTipoDeViaje)
);

insert into TipoDeViaje (tipoDeViaje)
values ("Suborbital"),("Orbital"),("Entre destinos");


create table Equipo (idEquipo int not null auto_increment,
equipo varchar(200) ,
constraint PKequipo primary key (idEquipo)
);

insert into Equipo (equipo)
values ("Orbital"),("Baja aceleracion"),("Alta aceleracion");


create table viaje (idViaje int not null auto_increment,
idTipoDeViaje int ,
idEquipo int,
fechaSalida date,
fechaRegreso date,

constraint PKVIAJE primary key (idViaje),
constraint FK_A_TipoViaje foreign key (idTipoDeViaje) references tipoDeViaje (idTipoDeViaje),
constraint FK_A_IdEquipo foreign key (idEquipo) references Equipo (idEquipo) 
);

select*from viaje;

create table viajeUsuario (idViaje int not null,
idUsuarioK SMALLINT not null,
constraint FK_A_viaje foreign key (idViaje) references viaje (idViaje),
constraint FK_A_Usuario foreign key (idUsuarioK) references usuario (idUsuario)
);

insert into viaje (idTipoDeViaje, idEquipo,fechaSalida,fechaRegreso)
values (1,1,20211120,20240101),(2,2,20211110,20230201),(2,3,20211120,20300101)
,(1,1,20211010,20220101),(1,1,20211120,20220101),(2,2,20221120,20230101);




