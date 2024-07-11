# USE test_db;
# USE library;

CREATE TABLE IF NOT EXISTS users (
                                     user_id CHAR(36) PRIMARY KEY,
                                     user_name VARCHAR(255) NOT NULL,
                                     password VARCHAR(255) NOT NULL,
                                     email VARCHAR(255) NOT NULL,
                                     full_name VARCHAR(255),
                                     age INT,
                                     created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                                     role ENUM('user', 'admin') DEFAULT 'user' NOT NULL
);

CREATE TABLE IF NOT EXISTS books (
                                     book_id CHAR(36) PRIMARY KEY,
                                     title VARCHAR(255) NOT NULL,
                                     author VARCHAR(255) NOT NULL,
                                     year INT NOT NULL,
                                     pages INT,
                                     genre VARCHAR(255),
                                     language VARCHAR(50) NOT NULL,
                                     created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                                     is_available TINYINT(1) DEFAULT 1 NOT NULL
);

CREATE TABLE IF NOT EXISTS loans (
                                     loan_id CHAR(36) PRIMARY KEY,
                                     book_id CHAR(36) NOT NULL,
                                     user_id CHAR(36) NOT NULL,
                                     borrowed_at DATETIME NOT NULL,
                                     returned_at DATETIME,
                                     status ENUM('borrowed', 'returned') DEFAULT 'borrowed',
                                     FOREIGN KEY (book_id) REFERENCES books(book_id),
                                     FOREIGN KEY (user_id) REFERENCES users(user_id),
                                     INDEX (book_id),
                                     INDEX (user_id)
);
