use tpfinalpw2;

create table Usuario (id_prov smallint not null auto_increment,
nombreDeUsuario varchar(25),
contrasenia int,
tipoDeUsuario int default '0',/* el usuario 0 es el comun*/ 
constraint id_prov primary key (id_prov)
);

insert into Usuario (nombreDeUsuario , contrasenia, tipoDeUsuario)
values ("admin", "123456789", 1);


select *
from Usuario;


