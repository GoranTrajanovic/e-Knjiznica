CREATE TABLE admin(
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  admin_username varchar(128) NOT NULL,
  admin_password varchar(128) NOT NULL
)DEFAULT CHARACTER SET utf8 ENGINE=INNODB;

CREATE TABLE knjiznicar(
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  knjiznicar_name varchar(128) NOT NULL,
  knjiznicar_surname varchar(128) NOT NULL,
  knjiznicar_date varchar(128) NOT NULL,
  knjiznicar_username varchar(128) NOT NULL,
  knjiznicar_password varchar(128) NOT NULL
)DEFAULT CHARACTER SET utf8 ENGINE=INNODB;

CREATE TABLE ucenici(
  id int(11) NOT NULL AUTO_INCREMENT,
  ucenik_oznaka varchar(128) NOT NULL,
  ucenik_name varchar(128) NOT NULL,
  ucenik_surname varchar(128) NOT NULL,
  ucenik_email varchar(128) NOT NULL,
  ucenik_date varchar(128) NOT NULL,
  ucenik_username varchar(128) NOT NULL,
  ucenik_password varchar(128) NOT NULL,
  ucenik_br_posudjenih_knjiga int(11) DEFAULT 0,
  ucenik_br_rezervacija int(11) DEFAULT 0,
  ucenik_br_rezervacija_mjesecnih int(11) DEFAULT 0,
  PRIMARY KEY(id, ucenik_oznaka)
)DEFAULT CHARACTER SET utf8 ENGINE=INNODB;

CREATE TABLE knjige(
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  knjiga_naslov varchar(128) NOT NULL,
  knjiga_autor varchar(128) NOT NULL,
  knjiga_vrsta varchar(128) NOT NULL,
  knjiga_kolicina varchar(128) NOT NULL,
  knjiga_date varchar(128) NOT NULL,
  knjiga_dostupno int(11) NOT NULL
)DEFAULT CHARACTER SET utf8 ENGINE=INNODB;

CREATE TABLE vrste(
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  vrsta_ime varchar(128) NOT NULL
)DEFAULT CHARACTER SET utf8 ENGINE=INNODB;

# posudjene_knjige table
# 
/*CREATE TABLE posudjene_knjige(
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  posudjeno_ime_ucenika varchar(128) NOT NULL,
  posudjeno_prezime_ucenika varchar(128) NOT NULL,
  posudjeno_email_ucenika varchar(128) NOT NULL,
  posudjeno_oznaka_ucenika varchar(128) NOT NULL,
  posudjeno_dani_posudbe varchar(128) NOT NULL
)DEFAULT CHARACTER SET utf8 ENGINE=INNODB*/

CREATE TABLE posudjene_knjige(
  posudba_ucenik_id int(11) NOT NULL,
  posudba_knjiga_id int(11) NOT NULL,
  br_preostalih_dana int(11) NULL,
  PRIMARY KEY(posudba_ucenik_id, posudba_knjiga_id)
)DEFAULT CHARACTER SET utf8 ENGINE=INNODB;

CREATE TABLE rezervirane_knjige(
  rezervirano_ucenik_id int(11) NOT NULL,
  rezervirano_knjiga_id int(11) NOT NULL,
  PRIMARY KEY(rezervirano_ucenik_id, rezervirano_knjiga_id)
)DEFAULT CHARACTER SET utf8 ENGINE=INNODB;

CREATE TABLE propale_rezervacije(
  propale_rezervacije_ucenik_id int(11) NOT NULL,
  propale_rezervacije_knjiga_id int(11) NOT NULL,
  PRIMARY KEY(propale_rezervacije_ucenik_id, propale_rezervacije_knjiga_id)
)DEFAULT CHARACTER SET utf8 ENGINE=INNODB;