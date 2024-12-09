DROP DATABASE IF EXISTS findabuddy;
CREATE DATABASE IF NOT EXISTS findabuddy;

USE findabuddy;


DROP TABLE IF EXISTS msg;
DROP TABLE IF EXISTS workout;
DROP TABLE IF EXISTS schedule;
DROP TABLE IF EXISTS athletes;
DROP TABLE IF EXISTS user_admin;

CREATE TABLE user_admin (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    passcode VARCHAR(2000) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50)
);
ALTER TABLE user_admin AUTO_INCREMENT = 1000;

CREATE TABLE athletes (
    alt_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    nick_name VARCHAR(50) NOT NULL,
    gender VARCHAR(50),
    age INT,
    email VARCHAR(100) UNIQUE,
    phone_number VARCHAR(20) UNIQUE,
    user_address VARCHAR(200),
    FOREIGN KEY (user_id) REFERENCES user_admin(user_id)
);
ALTER TABLE athletes AUTO_INCREMENT = 1000;

CREATE TABLE schedule (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    organizer_id INT,
    activities VARCHAR(50) NOT NULL UNIQUE,  -- run, walk, and bike
    event_date DATE,
    event_time TIME,
    distance INT,
    comment TEXT,
    FOREIGN KEY (organizer_id) REFERENCES athletes(alt_id)
);
ALTER TABLE schedule AUTO_INCREMENT = 5000;

CREATE TABLE workout (
    workout_id INT AUTO_INCREMENT PRIMARY KEY,
    workout_date ENUM('Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su') NOT NULL,
    workout_time TIME NOT NULL,
    activity ENUM('Run', 'Walk', 'Bike') NOT NULL
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    content TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES user_admin(user_id),
    FOREIGN KEY (receiver_id) REFERENCES user_admin(user_id)
);