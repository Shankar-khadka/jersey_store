CREATE DATABASE IF NOT EXISTS jersey_store_crud CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jersey_store_crud;

CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  category ENUM('Club','National Team','Retro','Training','Goalkeeper') NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  size VARCHAR(50) NOT NULL DEFAULT 'S,M,L,XL',
  stock INT NOT NULL DEFAULT 0,
  description TEXT,
  image VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  full_name VARCHAR(100) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  address VARCHAR(255) NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  payment_method ENUM('cod','esewa') NOT NULL,
  payment_status ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending',
  status ENUM('Pending','Processing','Shipped','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  size VARCHAR(10) NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  user_id INT NOT NULL,
  rating TINYINT NOT NULL,
  comment TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  gateway VARCHAR(30) NOT NULL,
  transaction_uuid VARCHAR(80) NOT NULL UNIQUE,
  amount DECIMAL(10,2) NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'initiated',
  reference_id VARCHAR(80) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Default admin (admin / admin). 'PLAIN:' is upgraded to a bcrypt hash on first login.
INSERT IGNORE INTO admins (username, password) VALUES ('admin', 'PLAIN:admin');

INSERT INTO products (name, category, price, size, stock, description, image) VALUES
('Red Devils Home Jersey 24/25','Club',2800,'S,M,L,XL',25,'Classic red home shirt with breathable fabric.','jersey_1.svg'),
('Blue Lions Home Jersey 24/25','Club',2800,'S,M,L,XL',20,'Sky blue home shirt, lightweight and comfortable.','jersey_2.svg'),
('Royal White Away Jersey','Club',2600,'M,L,XL',15,'Clean white away kit with gold trim.','jersey_3.svg'),
('Nepal National Team Home','National Team',3000,'S,M,L,XL',30,'Crimson national team jersey with blue sleeves.','jersey_4.svg'),
('Albiceleste Stripes','National Team',3200,'S,M,L,XL',18,'Sky and white vertical stripes.','jersey_5.svg'),
('Samba Yellow Jersey','National Team',3200,'S,M,L,XL',22,'Iconic yellow shirt with green collar.','jersey_6.svg'),
('Retro 1998 Classic','Retro',3500,'M,L,XL',10,'Vintage-inspired classic shirt.','jersey_7.svg'),
('Retro 90s Green Edition','Retro',3400,'S,M,L',8,'Throwback green shirt with bold pattern.','jersey_8.svg'),
('Pro Training Top Orange','Training',1800,'S,M,L,XL',40,'Moisture-wicking training top.','jersey_9.svg'),
('Pro Training Top Black','Training',1800,'S,M,L,XL',35,'Lightweight black training top.','jersey_10.svg'),
('Goalkeeper Pro Neon','Goalkeeper',3000,'S,M,L,XL',12,'Padded long-sleeve goalkeeper shirt.','jersey_11.svg'),
('Goalkeeper Pro Purple','Goalkeeper',3000,'M,L,XL',9,'Long-sleeve goalkeeper kit in purple.','jersey_12.svg');
