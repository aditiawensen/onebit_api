CREATE DATABASE tirtabitung

USE tirtabitung

CREATE TABLE access_level
(
id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(40),
label VARCHAR(40)
)

CREATE TABLE pages
(
id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(40),
sort INT,
img TEXT,
link TEXT,
active CHAR(1),
parent INT,
)

CREATE TABLE parent
(
id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(40),
sort INT,
img TEXT
)

CREATE TABLE access
(
id INT AUTO_INCREMENT PRIMARY KEY,
access_level INT,
pages INT,
see CHAR(1),
edit CHAR(1),
del CHAR(1)  
)