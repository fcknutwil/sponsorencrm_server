INSERT INTO users (name, password) VALUES ('maesi', SHA2('maesi', 512));

INSERT INTO typ (name) VALUES ('Bandenwerbung');

INSERT INTO engagement (name, betrag) VALUES ('Hauptsponsor', 10000);
INSERT INTO engagement_typ (fk_typ, fk_engagement) VALUES (1, 1);
INSERT INTO engagement_typ (fk_typ, fk_engagement) VALUES (2, 1);
INSERT INTO engagement_typ (fk_typ, fk_engagement) VALUES (3, 1);
INSERT INTO engagement_typ (fk_typ, fk_engagement) VALUES (4, 1);

INSERT INTO engagement (name, betrag) VALUES ('Ausrüster', 1200);
INSERT INTO engagement_typ (fk_typ, fk_engagement) VALUES (2, 2);
INSERT INTO engagement_typ (fk_typ, fk_engagement) VALUES (3, 2);

INSERT INTO engagement (name, betrag, zahlung) VALUES ('Teamsponsor', 1200, 'onetime');
INSERT INTO engagement_typ (fk_typ, fk_engagement) VALUES (3, 3);

INSERT INTO engagement (name, betrag) VALUES ('Bandenwerbung', 200);
INSERT INTO engagement_typ (fk_typ, fk_engagement) VALUES (4, 4);

INSERT INTO sponsor (typ, name, strasse, fk_ort, telefon, email, homepage, name_ansprechpartner, email_ansprechpartner,
                     telefon_ansprechpartner) VALUES
  ('company', 'Die Mobiliar', 'Bundesgasse 35', 1241, '+41 31 389 70 50', 'info@mobiliar.ch', 'http://www.mobiliar.ch',
   'Marcel Arnold', 'marcel.arnold@mobiliar.ch', '+41 31 389 76 17');
INSERT INTO sponsor (typ, name, vorname, strasse, fk_ort, telefon, email, homepage) VALUES
  ('individual', 'Arnold', 'Marcel', 'Centralstrasse 31c', 2469, '+41 76 661 53 21', 'mail@maesi.org',
   'http://www.maesi.org');

INSERT INTO sponsor_engagement (fk_sponsor, fk_engagement, von) VALUES (1, 4, '2016-01-01');
INSERT INTO sponsor_engagement (fk_sponsor, fk_engagement, von, bis) VALUES (1, 3, '2014-01-01', '2015-12-31');
INSERT INTO sponsor_engagement (fk_sponsor, fk_engagement, von, bis) VALUES (2, 2, '2014-01-01', '2014-12-31');