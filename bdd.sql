create table vehicule (
    idVehicule serial primary key auto_increment,
    modele varchar(50),
    numero varchar(20)
);

create table user (
    id serial primary key auto_increment,
    login varchar(50),
    password varchar(50)
);

create table token(
    id integer,
    token varchar(100),
    dateExpiration timestamp
);

create table kilometrage (
    idVehicule integer,
    dateKilometrage date,
    debut float,
    fin float,
    foreign key (idVehicule) references vehicule(idVehicule)
);

insert into user values (null,'Mitia',SHA1('1234'));

insert into vehicule values (null,'Porsche Cayenne','852 AMK');
insert into vehicule values (null,'Porsche Panamera','950 AMK');
insert into vehicule values (null,'Dodge Charger','784 AMK');
insert into vehicule values (null,'Range Rover Evoque','784 AMK');