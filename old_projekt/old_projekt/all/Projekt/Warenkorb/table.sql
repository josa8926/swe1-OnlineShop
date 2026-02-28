CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  vorname VARCHAR(100),
  nachname VARCHAR(100),
  adresse VARCHAR(255),
  ort VARCHAR(100),
  zusatz VARCHAR(255),
  plz VARCHAR(20),
  email VARCHAR(150),
  handy VARCHAR(50)
);
  CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  title VARCHAR(255),
  price DECIMAL(10,2),
  qty INT
);


