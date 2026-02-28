CREATE TABLE Bestellung {
  id INT AUTO_INCREMENT PRIMARY KEY,
  vorname VARCHAR(100),
  nachname VARCHAR(100),
  adresse VARCHAR(255),
  ort VARCHAR(100),
  zusatz VARCHAR(255),
  plz VARCHAR(20),
  email VARCHAR(150),
  handy VARCHAR(50),
  status VARCHAR(30) NOT NULL DEFAULT 'Bezahlt'
);

