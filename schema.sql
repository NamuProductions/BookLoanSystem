USE test_db;

CREATE TABLE IF NOT EXISTS users (
                                     id INT AUTO_INCREMENT PRIMARY KEY,
                                     user_name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS books (
                                     id INT AUTO_INCREMENT PRIMARY KEY,
                                     title VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS loan_requests (
                                             id INT AUTO_INCREMENT PRIMARY KEY,
                                             book_id INT,
                                             user_id INT,
                                             borrow_at TIMESTAMP,
                                             return_at TIMESTAMP NULL,
                                             status VARCHAR(50),
                                             FOREIGN KEY (book_id) REFERENCES books(id),
                                             FOREIGN KEY (user_id) REFERENCES users(id)
);
