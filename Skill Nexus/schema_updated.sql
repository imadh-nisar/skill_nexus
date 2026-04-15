-- ============================================
-- Modern Career Guidance Platform - Database Schema
-- Updated: April 2026
-- Clean, Efficient, Scalable Structure
-- ============================================

CREATE DATABASE IF NOT EXISTS `career_guidance` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `career_guidance`;

-- ============================================
-- 1. ADMIN USERS TABLE
-- ============================================
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(191) NOT NULL UNIQUE,
  `email` VARCHAR(191) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 2. DEGREES TABLE
-- ============================================
DROP TABLE IF EXISTS `degrees`;
CREATE TABLE `degrees` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `duration` VARCHAR(100),
  `icon` VARCHAR(255),
  `color_code` VARCHAR(7),
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 3. CAREERS TABLE
-- ============================================
DROP TABLE IF EXISTS `careers`;
CREATE TABLE `careers` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `average_salary` VARCHAR(100),
  `job_outlook` TEXT,
  `icon` VARCHAR(255),
  `color_code` VARCHAR(7),
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 4. CAREER-DEGREE RELATIONSHIPS (Many-to-Many)
-- ============================================
DROP TABLE IF EXISTS `career_degrees`;
CREATE TABLE `career_degrees` (
  `career_id` INT UNSIGNED NOT NULL,
  `degree_id` INT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`career_id`, `degree_id`),
  INDEX (`career_id`),
  INDEX (`degree_id`),
  CONSTRAINT `fk_career_degrees_career` FOREIGN KEY (`career_id`) REFERENCES `careers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_career_degrees_degree` FOREIGN KEY (`degree_id`) REFERENCES `degrees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 5. ASSESSMENT QUESTIONS (20 Core Questions)
-- ============================================
DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_text` TEXT NOT NULL,
  `question_type` VARCHAR(50) DEFAULT 'multiple_choice',
  `category` VARCHAR(100),
  `sequence` INT NOT NULL,
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_sequence` (`sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 6. QUESTION OPTIONS/ANSWERS
-- ============================================
DROP TABLE IF EXISTS `question_options`;
CREATE TABLE `question_options` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` INT UNSIGNED NOT NULL,
  `option_text` VARCHAR(255) NOT NULL,
  `option_value` INT NOT NULL,
  `sequence` INT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX (`question_id`),
  CONSTRAINT `fk_question_options_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 7. CAREER-ANSWER SCORING MAPPING
-- ============================================
DROP TABLE IF EXISTS `career_scoring`;
CREATE TABLE `career_scoring` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `career_id` INT UNSIGNED NOT NULL,
  `question_id` INT UNSIGNED NOT NULL,
  `selected_option_value` INT NOT NULL,
  `score_weight` DECIMAL(5,2) NOT NULL DEFAULT 1.00,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX (`career_id`),
  INDEX (`question_id`),
  UNIQUE KEY `unique_combination` (`career_id`, `question_id`, `selected_option_value`),
  CONSTRAINT `fk_career_scoring_career` FOREIGN KEY (`career_id`) REFERENCES `careers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_career_scoring_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 8. TEST RESULTS (For Analytics)
-- ============================================
DROP TABLE IF EXISTS `test_results`;
CREATE TABLE `test_results` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` VARCHAR(255) NOT NULL UNIQUE,
  `career_recommendations` JSON,
  `degree_recommendations` JSON,
  `overall_score` DECIMAL(5,2),
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SEED DATA
-- ============================================

-- Sample Admin User (username: admin, password: hashed version of "admin123")
INSERT INTO `admin_users` (`username`, `email`, `password`) VALUES
  ('admin', 'admin@skillnexus.com', '$2y$10$YourHashedPasswordHere');

-- ============================================
-- DEGREES - Core Educational Paths
-- ============================================
INSERT INTO `degrees` (`name`, `description`, `duration`, `color_code`) VALUES
  ('Computer Science', 'Foundation for software engineering, systems design, and technical innovation', '4 years', '#667eea'),
  ('Data Science', 'Statistics, coding, and machine learning for data-driven insights', '4 years', '#764ba2'),
  ('Business Administration', 'Management, leadership, and business operations', '4 years', '#f093fb'),
  ('Product Design/UX', 'User experience, interface design, and digital creativity', '4 years', '#FF6B6B'),
  ('Psychology', 'Behavior, mind, and human-centered problem solving', '4 years', '#4ECDC4'),
  ('Education & Teaching', 'Curriculum, pedagogy, and learning strategies', '4 years', '#45B7D1'),
  ('Mechanical Engineering', 'Design and analysis of mechanical systems', '4 years', '#96CEB4'),
  ('Electrical Engineering', 'Power systems, electronics, and circuit design', '4 years', '#FFEAA7'),
  ('Civil Engineering', 'Infrastructure, buildings, and urban systems', '4 years', '#DFE6E9'),
  ('Marketing & Communications', 'Branding, strategy, and audience engagement', '4 years', '#A29BFE'),
  ('Finance & Accounting', 'Financial analysis, auditing, and investment strategy', '4 years', '#74B9FF'),
  ('Human Resources', 'Talent management, organization, and culture', '4 years', '#81ECEC'),
  ('Information Technology', 'IT systems, networks, and infrastructure support', '4 years', '#55EFC4'),
  ('Cybersecurity', 'Network security, threat analysis, and protection', '4 years', '#FD79A8'),
  ('Web Development', 'Frontend, backend, and full-stack development', '4 years', '#FDCB6E'),
  ('Artificial Intelligence/ML', 'Machine learning, neural networks, and AI systems', '4 years', '#6C5CE7'),
  ('Environmental Science', 'Ecology, sustainability, and environmental management', '4 years', '#00B894'),
  ('Healthcare Administration', 'Hospital management, health policy, and operations', '4 years', '#FF7675'),
  ('Nursing & Clinical Care', 'Patient care, medical procedures, and health support', '3-4 years', '#E84393'),
  ('Fine Arts & Creative Design', 'Graphic design, visual arts, and creative expression', '4 years', '#A29BFE');

-- ============================================
-- CAREERS - Core Job Roles
-- ============================================
INSERT INTO `careers` (`name`, `description`, `average_salary`, `job_outlook`, `color_code`) VALUES
  ('Software Engineer', 'Design and build software applications using programming languages and frameworks', '$100,000+', 'Excellent growth expected', '#667eea'),
  ('Data Scientist', 'Extract insights from data using statistics, machine learning, and data visualization', '$95,000+', 'Very high demand', '#764ba2'),
  ('Product Manager', 'Guide product strategy, roadmap, and user satisfaction', '$110,000+', 'High growth', '#f093fb'),
  ('UX/UI Designer', 'Create intuitive and beautiful user interfaces for digital products', '$85,000+', 'Strong growth', '#FF6B6B'),
  ('Business Analyst', 'Bridge technology and business needs through requirements analysis', '$90,000+', 'Steady growth', '#4ECDC4'),
  ('Project Manager', 'Plan, execute, and monitor projects to meet business objectives', '$95,000+', 'Consistent demand', '#45B7D1'),
  ('Marketing Manager', 'Develop marketing strategies and manage brand presence', '$85,000+', 'Growing field', '#96CEB4'),
  ('Financial Analyst', 'Analyze financial data and provide investment recommendations', '$75,000+', 'Stable field', '#FFEAA7'),
  ('Accountant', 'Manage financial records, audits, and tax compliance', '$65,000+', 'Always in demand', '#DFE6E9'),
  ('HR Specialist', 'Recruit, train, and support employee development', '$60,000+', 'Growing field', '#A29BFE'),
  ('Cybersecurity Analyst', 'Protect systems and networks from digital threats', '$100,000+', 'High demand', '#FD79A8'),
  ('DevOps Engineer', 'Manage infrastructure, deployment, and system operations', '$110,000+', 'Rapidly growing', '#74B9FF'),
  ('Network Administrator', 'Configure and maintain computer networks', '$70,000+', 'Steady demand', '#81ECEC'),
  ('Full-Stack Developer', 'Build complete web applications front-end to back-end', '$100,000+', 'High demand', '#FDCB6E'),
  ('Mobile App Developer', 'Create applications for iOS and Android platforms', '$95,000+', 'Strong growth', '#55EFC4'),
  ('AI/ML Engineer', 'Develop machine learning models and AI systems', '$120,000+', 'Exceptional growth', '#6C5CE7'),
  ('Environmental Scientist', 'Study ecosystems and develop sustainability solutions', '$65,000+', 'Growing field', '#00B894'),
  ('Hospital Administrator', 'Manage healthcare facility operations and staff', '$90,000+', 'Growing field', '#FF7675'),
  ('Registered Nurse', 'Provide direct patient care and medical support', '$70,000+', 'High demand', '#E84393'),
  ('Graphic Designer', 'Create visual content for print and digital media', '$60,000+', 'Growing field', '#A29BFE');

-- ============================================
-- CAREER-DEGREE RELATIONSHIPS
-- ============================================
INSERT INTO `career_degrees` (`career_id`, `degree_id`) VALUES
  -- Software Engineer - links to multiple degrees
  (1, 1), (1, 16), (1, 14),
  -- Data Scientist
  (2, 1), (2, 2), (2, 16),
  -- Product Manager
  (3, 3), (3, 1), (3, 4),
  -- UX/UI Designer
  (4, 4), (4, 20), (4, 1),
  -- Business Analyst
  (5, 3), (5, 1), (5, 2),
  -- Project Manager
  (6, 3), (6, 10), (6, 5),
  -- Marketing Manager
  (7, 3), (7, 10), (7, 11),
  -- Financial Analyst
  (8, 11), (8, 2), (8, 3),
  -- Accountant
  (9, 11), (9, 3), (9, 2),
  -- HR Specialist
  (10, 12), (10, 5), (10, 3),
  -- Cybersecurity Analyst
  (11, 1), (11, 14), (11, 13),
  -- DevOps Engineer
  (12, 1), (12, 14), (12, 13),
  -- Network Administrator
  (13, 1), (13, 14), (13, 13),
  -- Full-Stack Developer
  (14, 1), (14, 15), (14, 16),
  -- Mobile App Developer
  (15, 1), (15, 15), (15, 14),
  -- AI/ML Engineer
  (16, 1), (16, 2), (16, 16),
  -- Environmental Scientist
  (17, 17), (17, 3), (17, 7),
  -- Hospital Administrator
  (18, 18), (18, 12), (18, 3),
  -- Registered Nurse
  (19, 19), (19, 18), (19, 5),
  -- Graphic Designer
  (20, 20), (20, 4), (20, 10);

-- ============================================
-- 20 ASSESSMENT QUESTIONS
-- ============================================
INSERT INTO `questions` (`question_text`, `category`, `sequence`) VALUES
  ('Do you enjoy working with numbers and data?', 'Interest', 1),
  ('Do you prefer logical problem-solving over creative design?', 'WorkStyle', 2),
  ('Do you prefer working alone rather than in a team?', 'WorkStyle', 3),
  ('Would you enjoy a career that involves frequent travel?', 'Lifestyle', 4),
  ('Do you like explaining concepts to others?', 'Interest', 5),
  ('Are you comfortable with coding and technology?', 'Interest', 6),
  ('Do you prefer stability over risk-taking in your career?', 'Motivation', 7),
  ('Do you enjoy researching and writing?', 'Interest', 8),
  ('Would you like to manage people and projects?', 'Interest', 9),
  ('Do you prefer practical hands-on work over theoretical analysis?', 'WorkStyle', 10),
  ('Are you motivated by high salary opportunities?', 'Motivation', 11),
  ('Do you enjoy solving puzzles and complex problems?', 'Interest', 12),
  ('Would you like to work in healthcare or education?', 'Interest', 13),
  ('Do you prefer working indoors rather than outdoors?', 'Lifestyle', 14),
  ('Are you interested in entrepreneurship and innovation?', 'Motivation', 15),
  ('Do you have strong communication and presentation skills?', 'Interest', 16),
  ('Do you prefer helping others over competing for recognition?', 'WorkStyle', 17),
  ('Are you highly creative and design-focused?', 'Interest', 18),
  ('Do you want a career with leadership development opportunities?', 'Motivation', 19),
  ('Do you enjoy learning about emerging technologies?', 'Interest', 20);

-- ============================================
-- QUESTION OPTIONS - Likert Scale (1-4)
-- ============================================
INSERT INTO `question_options` (`question_id`, `option_text`, `option_value`, `sequence`) VALUES
  -- Q1: Numbers and Data
  (1, 'Strongly Agree', 4, 1), (1, 'Agree', 3, 2), (1, 'Disagree', 2, 3), (1, 'Strongly Disagree', 1, 4),
  -- Q2: Logic vs Creative
  (2, 'Strongly Agree', 4, 1), (2, 'Agree', 3, 2), (2, 'Disagree', 2, 3), (2, 'Strongly Disagree', 1, 4),
  -- Q3: Alone vs Team
  (3, 'Strongly Agree', 4, 1), (3, 'Agree', 3, 2), (3, 'Disagree', 2, 3), (3, 'Strongly Disagree', 1, 4),
  -- Q4: Travel
  (4, 'Strongly Agree', 4, 1), (4, 'Agree', 3, 2), (4, 'Disagree', 2, 3), (4, 'Strongly Disagree', 1, 4),
  -- Q5: Teaching/Explaining
  (5, 'Strongly Agree', 4, 1), (5, 'Agree', 3, 2), (5, 'Disagree', 2, 3), (5, 'Strongly Disagree', 1, 4),
  -- Q6: Coding & Technology
  (6, 'Strongly Agree', 4, 1), (6, 'Agree', 3, 2), (6, 'Disagree', 2, 3), (6, 'Strongly Disagree', 1, 4),
  -- Q7: Stability
  (7, 'Strongly Agree', 4, 1), (7, 'Agree', 3, 2), (7, 'Disagree', 2, 3), (7, 'Strongly Disagree', 1, 4),
  -- Q8: Research & Writing
  (8, 'Strongly Agree', 4, 1), (8, 'Agree', 3, 2), (8, 'Disagree', 2, 3), (8, 'Strongly Disagree', 1, 4),
  -- Q9: Management
  (9, 'Strongly Agree', 4, 1), (9, 'Agree', 3, 2), (9, 'Disagree', 2, 3), (9, 'Strongly Disagree', 1, 4),
  -- Q10: Hands-on Work
  (10, 'Strongly Agree', 4, 1), (10, 'Agree', 3, 2), (10, 'Disagree', 2, 3), (10, 'Strongly Disagree', 1, 4),
  -- Q11: High Salary
  (11, 'Strongly Agree', 4, 1), (11, 'Agree', 3, 2), (11, 'Disagree', 2, 3), (11, 'Strongly Disagree', 1, 4),
  -- Q12: Problem Solving
  (12, 'Strongly Agree', 4, 1), (12, 'Agree', 3, 2), (12, 'Disagree', 2, 3), (12, 'Strongly Disagree', 1, 4),
  -- Q13: Healthcare/Education
  (13, 'Strongly Agree', 4, 1), (13, 'Agree', 3, 2), (13, 'Disagree', 2, 3), (13, 'Strongly Disagree', 1, 4),
  -- Q14: Indoors
  (14, 'Strongly Agree', 4, 1), (14, 'Agree', 3, 2), (14, 'Disagree', 2, 3), (14, 'Strongly Disagree', 1, 4),
  -- Q15: Entrepreneurship
  (15, 'Strongly Agree', 4, 1), (15, 'Agree', 3, 2), (15, 'Disagree', 2, 3), (15, 'Strongly Disagree', 1, 4),
  -- Q16: Communication Skills
  (16, 'Strongly Agree', 4, 1), (16, 'Agree', 3, 2), (16, 'Disagree', 2, 3), (16, 'Strongly Disagree', 1, 4),
  -- Q17: Helping vs Competing
  (17, 'Strongly Agree', 4, 1), (17, 'Agree', 3, 2), (17, 'Disagree', 2, 3), (17, 'Strongly Disagree', 1, 4),
  -- Q18: Creativity & Design
  (18, 'Strongly Agree', 4, 1), (18, 'Agree', 3, 2), (18, 'Disagree', 2, 3), (18, 'Strongly Disagree', 1, 4),
  -- Q19: Leadership Opportunities
  (19, 'Strongly Agree', 4, 1), (19, 'Agree', 3, 2), (19, 'Disagree', 2, 3), (19, 'Strongly Disagree', 1, 4),
  -- Q20: Emerging Technologies
  (20, 'Strongly Agree', 4, 1), (20, 'Agree', 3, 2), (20, 'Disagree', 2, 3), (20, 'Strongly Disagree', 1, 4);

-- ============================================
-- CAREER SCORING MAPPING
-- Maps career to specific answers
-- ============================================
INSERT INTO `career_scoring` (`career_id`, `question_id`, `selected_option_value`, `score_weight`) VALUES
  -- Software Engineer: Strong interest in coding, logic, problem-solving
  (1, 6, 4, 2.0), (1, 6, 3, 1.5), (1, 12, 4, 2.0), (1, 2, 4, 1.5), (1, 20, 4, 1.5),
  -- Data Scientist: Data, logic, technology, problem-solving
  (2, 1, 4, 2.0), (2, 1, 3, 1.5), (2, 12, 4, 2.0), (2, 2, 4, 1.5), (2, 6, 4, 1.5),
  -- Product Manager: Management interest, people skills, communication
  (3, 9, 4, 2.0), (3, 16, 4, 2.0), (3, 11, 4, 1.5), (3, 2, 3, 1.0),
  -- UX/UI Designer: Creativity, design interest, problem-solving
  (4, 18, 4, 2.0), (4, 18, 3, 1.5), (4, 12, 4, 1.5), (4, 2, 2, 1.0),
  -- Business Analyst: Data, analysis, logic, communication
  (5, 1, 4, 1.5), (5, 2, 4, 2.0), (5, 16, 4, 1.5), (5, 8, 3, 1.0),
  -- Project Manager: Management, communication, people skills
  (6, 9, 4, 2.0), (6, 16, 4, 2.0), (6, 11, 4, 1.5),
  -- Marketing Manager: Communication, creativity, people skills
  (7, 16, 4, 2.0), (7, 18, 3, 1.5), (7, 11, 4, 1.5), (7, 5, 3, 1.0),
  -- Financial Analyst: Numbers, data, logic
  (8, 1, 4, 2.0), (8, 2, 4, 1.5), (8, 7, 4, 1.5),
  -- Accountant: Numbers, stability, details focus
  (9, 1, 4, 2.0), (9, 7, 4, 2.0), (9, 3, 4, 1.0),
  -- HR Specialist: People, communication, helping
  (10, 16, 4, 2.0), (10, 17, 4, 2.0), (10, 5, 3, 1.5),
  -- Cybersecurity Analyst: Technology, logic, problem-solving
  (11, 6, 4, 2.0), (11, 12, 4, 2.0), (11, 2, 4, 1.5),
  -- DevOps Engineer: Technology, logic, problem-solving
  (12, 6, 4, 2.0), (12, 2, 4, 1.5), (12, 20, 4, 2.0),
  -- Network Administrator: Technology, stability
  (13, 6, 3, 1.5), (13, 7, 4, 1.5), (13, 3, 3, 1.0),
  -- Full-Stack Developer: Coding, creativity, technology
  (14, 6, 4, 2.0), (14, 18, 3, 1.5), (14, 2, 3, 1.5),
  -- Mobile App Developer: Coding, creativity, problem-solving
  (15, 6, 4, 2.0), (15, 12, 4, 1.5), (15, 18, 3, 1.0),
  -- AI/ML Engineer: Data, learning, problem-solving, technology
  (16, 1, 4, 1.5), (16, 12, 4, 2.0), (16, 20, 4, 2.0), (16, 6, 4, 2.0),
  -- Environmental Scientist: Research, outdoors interest (inverse on Q14)
  (17, 8, 4, 2.0), (17, 14, 2, 1.5), (17, 14, 1, 2.0),
  -- Hospital Administrator: Management, people, helping
  (18, 9, 4, 2.0), (18, 16, 3, 1.5), (18, 17, 3, 1.5),
  -- Registered Nurse: Healthcare interest, helping, people
  (19, 13, 4, 2.0), (19, 17, 4, 2.0), (19, 5, 3, 1.0),
  -- Graphic Designer: Creativity, design, problem-solving
  (20, 18, 4, 2.0), (20, 12, 3, 1.5), (20, 2, 2, 1.0);

-- ============================================
-- END OF SCHEMA
-- ============================================
COMMIT;