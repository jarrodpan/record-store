start transaction;
-- drop any existing tables-- nah just nuke the DB lol
drop database if exists djj_db;
create database djj_db;
use djj_db;

-- Items table
create table Items (
	id int(11) unsigned not null auto_increment,
	sku varchar(255) not null,
	title varchar(255) not null,
	price int(11) not null default 0,
	taxable boolean not null default false,	
	tangible boolean not null default false,	
	available boolean not null default false,	
	listed boolean not null default false,	
	quantity int(11) not null default 0,	
	description varchar(255),
	notes varchar(255),
	created timestamp not null default CURRENT_TIMESTAMP,
	primary key(id),
	constraint `CHK_ListAndNotAvailable` CHECK (`available` is true or (listed is false and available is false))
) engine=InnoDB ;

-- Tag and category system
create table Tags (
	id int(11) not null auto_increment,
	tag varchar(255) not null,
	permalink varchar(255) not null,
	primary key(id)
) engine=InnoDB ;

create table Categories (
	id int(11) not null auto_increment,
	category varchar(255) not null,
	permalink varchar(255) not null,
	apiName varchar(255) not null,
	primary key(id)
) engine=InnoDB ;

-- tag and category associative tables + fk entries
create table ItemTags (
	itemID int(11) not null,
	tagID int(11) not null,
	primary key(itemID, tagID),
	foreign key (itemID) REFERENCES Items(id) on delete cascade,
	foreign key (tagID) REFERENCES Tags(id) on delete cascade
) engine=InnoDB ;

create table TagCategories (
	tagID int(11) not null,
	categoryID int(11) not null,
	primary key(tagID, categoryID),
	foreign key (tagID) REFERENCES Tags(id) on delete cascade,
	foreign key (categoryID) REFERENCES Categories(id) on delete cascade
) engine=InnoDB ;

-- users table
create table Users (
	id int(11) not null auto_increment,
	username varchar(256) not null,
	firstname varchar(256) not null,
	pword binary(32) not null,
	email varchar(256) not null,
	access int(11) not null default 0,
	created timestamp not null default CURRENT_TIMESTAMP,
	primary key(id)
) engine=InnoDB ;


/*
create table Tags (
	id int(11) not null auto_increment,
	
	primary key(id)
) engine=InnoDB ;
*/

create table Barcodes (
	id int(11) not null auto_increment,
	itemID int(11) not null,
	quantity int(11) not null,
	
	primary key (id),
	foreign key (itemID) references Items(ID) on delete cascade
) engine=InnoDB ;

-- drop table images;
create table Images (
	id int(11) not null auto_increment,
	imageName varchar(255),
	filepath varchar(255) not null,
	uploaded timestamp,
	primary key(id)
) engine=InnoDB ;

create table ItemImages (
	itemID int(11) not null,
	imageID int(11) not null,
	linked timestamp,
	primary key(itemID, imageID),
	foreign key (itemID) REFERENCES Items(id) on delete cascade,
	foreign key (imageID) REFERENCES Images(id) on delete cascade
) engine=InnoDB ;

-- Í!7MÈ6lÎ
-- dummy data for entry
-- https://www.discogs.com/Steely-Dan-Cant-Buy-A-Thrill/release/5087466

insert into Items (sku, title, price, quantity)
values ('YQ-8005-AB', "Can't Buy A Thrill", 4000, 1);

insert into Tags (tag, permalink)
values
	("Steely Dan","steely-dan"),
	("5087466", "https://www.discogs.com/Steely-Dan-Cant-Buy-A-Thrill/release/5087466"),
	("Rock","rock"),
	("Pop","pop"),
	("1976","1976");

insert into Categories (category, permalink,apiName)
values
	("Artist", "artist", "artist"),
	("Discogs Release", "discogs-release", "discogs"),
	("Genre", "genre", "genre"),
	("Release Year", "release-year", "released"),
	("Recording Year", "recording-year", "recorded");

insert into ItemTags (itemID, tagID)
values (1,1),(1,2),(1,3),(1,4),(1,5);

insert into TagCategories (tagID, categoryID)
values (1,1),(2,2),(3,3),(4,3),(5,4),(5,5);


commit;
use djj_db;