-- Drop tables in reverse dependency order to avoid FK issues
use tms;
DROP TABLE IF EXISTS attachments;
DROP TABLE IF EXISTS evaluations;
DROP TABLE IF EXISTS answers;
drop table if exists assigned_questions;
DROP TABLE IF EXISTS assessment_questions;
DROP TABLE IF EXISTS questions;
DROP TABLE IF EXISTS assessments;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS users;

-- 1. USERS
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role ENUM('student', 'teacher', 'admin') DEFAULT 'student'
);



-- 3. ASSESSMENTS
CREATE TABLE assessments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT
);

-- 4. QUESTIONS
CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_text TEXT NOT NULL,
    model_answer text not null,
    question_type ENUM('text', 'mcq', 'code') DEFAULT 'text'
    );

-- 5. ASSESSMENT_QUESTIONS
CREATE TABLE assessment_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assessment_id INT NOT NULL,
    question_id INT NOT NULL,
    FOREIGN KEY (assessment_id) REFERENCES assessments(id),
    FOREIGN KEY (question_id) REFERENCES questions(id),
    UNIQUE (assessment_id, question_id)
);

-- 6. ANSWERS
-- 6. ANSWERS
-- 6. ANSWERS
CREATE TABLE answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assessment_id INT NOT NULL,
    question_id INT NOT NULL,
    student_id INT NOT NULL,
    attempt_number INT NOT NULL DEFAULT 1, -- attempt number starts at 1
    answer_text TEXT,
    ai_score INT DEFAULT NULL,
    ai_feedback TEXT DEFAULT NULL,
    FOREIGN KEY (assessment_id, question_id) REFERENCES assessment_questions(assessment_id, question_id),
    FOREIGN KEY (question_id) REFERENCES questions(id),
    FOREIGN KEY (student_id) REFERENCES users(id),
    UNIQUE (assessment_id, question_id, student_id, attempt_number)
);
-- 1. USERS
INSERT INTO users (name, role) VALUES
('Alice Johnson', 'student'),
('Bob Smith', 'student'),
('Carol Lee', 'student'),
('David Brown', 'student'),
('Eve Davis', 'teacher');

-- 2. ASSESSMENTS
INSERT INTO assessments (title, description) VALUES
('Intro to Programming Assessment', 'Assessment covering basic programming concepts.');
INSERT INTO assessments (title, description) VALUES
('Intro to Algorithms', 'Assessment covering algorithm concepts.');
-- 3. QUESTIONS
INSERT INTO questions (question_text, model_answer, question_type) VALUES
('What is a variable in programming?', 'A variable stores data values.', 'text'),
('Write a Python function to add two numbers.', 'def add(a, b): return a + b', 'code'),
('Explain the concept of a function.', 'A function is a block of code that performs a specific task.', 'text'),
('What does IDE stand for?', 'Integrated Development Environment.', 'text');

-- 4. ASSESSMENT_QUESTIONS
INSERT INTO assessment_questions (assessment_id, question_id) VALUES
(2,5),
(2,6),
(2,7),
(2,8);
select * from assessments;
-- 5. ANSWERS (Each student answers each question)
-- For simplicity, each student answers Question 1 only
-- INSERT INTO answers (assessment_id, question_id, student_id, answer_text, ai_score, ai_feedback) VALUES
-- (1,1,1,'A variable is a container for storing values.',4,'Good understanding, minor detail missing.'),
-- (1,1,2,'Stores data that can change.',5,'Excellent answer.'),
-- (1,1,3,'It holds information.',3,'Too brief, please elaborate.'),
-- (1,1,4,'Variable is used to store data.',4,'Correct, but can be more precise.'),
-- (1,1,1,2,'A variable keeps a value.',4,'Clear and concise.');

-- If you want each student to answer all 5 questions, you can generate more rows accordingly:
-- Example of inserting all 5 questions for all 4 students (students 1-4), skipping for brevity.


-- 7. EVALUATIONS
-- CREATE TABLE evaluations (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     answer_id INT NOT NULL,
--     graded_by INT NOT NULL,
--     marks_awarded INT DEFAULT 0,
--     feedback TEXT,
--     used_ai BOOLEAN DEFAULT FALSE,
--     graded_at DATETIME,
--     created DATETIME,
--     modified DATETIME,
--     FOREIGN KEY (answer_id) REFERENCES answers(id),
--     FOREIGN KEY (graded_by) REFERENCES users(id)
-- );

-- 8. ATTACHMENTS
-- CREATE TABLE attachments (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     answer_id INT,
--     file_path VARCHAR(255) NOT NULL,
--     uploaded_at DATETIME,
--     FOREIGN KEY (answer_id) REFERENCES answers(id)
-- );
-- Insert 5 questions about AI with max_marks = 1
use tms;
select * from answers;
SET SQL_SAFE_UPDATES = 0;
update answers set ai_feedback=null ,ai_score=null where 1 >0;
INSERT INTO questions (question_text, model_answer, question_type) VALUES
(
  'What is an algorithm?',
  'An algorithm is a step-by-step procedure or set of rules designed to perform a specific task or solve a particular problem.',
  'text'
),
(
  'Explain the difference between a flowchart and pseudocode.',
  'A flowchart is a diagrammatic representation of an algorithm using symbols and arrows to show the flow of control, while pseudocode is a written, structured description of the steps in plain language that resembles programming logic.',
  'text'
),
(
  'Name two characteristics of a good algorithm.',
  'Two characteristics of a good algorithm are: (1) Finiteness – it must always terminate after a finite number of steps; and (2) Effectiveness – each step must be clear and unambiguous.',
  'text'
),
(
  'What does it mean for an algorithm to be efficient?',
  'An efficient algorithm solves the problem correctly using the least amount of resources possible, such as minimal time (fast execution) and minimal memory (space).',
  'text'
);
