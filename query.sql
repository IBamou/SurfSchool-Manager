CREATE DATABASE IF NOT EXISTS surfManager;

USE surfManager;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    level ENUM('Beginner', 'Intermediate', 'Advanced', 'Expert') DEFAULT 'Beginner',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS coaches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    speciality VARCHAR(255),
    experience INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS lessons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    level ENUM('Beginner', 'Intermediate', 'Advanced', 'Expert') DEFAULT 'Beginner',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lesson_id INT,
    coach_id INT,
    datetime DATETIME NOT NULL,
    duration INT DEFAULT 60,
    location VARCHAR(255),
    price DECIMAL(10,2) DEFAULT 0.00,
    requirements TEXT,

    status ENUM('available', 'completed', 'cancelled') DEFAULT 'available',

    max_spots INT DEFAULT 8,
    spots_available INT DEFAULT 8,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    FOREIGN KEY (coach_id) REFERENCES coaches(id) ON DELETE CASCADE,

    CHECK (spots_available >= 0),
    CHECK (spots_available <= max_spots),

    INDEX (lesson_id),
    INDEX (coach_id),
    INDEX (datetime)
);

CREATE TABLE IF NOT EXISTS assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    student_id INT NOT NULL,
    payment_status ENUM('pending', 'paid', 'refunded') DEFAULT 'pending',
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES sessions(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Sample Data

INSERT INTO users (name, email, password, role) VALUES 
('Admin User', 'admin@surf.com', 'password123', 'admin'),
('John Smith', 'john@email.com', 'password123', 'user'),
('Sarah Johnson', 'sarah@email.com', 'password123', 'user'),
('Mike Davis', 'mike@email.com', 'password123', 'user'),
('Emma Wilson', 'emma@email.com', 'password123', 'user'),
('David Brown', 'david@email.com', 'password123', 'user'),
('Lisa Garcia', 'lisa@email.com', 'password123', 'user'),
('Tom Martinez', 'tom@email.com', 'password123', 'user'),
('Amy Lee', 'amy@email.com', 'password123', 'user'),
('Chris Taylor', 'chris@email.com', 'password123', 'user'),
('Rachel Green', 'rachel@email.com', 'password123', 'user'),
('Kevin Wong', 'kevin@email.com', 'password123', 'user'),
('Jessica Adams', 'jessica@email.com', 'password123', 'user'),
('Brian Clark', 'brian@email.com', 'password123', 'user'),
('Nicole Reed', 'nicole@email.com', 'password123', 'user'),
('Tyler Hill', 'tyler@email.com', 'password123', 'user');

INSERT INTO students (user_id, level) VALUES 
(2, 'Beginner'),
(3, 'Intermediate'),
(4, 'Beginner'),
(5, 'Advanced'),
(6, 'Intermediate'),
(7, 'Beginner'),
(8, 'Expert'),
(9, 'Beginner'),
(10, 'Intermediate'),
(11, 'Advanced'),
(12, 'Beginner'),
(13, 'Intermediate'),
(14, 'Beginner'),
(15, 'Intermediate');

INSERT INTO coaches (name, email, speciality, experience) VALUES 
('Mike Ocean', 'coach.mike@surf.com', 'Beginner Friendly', 10),
('Sarah Wave', 'coach.sarah@surf.com', 'Advanced Techniques', 15),
('Carlos Surf', 'coach.carlos@surf.com', 'All Levels', 8),
('Jessica Blue', 'coach.jessica@surf.com', 'Competition Training', 12),
('Ryan Tide', 'coach.ryan@surf.com', 'Kids & Families', 6),
('Mia Reef', 'coach.mia@surf.com', 'Longboard Style', 9);

INSERT INTO lessons (title, description, level) VALUES 
('Beginner Surf Basics', 'Learn the fundamentals of surfing including ocean safety, paddling technique, and how to stand up on the board. Perfect for first-timers!', 'Beginner'),
('Intermediate Wave Riding', 'Improve your skills with better wave selection, timing, and basic turns. Start catching green waves!', 'Intermediate'),
('Advanced Surf Technique', 'Master advanced maneuvers like bottom turns, top turns, and linking maneuvers. Video analysis included.', 'Advanced'),
('Surf Fitness & Theory', 'Learn about surf etiquette, ocean awareness, reading waves, and conditioning exercises for better performance.', 'Expert'),
('Kids Surf Camp', 'Fun and safe surfing lessons designed specifically for children ages 8-14. Small groups with patient instructors.', 'Beginner'),
('Private Coaching', 'One-on-one personalized coaching tailored to your specific goals and skill level. Fast-track your progress!', 'Intermediate'),
('Longboard Basics', 'Learn the art of longboarding with proper stance, noseriding, and cross-step technique.', 'Intermediate'),
('Competition Prep', 'Prepare for surf competitions with heat strategy, wave selection under pressure, and professional techniques.', 'Advanced');

INSERT INTO sessions (lesson_id, coach_id, datetime, duration, location, price, requirements, max_spots, spots_available, status) VALUES 
-- April 2026
(1, 1, '2026-04-15 09:00:00', 90, 'Main Beach - Left Point', 45.00, 'Bring swimwear and towel. Wetsuit provided.', 6, 3, 'available'),
(1, 1, '2026-04-15 14:00:00', 90, 'Main Beach - Left Point', 45.00, 'Bring swimwear and towel. Wetsuit provided.', 6, 5, 'available'),
(2, 2, '2026-04-16 08:00:00', 60, 'North Reef - Right Break', 55.00, 'Must be able to paddle and stand. Own board recommended.', 8, 4, 'available'),
(2, 2, '2026-04-16 14:00:00', 60, 'North Reef - Right Break', 55.00, 'Must be able to paddle and stand. Own board recommended.', 8, 7, 'available'),
(3, 2, '2026-04-17 07:00:00', 120, 'Secret Spot - South Shore', 85.00, 'Advanced surfers only. Video analysis included.', 4, 1, 'available'),
(3, 4, '2026-04-17 14:00:00', 90, 'Main Beach - Center', 75.00, 'Video analysis included. Bring your own board.', 6, 3, 'available'),
(4, 3, '2026-04-18 10:00:00', 120, 'Surf School - Classroom', 35.00, 'Classroom session. Bring notebook.', 12, 8, 'available'),
(5, 5, '2026-04-18 09:00:00', 60, 'Kids Beach - South Shore', 35.00, 'For ages 8-14. All equipment provided.', 8, 5, 'available'),
(1, 1, '2026-04-18 09:00:00', 90, 'Main Beach - Left Point', 45.00, 'Bring swimwear and towel. Wetsuit provided.', 6, 4, 'available'),
(2, 3, '2026-04-19 08:00:00', 60, 'North Reef - Right Break', 55.00, 'Must be able to paddle and stand.', 8, 6, 'available'),
(3, 4, '2026-04-19 15:00:00', 90, 'Secret Spot - South Shore', 75.00, 'Advanced level required.', 6, 5, 'available'),
(6, 1, '2026-04-20 10:00:00', 60, 'Your Choice of Location', 120.00, 'One-on-one coaching session.', 1, 1, 'available'),
(7, 6, '2026-04-20 09:00:00', 90, 'Main Beach - Center', 65.00, 'Longboard provided if needed.', 6, 4, 'available'),
(1, 5, '2026-04-21 09:00:00', 90, 'Main Beach - Left Point', 45.00, 'Beginner friendly session.', 6, 3, 'available'),
(2, 2, '2026-04-21 14:00:00', 60, 'North Reef - Right Break', 55.00, 'Intermediate wave riding.', 8, 5, 'available'),
(8, 4, '2026-04-22 08:00:00', 180, 'Competition Beach - Main', 150.00, 'Competition prep. Video analysis included.', 6, 4, 'available'),
-- May 2026
(1, 1, '2026-05-01 09:00:00', 90, 'Main Beach - Left Point', 45.00, 'Beginner basics.', 6, 2, 'available'),
(2, 3, '2026-05-01 14:00:00', 60, 'North Reef - Right Break', 55.00, 'Green wave techniques.', 8, 6, 'available'),
(3, 2, '2026-05-02 07:00:00', 120, 'Secret Spot - South Shore', 85.00, 'Advanced maneuvers.', 4, 2, 'available'),
(5, 5, '2026-05-02 10:00:00', 60, 'Kids Beach - South Shore', 35.00, 'Kids camp session.', 8, 7, 'available'),
(4, 3, '2026-05-03 10:00:00', 120, 'Surf School - Classroom', 35.00, 'Theory and fitness.', 12, 9, 'available'),
(6, 1, '2026-05-03 14:00:00', 60, 'Main Beach - Left Point', 120.00, 'Private coaching.', 1, 1, 'available'),
(7, 6, '2026-05-04 09:00:00', 90, 'Main Beach - Center', 65.00, 'Longboard style.', 6, 5, 'available'),
(1, 5, '2026-05-04 14:00:00', 90, 'Main Beach - Left Point', 45.00, 'Beginner friendly.', 6, 4, 'available'),
(2, 2, '2026-05-05 08:00:00', 60, 'North Reef - Right Break', 55.00, 'Timing improvement.', 8, 3, 'available'),
(8, 4, '2026-05-05 15:00:00', 180, 'Competition Beach - Main', 150.00, 'Competition training.', 6, 5, 'available'),
-- Completed sessions
(1, 1, '2026-03-20 09:00:00', 90, 'Main Beach - Left Point', 45.00, 'Completed session.', 6, 0, 'completed'),
(2, 2, '2026-03-21 10:00:00', 60, 'North Reef - Right Break', 55.00, 'Completed session.', 8, 0, 'completed'),
(3, 4, '2026-03-22 08:00:00', 120, 'Secret Spot - South Shore', 85.00, 'Completed session.', 4, 0, 'completed'),
(5, 5, '2026-03-23 09:00:00', 60, 'Kids Beach - South Shore', 35.00, 'Completed kids session.', 8, 0, 'completed'),
(1, 1, '2026-03-25 14:00:00', 90, 'Main Beach - Left Point', 45.00, 'Completed session.', 6, 0, 'completed'),
-- Cancelled sessions
(2, 3, '2026-03-15 08:00:00', 60, 'North Reef - Right Break', 55.00, 'Cancelled due to weather.', 8, 8, 'cancelled'),
(3, 2, '2026-03-18 07:00:00', 120, 'Secret Spot - South Shore', 85.00, 'Cancelled due to conditions.', 4, 4, 'cancelled');

INSERT INTO assignments (session_id, student_id, payment_status) VALUES 
-- April 15
(1, 1, 'paid'),
(1, 2, 'paid'),
(1, 3, 'paid'),
(2, 4, 'paid'),
(2, 5, 'pending'),
-- April 16
(3, 2, 'paid'),
(3, 3, 'paid'),
(3, 6, 'paid'),
(4, 7, 'paid'),
(4, 8, 'paid'),
(4, 9, 'paid'),
(4, 10, 'paid'),
-- April 17
(5, 5, 'paid'),
(5, 11, 'paid'),
(5, 12, 'paid'),
(6, 6, 'paid'),
(6, 13, 'paid'),
(6, 14, 'paid'),
-- April 18
(7, 7, 'paid'),
(7, 8, 'paid'),
(7, 9, 'paid'),
(7, 10, 'paid'),
(8, 2, 'paid'),
(8, 4, 'paid'),
(8, 6, 'paid'),
(9, 1, 'paid'),
(9, 3, 'paid'),
-- April 19
(10, 5, 'paid'),
(10, 11, 'paid'),
(11, 12, 'paid'),
(11, 13, 'paid'),
(11, 14, 'paid'),
-- April 20-22
(12, 1, 'paid'),
(13, 2, 'paid'),
(13, 3, 'paid'),
(14, 4, 'paid'),
(14, 6, 'paid'),
(15, 7, 'pending'),
(16, 8, 'paid'),
(16, 9, 'paid'),
(16, 10, 'paid'),
-- May sessions (some paid)
(17, 1, 'paid'),
(17, 2, 'pending'),
(17, 3, 'pending'),
(18, 4, 'paid'),
(18, 5, 'paid'),
(19, 11, 'paid'),
(19, 12, 'pending'),
(20, 6, 'paid'),
(20, 7, 'paid'),
(21, 8, 'paid'),
(21, 9, 'paid'),
(22, 1, 'paid'),
(23, 2, 'pending'),
(23, 3, 'pending'),
(24, 4, 'paid'),
(24, 5, 'paid'),
(24, 6, 'paid'),
(25, 7, 'paid'),
(26, 8, 'pending'),
(26, 9, 'pending'),
(26, 10, 'pending');
