-- Drop database if it exists and recreate it
DROP DATABASE IF EXISTS findabuddy;
CREATE DATABASE IF NOT EXISTS findabuddy;

USE findabuddy;


DROP TABLE IF EXISTS msg;
DROP TABLE IF EXISTS workout;
DROP TABLE IF EXISTS schedule;
DROP TABLE IF EXISTS athletes;
DROP TABLE IF EXISTS user_admin;

-- Create user_admin table
CREATE TABLE user_admin (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    passcode VARCHAR(50) NOT NULL
);
ALTER TABLE user_admin AUTO_INCREMENT = 1000;

-- Create athletes table
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

-- Create schedule table
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

-- Create workout table
CREATE TABLE workout (
    workout_id INT AUTO_INCREMENT PRIMARY KEY,
    exercise_type VARCHAR(50) NOT NULL,
    workout_date DATE,
    workout_time TIME,
    FOREIGN KEY (exercise_type) REFERENCES schedule(activities)
);

-- Create msg table
CREATE TABLE msg (
    msg_id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    recipient_id INT NOT NULL,
    msg_datetime DATETIME,
    msg_content TEXT,
    FOREIGN KEY (sender_id) REFERENCES athletes(alt_id),
    FOREIGN KEY (recipient_id) REFERENCES athletes(alt_id)
);

-- Insert initial data into user_admin
INSERT INTO user_admin(username, passcode) 
VALUES ('abcd', '123456'), ('csc350', '123456'), ('xampp', '123456');

-- Insert initial data into athletes
INSERT INTO athletes(user_id, nick_name, gender, age, email, phone_number, user_address)
VALUES (1000, 'textuser1','Male',25,'csc350@bmcc.com','2122222322','123 main st'),
       (1001, 'textuser2','Female',21,'xampp@bmcc.com','6466067100','67 78th st');

-- Insert initial data into schedule
INSERT INTO schedule(organizer_id, activities, event_date, event_time, distance, comment)
VALUES (1000, 'run', '2024-11-01', '09:30:00', 10, 'Run 10 miles in the morning in Dec 1st 2024.');
