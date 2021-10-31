create database tpfinalpw2;
use tpfinalpw2;
create table Equipo (idEquipo int not null auto_increment,
equipo varchar(200) ,
constraint PKequipo primary key (idEquipo)
);

create table TipoDeViaje (idTipoDeViaje int not null auto_increment,
tipoDeViaje varchar(200) ,
constraint PKVIAJE primary key (idTipoDeViaje)
);

create table Usuario (idUsuario int not null auto_increment,
nombreDeUsuario varchar(25),
contrasenia int,
tipoDeUsuario int default '0',/* el usuario 0 es el comun esto es para el login el otro tipo de usuario esta en chequeo*/ 
constraint idUsuario primary key (idUsuario)
);

create table ChequeoMedico (idChequeo int not null auto_increment,
tipoDeCliente smallint , /*Tipo 1 2 o 3 - esto lo habilita o no para subir a cieras aeronaves, destinados a ciertos vuelos*/
idUsuario int,
constraint idChequeo primary key (idChequeo),
constraint FKAUsuario foreign key (idUsuario) references usuario (idUsuario)
);
/*debe relacionarse En la reserva, Los datos Medicos y los datos de Usuario*/

create table cabina (idCabina int  auto_increment,
cabina varchar(100),
constraint pk primary key (idCabina)
);

create table salida (idSalida int not null auto_increment,
LugarSal varchar(100),
constraint pk primary key (idSalida)
);

create table destino (idDestino int not null auto_increment,
LugarDes varchar(100),
constraint pk primary key (idDestino)
);

create table modelo (idModelo int not null auto_increment,
modelo varchar (200),
constraint pkModelo primary key (idModelo)
);

create table aeronave (idAeronave int not null auto_increment,
idTipoDeViaje int, /*Sub Orbital o entre destinos*/
idModelo int,
capacidad int,
idCabina int,
idEquipo int, /* Orbital, Baja o Alta aceleracion */
matricula varchar(150),
constraint pk primary key (idAeronave),
constraint fkATipo foreign key (idTipoDeViaje) references tipodeviaje (idTipoDeViaje), /*ejemplo orbital*/
constraint fkACabina foreign key (idCabina) references cabina (idCabina), /* Familiar etc.*/
constraint fkAEquipo foreign key (idEquipo) references equipo (idEquipo), /* Familiar etc.*/
constraint fkaModelo foreign key (idModelo) references modelo (idModelo)

);

create table viaje (idViaje int not null auto_increment,
fechaSalida date,
fechaRegreso date,
idSalida int,/*Buenos aires o Ankara*/
idDestino int,
duracion int, /*en dias*/
idAeronave int,
/*la cabina la define el aeronave junto con el tipo de viaje*/
constraint PKVIAJE primary key (idViaje),
constraint FK_A_Salida foreign key (idSalida) references salida (idSalida),
constraint FK_A_Destino foreign key (idDestino) references destino (idDestino)
);

create table reserva (idReserva int not null auto_increment,
idViaje int not null,
idUsuario int not null,
asiento int, /* va haber tanta cantidad de asientos para este vuelo como capacidad tenga el avion */
estado boolean default false, /*si esta ocupado o no */
/*se puede reservar mas de uno a la vez, el asiento va a representar dentro de la capacidad de la aeronave 
asignada. Hacer un innter join con viaje y uno con aeronave*/
constraint PKPAC primary key (idReserva),
constraint fkaViaje foreign Key (idViaje) references viaje (idViaje),
constraint fkaYsuario foreign key (idUsuario) references usuario (idUsuario)
);

insert into Usuario (nombreDeUsuario , contrasenia, tipoDeUsuario)
values ("admin", "123456789", 1),("cris", "123456789", 0);

insert into cabina (cabina)
value("General"),("Familiar"),("Suite");

insert into Equipo (equipo)
values ("Orbital"),("Baja aceleracion"),("Alta aceleracion");

insert into TipoDeViaje (tipoDeViaje)
values ("Suborbital"),("Orbital"),("Entre destinos");

insert into modelo (modelo)
values("Calandria"),("Colibri"),("Zorzal"),("Carancho"),("Aguilucho"),("Canario"),("Aguila"),("Condor"),("Halcon");

insert into aeronave (idTipoDeViaje,idModelo, capacidad, idCabina, idEquipo, matricula)
value (1,2,20,2,1,"AEC45"),(1,1,45,3,2,"250ASDC"),(1,4,20,2,1,"BBB252"),
(2,6,85,3,2,"JIL252"),(2,5,50,1,3,"FAS484"),(2,3,10,3,3,"ZZZM05"),(2,2,25,2,2,"AME10001"),
(3,9,30,1,1,"MKL4810"),(3,8,40,2,2,"A058585"),(3,6,15,2,2,"CVD2055"),(3,5,10,3,3,"ZZZ055");

insert into salida (LugarSal)
value ("Buenos Aires"),("Ankara");

insert into destino (LugarDes)
value ("Estación Espacial Internacional"),("OrbiterHotel"),("Luna"),("Marte"),
("Ganimedes"),("Europa"),("Encedalo"),("Titán");

/*RECORDAR QUE SOLO EL VIAJE ENTRE DESTINOS TIENE SALIDA Y DESTINO. LOS OTROS SON SOLO SUBORBITALES Y ORBITALES*/

insert into viaje (fechaSalida,fechaRegreso,idSalida, idDestino, idAeronave)
values (20211120,20240101,1,2,8),(20211110,20230201,1,2,8),(20211120,20300101,2,4,9)
,(20211010,20220101,2,5,11),(20211120,20220101,1,7,10),(20221120,20230101,1,6,9),
(20211120,20240101,1,2,8),(20211110,20230201,1,2,10),(20211120,20300101,2,4,9)
,(20220101,20220501,1,6,1),(20211120,20220101,1,6,3),(20221120,20230101,1,6,2),
(20211120,20240101,1,6,1),(20211110,20230201,2,6,2),(20211120,20300101,1,6,4)
,(20220102,20221001,2,6,5),(20230202,20250101,2,6,5),(20210505,20230401,2,6,2)
,(20210804,20221002,1,6,6),(20250101,20260101,1,6,7),(20230104,20230501,2,6,3),
(20211120,20240101,1,6,4),(20211201,20211230,2,6,1),(20211120,20300101,2,6,7)
,(20211205,20220501,2,6,6),(20211120,20220101,1,6,7),(20221120,20230101,1,6,3);

/*VOY A LISTAR LOS ASIENTOS DISPONIBLES PARA ESE VUELO. Cuando se seleccione un vuelo 
de forma dinamica se van a crear registros por la cantidad de disponibilidad de la nave, los usuarios pueden
elegir que lugar ocupar, ese lugar o mas bien, esa reserva va a quedar con estado ocupado*/

/*LOS VUELOS ORBITALES NO TIENEN DESTINO Y SALIDA (PORQUE ESO ES PARA ENTRE DESTINOS)*/
select * from chequeomedico;