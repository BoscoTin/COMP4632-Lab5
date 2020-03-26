CREATE DATABASE labdb DEFAULT CHARSET utf8;
USE labdb;

CREATE TABLE User (
    _id BIGINT NOT NULL AUTO_INCREMENT,
    email VARCHAR(100),
    password VARCHAR(30),
    secret CHAR(16),
    name VARCHAR(50),
    hkid VARCHAR(9),
    phone CHAR(8),
    address VARCHAR(1000),
    status CHAR(1),  -- E/D/A
    PRIMARY KEY (_id)
);

CREATE TABLE Vote (
    _id BIGINT NOT NULL AUTO_INCREMENT,
    topic CHAR(50),
    option_a VARCHAR(25),
    option_b VARCHAR(25),
    option_c VARCHAR(25),
    option_d VARCHAR(25),
    PRIMARY KEY (_id)
);

CREATE TABLE User_Vote (
    _id BIGINT NOT NULL AUTO_INCREMENT,
    user_id BIGINT,
    vote_id BIGINT,
    choice INT,  -- 1/2/3/4
    PRIMARY KEY (_id)
);

INSERT INTO User (email, password, secret, name, hkid, phone, address, status) VALUES ('admin', 'admin123', 'AAAAAAAAAAAAAAAB', 'Administrator', '', '', '', 'A');
