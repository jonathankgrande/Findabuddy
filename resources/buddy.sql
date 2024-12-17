DROP DATABASE IF EXISTS findabuddy;
CREATE DATABASE IF NOT EXISTS findabuddy;
USE findabuddy;

-- Drop existing tables if they exist
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS workout;
DROP TABLE IF EXISTS schedule;
DROP TABLE IF EXISTS athletes;
DROP TABLE IF EXISTS user_admin;

-- Create user_admin
CREATE TABLE user_admin (
    user_id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    passcode VARCHAR(2000) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    profile_image VARCHAR(255),
    PRIMARY KEY (user_id),
    UNIQUE KEY (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE user_admin AUTO_INCREMENT = 1020;

-- Create athletes
CREATE TABLE athletes (
    alt_id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11),
    nick_name VARCHAR(50) NOT NULL,
    gender VARCHAR(50),
    age INT(11),
    email VARCHAR(100),
    phone_number VARCHAR(20),
    user_address VARCHAR(200),
    about TEXT,
    title VARCHAR(255),
    PRIMARY KEY (alt_id),
    UNIQUE KEY (email),
    UNIQUE KEY (phone_number),
    UNIQUE KEY (user_id),
    FOREIGN KEY (user_id) REFERENCES user_admin(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE athletes AUTO_INCREMENT = 1028;

-- Create schedule
CREATE TABLE schedule (
    event_id INT(11) NOT NULL AUTO_INCREMENT,
    organizer_id INT(11),
    activities VARCHAR(50) NOT NULL,
    event_date DATE,
    event_time TIME,
    distance INT(11),
    comment TEXT,
    PRIMARY KEY (event_id),
    UNIQUE KEY (activities),
    KEY (organizer_id),
    FOREIGN KEY (organizer_id) REFERENCES athletes(alt_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE schedule AUTO_INCREMENT = 5000;

-- Create workout
CREATE TABLE workout (
    workout_id INT(11) NOT NULL AUTO_INCREMENT,
    workout_date ENUM('Mo','Tu','We','Th','Fr','Sa','Su') NOT NULL,
    workout_time TIME NOT NULL,
    activity ENUM('Run','Walk','Bike') NOT NULL,
    user_id INT(11) NOT NULL,
    PRIMARY KEY (workout_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE workout AUTO_INCREMENT = 25;

-- Create messages
CREATE TABLE messages (
    id INT(11) NOT NULL AUTO_INCREMENT,
    sender_id INT(11) NOT NULL,
    receiver_id INT(11) NOT NULL,
    content TEXT NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY (sender_id),
    KEY (receiver_id),
    FOREIGN KEY (sender_id) REFERENCES user_admin(user_id),
    FOREIGN KEY (receiver_id) REFERENCES user_admin(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE messages AUTO_INCREMENT = 20;


-- Insert data into user_admin
INSERT INTO user_admin (user_id, username, passcode, first_name, last_name, profile_image) VALUES
(1011, 'Axel', '$2y$10$Vm2rDC5jlJmhlddzC4p1xuJS8f1wvOYdWp2lIK786ojiT1YPBVZPi', 'Axel', 'Rose', '../uploads/1734420344_axel.jpg'),
(1013, 'Slash', '$2y$10$4HnOGyxKBRMtCwOrctnMVOpBQNlx8KF46oRS75XHf0MspElr3KHZC', 'Slash', 'Hudson', '../uploads/1734420964_slash.jpg'),
(1015, 'TheRock', '$2y$10$v5K7yHNRNdQMkMcRc5mro.voGRphfDVAaXJOfT4RD9tiJaTNoSdB6', 'Rock', 'Johnson', 'uploads/TheRock_default.png'),
(1019, 'Van', '$2y$10$u.umz81ytk3q46f0sPuCou/lLQAokN/9Hqpj3SXatVnsjEirEIOEa', 'Eddie ', ' Van Halen', '../uploads/1734421204_van.jpg');

-- Insert data into athletes
INSERT INTO athletes (alt_id, user_id, nick_name, gender, age, email, phone_number, user_address, about, title) VALUES
(1002, 1011, 'Axl', 'male', 53, 'axelrose@gmail.com', '9292236333', 'Bronx', 'Hey, I am Axl Rose, band member of Guns N Roses and a lover of rock n roll and running. When I’m not on stage performing hits like Sweet Child O Mine or Welcome to the Jungle, you will find me hitting the road for a run. Running clears my mind, fuels my energy, and keeps me ready for the next big show. Music and running, it’s all about rhythm and freedom.', 'Rockstar and Runner'),
(1021, 1013, 'Slash', 'male', 35, 'slash@gmail.com', '9292246443', 'Bronx', 'Hey there! I am Slash, a rock n roll soul with a passion for running and exploring new roads. You might know me for shredding guitar solos, but off stage, I trade my Les Paul for a good pair of running shoes. I thrive on endurance, just like in music it\'s all about hitting that rhythm and finding your stride.', 'Riff Runner and Rhythm Pacer 🎸🏃‍♂️'),
(1023, 1019, 'Van', 'male', 45, 'van@gmail.com', '9292993293', 'Bronx', 'Hey, I am Van! Just like my music, I bring energy, rhythm, and speed to every run. Whether it\'s a steady jog or sprint, I love pacing to the beat and pushing limits. Looking for a buddy who shares my drive for consistency and creativity on the road. Let\'s hit the pavement and make some tracks!', 'The Sonic Sprinter and Tempo Tamer 🎶🏃‍♂️');

-- Insert data into messages
INSERT INTO messages (id, sender_id, receiver_id, content, timestamp) VALUES
(2, 1011, 1013, 'Hey Slash, Do you want to run today at 6pm?', '2024-12-16 15:30:25'),
(4, 1013, 1011, 'Yeah of course, I am okay with that!', '2024-12-16 15:32:52'),
(5, 1011, 1015, 'Hey', '2024-12-17 04:01:27'),
(8, 1011, 1013, 'hey', '2024-12-17 08:26:48'),
(9, 1013, 1011, 'Hey Axel', '2024-12-17 08:53:00'),
(10, 1013, 1011, 'Hey', '2024-12-17 08:57:11'),
(11, 1013, 1011, 'Hey', '2024-12-17 09:02:12'),
(12, 1011, 1013, 'Hey', '2024-12-17 09:16:45'),
(13, 1011, 1015, 'Hey', '2024-12-17 09:17:01'),
(14, 1011, 1015, 'Hey', '2024-12-17 09:18:03'),
(15, 1011, 1013, 'Hey', '2024-12-17 09:18:57'),
(16, 1011, 1015, 'Hey', '2024-12-17 09:20:23'),
(17, 1011, 1013, 'Hello', '2024-12-17 09:20:31'),
(18, 1013, 1011, 'hey', '2024-12-17 09:36:18'),
(19, 1013, 1019, 'Hey Van', '2024-12-17 09:44:42');

-- Insert data into workout
INSERT INTO workout (workout_id, workout_date, workout_time, activity, user_id) VALUES
(11, 'Mo', '13:00:00', 'Run', 1011),
(13, 'Tu', '17:00:00', 'Run', 1015),
(14, 'Mo', '17:00:00', 'Run', 1015),
(15, 'We', '17:00:00', 'Run', 1015),
(16, 'Tu', '13:30:00', 'Run', 1011),
(17, 'We', '19:00:00', 'Run', 1011),
(18, 'Th', '22:00:00', 'Run', 1011),
(19, 'Fr', '05:30:00', 'Run', 1011),
(20, 'Mo', '16:00:00', 'Run', 1019),
(21, 'Tu', '02:42:00', 'Run', 1019),
(22, 'We', '17:40:00', 'Run', 1019),
(23, 'Th', '19:00:00', 'Run', 1019),
(24, 'Su', '09:00:00', 'Run', 1019);
