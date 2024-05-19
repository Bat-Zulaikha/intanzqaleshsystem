CREATE TABLE customer (
    email VARCHAR(100) PRIMARY KEY,
    full_name VARCHAR(50),
    password VARCHAR(100),
    phone_number VARCHAR(20),
    address VARCHAR(255)
);

CREATE TABLE admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(100),
    email VARCHAR(100) UNIQUE
);

CREATE TABLE product (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    description TEXT,
    price DECIMAL(10,2),
	image VARCHAR(255) NOT NULL,
	stock INT DEFAULT 0,
    category ENUM('menswear', 'womenswear', 'kidswear', 'kidtoys', 'cutupfruits', 'drink'),
	sizeWear boolean NOT NULL,
	size_info VARCHAR(50)
);

CREATE TABLE cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    quantity INT,
    email VARCHAR(100),
    product_id INT,
	size VARCHAR(10),
    FOREIGN KEY (email) REFERENCES customer(email),
    FOREIGN KEY (product_id) REFERENCES product(product_id)
);

CREATE TABLE payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100),
    product_id INT,
	quantity INT,
    amount DECIMAL(10,2),
	payment_method VARCHAR(50),
    payment_date DATETIME,
    payment_status ENUM('pending', 'completed', 'failed'),
    FOREIGN KEY (email) REFERENCES customer(email),
    FOREIGN KEY (product_id) REFERENCES product(product_id)
);

CREATE TABLE customer_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100),
    login_date DATETIME,
    FOREIGN KEY (email) REFERENCES customer(email)
);