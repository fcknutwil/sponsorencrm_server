DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS sponsor_engagement;
DROP TABLE IF EXISTS engagement_typ;
DROP TABLE IF EXISTS typ;
DROP TABLE IF EXISTS engagement;
DROP TABLE IF EXISTS beziehung;
DROP TABLE IF EXISTS sponsor;

CREATE TABLE users (
  id       INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name     VARCHAR(30) NOT NULL UNIQUE KEY,
  password VARCHAR(128) NOT NULL
);

CREATE TABLE typ (
  id       INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name     VARCHAR(50) NOT NULL UNIQUE KEY,
  readonly TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
);
INSERT INTO typ (name, readonly) VALUES ('Hauptsponsor', 1);
INSERT INTO typ (name, readonly) VALUES ('Ausrüster', 1);
INSERT INTO typ (name, readonly) VALUES ('Teamsponsor', 1);

CREATE TABLE engagement (
  id       INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name     VARCHAR(50) NOT NULL UNIQUE KEY,
  betrag   DECIMAL(8, 2) NOT NULL,
  zahlung  ENUM('Jährlich', 'Einmalig') NOT NULL DEFAULT 'Jährlich'
);

CREATE TABLE engagement_typ (
  id            INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  fk_typ        INT(6) UNSIGNED,
  fk_engagement INT(6) UNSIGNED,
  FOREIGN KEY (fk_typ) REFERENCES typ(id),
  FOREIGN KEY (fk_engagement) REFERENCES engagement(id)
);

CREATE TABLE sponsor (
  id            INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(50) NOT NULL UNIQUE KEY,
  vorname       VARCHAR(50) DEFAULT NULL,
  strasse       VARCHAR(100) DEFAULT NULL,
  fk_plz        INT(6) UNSIGNED NOT NULL,
  telefon       VARCHAR(12) DEFAULT NULL,
  email         VARCHAR(100) DEFAULT NULL,
  homepage      VARCHAR(100) DEFAULT NULL,
  notiz         TEXT DEFAULT NULL,
  name_ansprechpartner     VARCHAR(50) DEFAULT NULL,
  email_ansprechpartner    VARCHAR(100) DEFAULT NULL,
  telefon_ansprechpartner  VARCHAR(12) DEFAULT NULL,
  typ           ENUM('Firma', 'Privatperson') NOT NULL DEFAULT 'Firma'
);

CREATE TABLE sponsor_engagement (
  id            INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  fk_sponsor    INT(6) UNSIGNED,
  fk_engagement INT(6) UNSIGNED,
  von           DATE NOT NULL,
  bis           DATE NOT NULL DEFAULT '9999-12-31',
  FOREIGN KEY (fk_sponsor) REFERENCES sponsor(id),
  FOREIGN KEY (fk_engagement) REFERENCES engagement(id)
);

CREATE TABLE beziehung (
  id          INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  typ         ENUM('CRM', 'Donatoren', 'Andere') NOT NULL DEFAULT 'CRM',
  value       VARCHAR(50) NOT NULL,
  notizen     TEXT DEFAULT NULL,
  fk_sponsor  INT(6) UNSIGNED NOT NULL,
  FOREIGN KEY (fk_sponsor) REFERENCES sponsor(id)
)