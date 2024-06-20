-- USE test_db;
-- USE library;

CREATE TABLE IF NOT EXISTS users (
                                     id INT AUTO_INCREMENT PRIMARY KEY,
                                     username VARCHAR(255) NOT NULL,
                                     password VARCHAR(255) NOT NULL,
                                     email VARCHAR(255) NOT NULL,
                                     full_name VARCHAR(255) NOT NULL,
                                     age INT,
                                     created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                                     updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS books (
                                     id INT AUTO_INCREMENT PRIMARY KEY,
                                     title VARCHAR(255) NOT NULL,
                                     author VARCHAR(255) NOT NULL,
                                     isbn VARCHAR(13) NOT NULL,
                                     published_date DATE NOT NULL,
                                     genre VARCHAR(255),
                                     pages INT,
                                     available_copies INT DEFAULT 1,
                                     total_copies INT DEFAULT 1,
                                     created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                                     updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS loan_requests (
                                             id INT AUTO_INCREMENT PRIMARY KEY,
                                             book_id INT,
                                             user_id INT,
                                             borrowed_at DATETIME NOT NULL,
                                             return_date DATETIME DEFAULT NULL,
                                             status ENUM('pending', 'approved', 'returned', 'lost') DEFAULT 'pending',
                                             FOREIGN KEY (book_id) REFERENCES books(id),
                                             FOREIGN KEY (user_id) REFERENCES users(id),
                                             created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                                             updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
