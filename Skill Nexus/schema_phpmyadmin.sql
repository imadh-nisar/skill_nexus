-- Modern Database Schema for Skill Nexus (Career Guidance)  
-- Updated: April 2026
-- Optimized for phpMyAdmin - InnoDB, UTF8MB4, proper constraints
-- No user login required for main platform

CREATE DATABASE IF NOT EXISTS `career_guidance` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `career_guidance`;

-- Admin users (ONLY for dashboard admin access)
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(191) NOT NULL UNIQUE,
  `email` VARCHAR(191) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Degrees/Educational paths
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Careers - unlimited entries
DROP TABLE IF EXISTS `careers`;
CREATE TABLE `careers` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `average_salary` VARCHAR(100),
  `job_outlook` TEXT,
  `icon` VARCHAR(255),
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Career-Degree relationships (many-to-many)
DROP TABLE IF EXISTS `career_degrees`;
CREATE TABLE `career_degrees` (
  `career_id` INT UNSIGNED NOT NULL,
  `degree_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`career_id`, `degree_id`),
  KEY `idx_career_id` (`career_id`),
  KEY `idx_degree_id` (`degree_id`),
  CONSTRAINT `fk_career_degrees_career` FOREIGN KEY (`career_id`) REFERENCES `careers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_career_degrees_degree` FOREIGN KEY (`degree_id`) REFERENCES `degrees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Assessment Questions (20 core questions for career test)
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Question Options/Answers
DROP TABLE IF EXISTS `question_options`;
CREATE TABLE `question_options` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` INT UNSIGNED NOT NULL,
  `option_text` VARCHAR(255) NOT NULL,
  `option_value` INT NOT NULL,
  `sequence` INT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_question_id` (`question_id`),
  CONSTRAINT `fk_question_options_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Career-Answer Scoring Mapping (maps answer patterns to careers)
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
  KEY `idx_career_id` (`career_id`),
  KEY `idx_question_id` (`question_id`),
  UNIQUE KEY `unique_combination` (`career_id`, `question_id`, `selected_option_value`),
  CONSTRAINT `fk_career_scoring_career` FOREIGN KEY (`career_id`) REFERENCES `careers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_career_scoring_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Test Results (for analytics - anonymous session-based)
DROP TABLE IF EXISTS `test_results`;
CREATE TABLE `test_results` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` VARCHAR(255) NOT NULL UNIQUE,
  `career_recommendations` JSON,
  `degree_recommendations` JSON,
  `overall_score` DECIMAL(5,2),
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ============================================
-- SEED DATA - Degrees (50+ entries)
-- ============================================

INSERT INTO `degrees` (`name`, `description`, `duration`, `icon`, `color_code`) VALUES
  ('Computer Science', 'Computer Science is the foundation for software engineering, systems design, and technical problem-solving.', '4 years', '💻', '#667eea'),
  ('Data Science', 'Data Science combines statistics, coding, and domain expertise to extract insights from data.', '2 years', '📊', '#764ba2'),
  ('Business Administration', 'Focuses on management, leadership, and business operations.', '4 years', '📋', '#f093fb'),
  ('Design', 'Covers visual communication, UX/UI, and creativity-driven problem solving.', '4 years', '🎨', '#4facfe'),
  ('Psychology', 'Study of behavior and mind, useful for counseling and HR roles.', '4 years', '🧠', '#43e97b'),
  ('Education', 'Training in teaching strategies, curriculum development, and learning theory.', '4 years', '📚', '#fa709a'),
  ('Mechanical Engineering', 'Designs and builds mechanical systems and devices.', '4 years', '⚙️', '#30b0fe'),
  ('Electrical Engineering', 'Focuses on electrical systems, circuits, and power.', '4 years', '⚡', '#ffa400'),
  ('Civil Engineering', 'Design and construction of buildings, roads, and infrastructure.', '4 years', '🏗️', '#ff6b6b'),
  ('Marketing', 'Business promotion, branding, and customer engagement.', '4 years', '📢', '#ee0979'),
  ('Finance', 'Financial planning, analysis, and investment strategies.', '4 years', '💰', '#ffd89b'),
  ('Accounting', 'Professional bookkeeping, auditing, and financial reporting.', '4 years', '📑', '#19547b'),
  ('Human Resources', 'Managing talent, recruiting, and organizational culture.', '4 years', '👥', '#ff9a56'),
  ('Environmental Science', 'Study of environmental systems and sustainability.', '4 years', '🌱', '#00d084'),
  ('Digital Media', 'Creation and management of digital content and platforms.', '4 years', '📱', '#667eea'),
  ('Healthcare Administration', 'Management of healthcare facilities and operations.', '4 years', '🏥', '#f093fb'),
  ('Public Relations', 'Communication strategy and media relations.', '4 years', '📢', '#4facfe'),
  ('Information Systems', 'Technology systems used to manage business operations.', '4 years', '🗄️', '#43e97b'),
  ('Cybersecurity', 'Protecting systems and networks from digital attacks.', '4 years', '🔒', '#fa709a'),
  ('Supply Chain Management', 'Planning and managing product supply chains.', '4 years', '📦', '#30b0fe'),
  ('International Relations', 'Global politics, diplomacy, and policy analysis.', '4 years', '🌍', '#ffa400'),
  ('Law', 'Study of legal systems, regulations, and advocacy.', '3 years', '⚖️', '#ff6b6b'),
  ('Architecture', 'Design of buildings, spaces, and urban environments.', '5 years', '🏢', '#ee0979'),
  ('Biomedical Engineering', 'Engineering solutions for medical applications.', '4 years', '🩺', '#ffd89b'),
  ('Journalism', 'Reporting, storytelling, and news production.', '4 years', '📰', '#19547b'),
  ('Film & Media', 'Production and analysis of film, TV, and digital media.', '4 years', '🎬', '#ff9a56'),
  ('Graphic Design', 'Visual design for print and digital media.', '4 years', '🖼️', '#00d084'),
  ('Statistics', 'Mathematical analysis of data and probability.', '4 years', '📊', '#667eea'),
  ('Economics', 'Study of markets, resources, and economic systems.', '4 years', '💹', '#f093fb'),
  ('Sports Management', 'Business operations and marketing in sports.', '4 years', '⚽', '#4facfe'),
  ('Hospitality Management', 'Running hotels, restaurants, and guest services.', '4 years', '🏨', '#43e97b'),
  ('Philosophy', 'Critical thinking, ethics, and logic.', '4 years', '💭', '#fa709a'),
  ('Sustainability Studies', 'Approaches to environmental and social sustainability.', '4 years', '♻️', '#30b0fe'),
  ('Human-Computer Interaction', 'Design of interactive systems and user experiences.', '4 years', '🖱️', '#ffa400'),
  ('Robotics', 'Design and control of autonomous machines.', '4 years', '🤖', '#ff6b6b'),
  ('Data Engineering', 'Building data pipelines and analytics infrastructure.', '4 years', '🔧', '#ee0979'),
  ('Project Management', 'Planning, executing, and monitoring projects.', '2 years', '📋', '#ffd89b'),
  ('Sales', 'Client relationships and revenue generation.', '2 years', '💼', '#19547b'),
  ('Social Work', 'Supporting individuals, families, and communities.', '4 years', '🤝', '#ff9a56'),
  ('UX Research', 'Studying user behavior to inform product design.', '4 years', '🔬', '#00d084'),
  ('Digital Marketing', 'Online marketing, social media and analytics.', '4 years', '📱', '#667eea'),
  ('Web Development', 'Building websites and web applications.', '4 years', '🌐', '#f093fb'),
  ('Game Design', 'Creating gameplay experiences for digital games.', '4 years', '🎮', '#4facfe'),
  ('Software Engineering', 'Engineering principles applied to software development.', '4 years', '💻', '#43e97b'),
  ('Network Engineering', 'Design and maintenance of computer networks.', '4 years', '🌐', '#fa709a'),
  ('Biotechnology', 'Biological innovation and product development.', '4 years', '🧬', '#30b0fe'),
  ('Entrepreneurship', 'Launching businesses and innovation management.', '4 years', '🚀', '#ffa400'),
  ('Logistics', 'Transportation planning and distribution networks.', '4 years', '🚚', '#ff6b6b'),
  ('Nursing', 'Clinical care and health support.', '4 years', '⚕️', '#ee0979'),
  ('Cloud Computing', 'Distributed systems and cloud platforms.', '4 years', '☁️', '#ffd89b'),
  ('Artificial Intelligence', 'Machine learning and intelligent systems.', '4 years', '🧠', '#19547b');

-- ============================================
-- SEED DATA - Careers (40+ sample entries)
-- ============================================

INSERT INTO `careers` (`name`, `description`, `icon`, `average_salary`, `job_outlook`) VALUES
  ('Software Developer', 'Builds applications, systems, and software solutions.', '💻', '$120,000+', 'Growing'),
  ('Data Analyst', 'Analyzes data to provide insights and inform decisions.', '📊', '$85,000+', 'Strong'),
  ('Project Manager', 'Coordinates teams, plans projects, and delivers outcomes.', '📋', '$100,000+', 'Growing'),
  ('UI/UX Designer', 'Designs user experiences and interfaces for digital products.', '🎨', '$95,000+', 'Strong'),
  ('Business Analyst', 'Translates business needs into technical requirements.', '🔍', '$90,000+', 'Growing'),
  ('Marketing Manager', 'Leads marketing strategy and campaigns.', '📢', '$105,000+', 'Moderate'),
  ('Financial Analyst', 'Evaluates financial data and supports decisions.', '💰', '$95,000+', 'Stable'),
  ('HR Specialist', 'Recruits, trains, and supports employees.', '👥', '$75,000+', 'Growing'),
  ('Mechanical Engineer', 'Designs and tests mechanical systems.', '⚙️', '$92,000+', 'Stable'),
  ('Electrical Engineer', 'Develops electrical systems and circuits.', '⚡', '$98,000+', 'Strong'),
  ('Civil Engineer', 'Plans and builds public infrastructure projects.', '🏗️', '$95,000+', 'Moderate'),
  ('UX Researcher', 'Studies user behavior to improve products.', '🔬', '$100,000+', 'Growing'),
  ('Content Strategist', 'Plans and creates content across platforms.', '✍️', '$85,000+', 'Growing'),
  ('Data Engineer', 'Builds and maintains data pipelines.', '🔧', '$115,000+', 'Very Strong'),
  ('Cybersecurity Analyst', 'Protects systems from security threats.', '🔒', '$110,000+', 'Very Strong'),
  ('Network Administrator', 'Maintains computer networks and systems.', '🌐', '$85,000+', 'Moderate'),
  ('Product Manager', 'Defines product vision and leads delivery.', '🎯', '$130,000+', 'Growing'),
  ('Operations Manager', 'Oversees operational efficiency and processes.', '⚡', '$95,000+', 'Moderate'),
  ('Event Planner', 'Coordinates events and logistics.', '🎉', '$70,000+', 'Growing'),
  ('Digital Marketing Specialist', 'Executes online marketing strategies.', '📱', '$80,000+', 'Growing'),
  ('Graphic Designer', 'Creates visual communications and branding.', '🖼️', '$80,000+', 'Moderate'),
  ('Architect', 'Designs buildings and structural concepts.', '🏢', '$100,000+', 'Moderate'),
  ('Attorney', 'Provides legal advice and representation.', '⚖️', '$140,000+', 'Stable'),
  ('Teacher', 'Educates students in a classroom setting.', '📚', '$65,000+', 'Moderate'),
  ('Nurse', 'Provides clinical care and patient support.', '⚕️', '$85,000+', 'Very Strong'),
  ('Environmental Consultant', 'Advises on sustainability and compliance.', '🌱', '$88,000+', 'Growing'),
  ('Healthcare Administrator', 'Manages healthcare operations and policy.', '🏥', '$105,000+', 'Growing'),
  ('Supply Chain Analyst', 'Optimizes supply chain processes.', '📦', '$90,000+', 'Growing'),
  ('Logistics Coordinator', 'Coordinates transportation and inventory.', '🚚', '$72,000+', 'Growing'),
  ('Social Worker', 'Supports individuals and community services.', '🤝', '$68,000+', 'Growing'),
  ('Journalist', 'Researches and reports news stories.', '📰', '$70,000+', 'Declining'),
  ('Game Developer', 'Designs and creates video games.', '🎮', '$110,000+', 'Growing'),
  ('Machine Learning Engineer', 'Builds machine learning systems and models.', '🤖', '$135,000+', 'Very Strong'),
  ('Cloud Architect', 'Designs cloud infrastructure and solutions.', '☁️', '$130,000+', 'Very Strong'),
  ('Database Administrator', 'Manages databases and data security.', '🗄️', '$105,000+', 'Moderate'),
  ('Quality Assurance Engineer', 'Tests and verifies software quality.', '✓', '$90,000+', 'Growing'),
  ('Technical Writer', 'Creates documentation and technical content.', '📝', '$80,000+', 'Moderate'),
  ('Urban Planner', 'Designs policies for city development.', '🌆', '$85,000+', 'Moderate'),
  ('Sustainability Specialist', 'Promotes environmental best practices.', '♻️', '$82,000+', 'Growing'),
  ('AI Researcher', 'Advances artificial intelligence research.', '🧠', '$130,000+', 'Very Strong');

-- ============================================
-- SEED DATA - 20 Assessment Questions
-- ============================================

INSERT INTO `questions` (`question_text`, `category`, `sequence`, `question_type`) VALUES
  ('Do you enjoy working with numbers and data?', 'Interest', 1, 'multiple_choice'),
  ('Do you prefer logical problem-solving over creative design?', 'WorkStyle', 2, 'multiple_choice'),
  ('Do you prefer working alone rather than in a team?', 'WorkStyle', 3, 'multiple_choice'),
  ('Would you enjoy a career that involves frequent travel?', 'Lifestyle', 4, 'multiple_choice'),
  ('Do you like explaining concepts to others?', 'Interest', 5, 'multiple_choice'),
  ('Are you comfortable with coding and technology?', 'Interest', 6, 'multiple_choice'),
  ('Do you prefer stability over risk-taking in your career?', 'Motivation', 7, 'multiple_choice'),
  ('Do you enjoy researching and writing?', 'Interest', 8, 'multiple_choice'),
  ('Would you like to manage people and projects?', 'Interest', 9, 'multiple_choice'),
  ('Do you prefer practical hands-on work over theoretical analysis?', 'WorkStyle', 10, 'multiple_choice'),
  ('Are you motivated by high salary rather than personal satisfaction?', 'Motivation', 11, 'multiple_choice'),
  ('Do you enjoy solving puzzles and complex problems?', 'Interest', 12, 'multiple_choice'),
  ('Would you like to work in healthcare or education?', 'Interest', 13, 'multiple_choice'),
  ('Do you prefer working indoors rather than outdoors?', 'Lifestyle', 14, 'multiple_choice'),
  ('Are you interested in entrepreneurship and starting your own business?', 'Motivation', 15, 'multiple_choice'),
  ('Do you have strong communication skills?', 'Interest', 16, 'multiple_choice'),
  ('Do you prefer helping others over competing?', 'WorkStyle', 17, 'multiple_choice'),
  ('Are you highly creative and design-focused?', 'Interest', 18, 'multiple_choice'),
  ('Do you want a career with leadership opportunities?', 'Motivation', 19, 'multiple_choice'),
  ('Do you enjoy learning about emerging technologies?', 'Interest', 20, 'multiple_choice');

-- ============================================
-- SEED DATA - Question Options (1-4 Likert Scale)
-- ============================================

INSERT INTO `question_options` (`question_id`, `option_text`, `option_value`, `sequence`) VALUES
  (1, 'Strongly Agree', 4, 1), (1, 'Agree', 3, 2), (1, 'Disagree', 2, 3), (1, 'Strongly Disagree', 1, 4),
  (2, 'Strongly Agree', 4, 1), (2, 'Agree', 3, 2), (2, 'Disagree', 2, 3), (2, 'Strongly Disagree', 1, 4),
  (3, 'Strongly Agree', 4, 1), (3, 'Agree', 3, 2), (3, 'Disagree', 2, 3), (3, 'Strongly Disagree', 1, 4),
  (4, 'Strongly Agree', 4, 1), (4, 'Agree', 3, 2), (4, 'Disagree', 2, 3), (4, 'Strongly Disagree', 1, 4),
  (5, 'Strongly Agree', 4, 1), (5, 'Agree', 3, 2), (5, 'Disagree', 2, 3), (5, 'Strongly Disagree', 1, 4),
  (6, 'Strongly Agree', 4, 1), (6, 'Agree', 3, 2), (6, 'Disagree', 2, 3), (6, 'Strongly Disagree', 1, 4),
  (7, 'Strongly Agree', 4, 1), (7, 'Agree', 3, 2), (7, 'Disagree', 2, 3), (7, 'Strongly Disagree', 1, 4),
  (8, 'Strongly Agree', 4, 1), (8, 'Agree', 3, 2), (8, 'Disagree', 2, 3), (8, 'Strongly Disagree', 1, 4),
  (9, 'Strongly Agree', 4, 1), (9, 'Agree', 3, 2), (9, 'Disagree', 2, 3), (9, 'Strongly Disagree', 1, 4),
  (10, 'Strongly Agree', 4, 1), (10, 'Agree', 3, 2), (10, 'Disagree', 2, 3), (10, 'Strongly Disagree', 1, 4),
  (11, 'Strongly Agree', 4, 1), (11, 'Agree', 3, 2), (11, 'Disagree', 2, 3), (11, 'Strongly Disagree', 1, 4),
  (12, 'Strongly Agree', 4, 1), (12, 'Agree', 3, 2), (12, 'Disagree', 2, 3), (12, 'Strongly Disagree', 1, 4),
  (13, 'Strongly Agree', 4, 1), (13, 'Agree', 3, 2), (13, 'Disagree', 2, 3), (13, 'Strongly Disagree', 1, 4),
  (14, 'Strongly Agree', 4, 1), (14, 'Agree', 3, 2), (14, 'Disagree', 2, 3), (14, 'Strongly Disagree', 1, 4),
  (15, 'Strongly Agree', 4, 1), (15, 'Agree', 3, 2), (15, 'Disagree', 2, 3), (15, 'Strongly Disagree', 1, 4),
  (16, 'Strongly Agree', 4, 1), (16, 'Agree', 3, 2), (16, 'Disagree', 2, 3), (16, 'Strongly Disagree', 1, 4),
  (17, 'Strongly Agree', 4, 1), (17, 'Agree', 3, 2), (17, 'Disagree', 2, 3), (17, 'Strongly Disagree', 1, 4),
  (18, 'Strongly Agree', 4, 1), (18, 'Agree', 3, 2), (18, 'Disagree', 2, 3), (18, 'Strongly Disagree', 1, 4),
  (19, 'Strongly Agree', 4, 1), (19, 'Agree', 3, 2), (19, 'Disagree', 2, 3), (19, 'Strongly Disagree', 1, 4),
  (20, 'Strongly Agree', 4, 1), (20, 'Agree', 3, 2), (20, 'Disagree', 2, 3), (20, 'Strongly Disagree', 1, 4);

-- ============================================
-- SEED DATA - Career Scoring Rules (Sample Data)
-- ============================================
-- Maps answer values (1-4) to career matches with score weights
-- Example: High technical interest (Q6: score 4) strongly matches Software Developer

INSERT INTO `career_scoring` (`career_id`, `question_id`, `selected_option_value`, `score_weight`) VALUES
  -- Software Developer: Tech + Logic + Problem Solving
  (1, 6, 4, 2.0), (1, 6, 3, 1.5), (1, 2, 4, 2.0), (1, 2, 3, 1.5), (1, 12, 4, 2.0), (1, 12, 3, 1.5), (1, 1, 4, 1.5),
  -- Data Analyst: Numbers + Research + Problem Solving
  (2, 1, 4, 2.0), (2, 1, 3, 1.5), (2, 8, 4, 1.5), (2, 8, 3, 1.0), (2, 12, 4, 1.5), (2, 12, 3, 1.0),
  -- Project Manager: Leadership + People + Communication
  (3, 9, 4, 2.0), (3, 9, 3, 1.5), (3, 5, 4, 1.5), (3, 5, 3, 1.0), (3, 16, 4, 1.5), (3, 16, 3, 1.0),
  -- UI/UX Designer: Creativity + Communication + Problem Solving
  (4, 18, 4, 2.0), (4, 18, 3, 1.5), (4, 5, 4, 1.5), (4, 5, 3, 1.0), (4, 12, 4, 1.0),
  -- Healthcare: Helping + Communication + Education
  (25, 13, 4, 2.0), (25, 13, 3, 1.5), (25, 17, 4, 1.5), (25, 17, 3, 1.0), (25, 5, 4, 1.0);

-- ============================================
-- SCHEMA READY FOR PHPMYADMIN
-- ============================================
-- Tables: admin_users, degrees, careers, career_degrees
-- Tables: questions, question_options, career_scoring, test_results
-- All with proper InnoDB constraints, UTF8MB4 charset
-- Sample seed data included for immediate testing
-- Ready for production use!
-- ============================================
