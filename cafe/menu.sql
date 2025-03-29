CREATE TABLE menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    is_popular BOOLEAN DEFAULT 0
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT,
    temperature ENUM('Hot', 'Cold') DEFAULT 'Hot',
    quantity INT NOT NULL,
    total_price DECIMAL(10,2),
    status ENUM('Pending', 'Completed') DEFAULT 'Pending',
    FOREIGN KEY (item_id) REFERENCES menu_items(id) ON DELETE CASCADE
);


