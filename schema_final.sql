-- ============================================
-- SKILL NEXUS - MODERN CAREER GUIDANCE PLATFORM
-- Final Comprehensive Database Schema
-- Optimized for phpMyAdmin
-- ============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `career_guidance` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `career_guidance`;

-- ============================================
-- ADMIN USERS TABLE
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
-- DEGREES TABLE
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_degree_name` (`name`),
  INDEX `idx_degrees_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CAREERS TABLE
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_career_name` (`name`),
  INDEX `idx_careers_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CAREER-DEGREE JUNCTION TABLE (Many-to-Many)
-- ============================================
DROP TABLE IF EXISTS `career_degrees`;
CREATE TABLE `career_degrees` (
  `career_id` INT UNSIGNED NOT NULL,
  `degree_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`career_id`, `degree_id`),
  INDEX `idx_degree_id` (`degree_id`),
  CONSTRAINT `fk_career_degrees_career` FOREIGN KEY (`career_id`) REFERENCES `careers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_career_degrees_degree` FOREIGN KEY (`degree_id`) REFERENCES `degrees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- ASSESSMENT QUESTIONS TABLE (20 Core Questions)
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
  UNIQUE KEY `unique_sequence` (`sequence`),
  INDEX `idx_questions_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- QUESTION OPTIONS TABLE
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
  INDEX `idx_question_id` (`question_id`),
  CONSTRAINT `fk_question_options_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CAREER SCORING TABLE
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
  INDEX `idx_career_id` (`career_id`),
  INDEX `idx_question_id` (`question_id`),
  UNIQUE KEY `unique_career_question_option` (`career_id`, `question_id`, `selected_option_value`),
  CONSTRAINT `fk_career_scoring_career` FOREIGN KEY (`career_id`) REFERENCES `careers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_career_scoring_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TEST RESULTS TABLE
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
  INDEX `idx_session_id` (`session_id`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SEED DATA - DEGREES (40 comprehensive options)
-- ============================================
INSERT INTO `degrees` (`name`, `description`, `duration`, `icon`, `color_code`) VALUES
('Computer Science', 'Foundation for software engineering, systems design, and technical innovation.', '4 years', 'fas fa-laptop-code', '#667eea'),
('Data Science', 'Statistics, coding, and domain expertise to extract insights from data.', '4 years', 'fas fa-chart-bar', '#764ba2'),
('Business Administration', 'Management, leadership, and business operations.', '4 years', 'fas fa-briefcase', '#f093fb'),
('Design (UX/UI)', 'Visual communication, user experience, and interface design.', '4 years', 'fas fa-paint-brush', '#ff6b6b'),
('Psychology', 'Behavior and mind, counseling, and human potential.', '4 years', 'fas fa-brain', '#4ecdc4'),
('Education', 'Teaching strategies, curriculum development, and learning theory.', '4 years', 'fas fa-book', '#ffa502'),
('Mechanical Engineering', 'Design and build mechanical systems and devices.', '4 years', 'fas fa-cog', '#ff9fb2'),
('Electrical Engineering', 'Electrical systems, circuits, and power solutions.', '4 years', 'fas fa-bolt', '#ffd93d'),
('Civil Engineering', 'Design and construction of infrastructure and buildings.', '4 years', 'fas fa-building', '#6bcf7f'),
('Marketing', 'Business promotion, branding, and customer engagement.', '4 years', 'fas fa-megaphone', '#ff6b9d'),
('Finance', 'Financial planning, analysis, and investment strategies.', '4 years', 'fas fa-dollar-sign', '#55b3d3'),
('Accounting', 'Bookkeeping, auditing, and financial reporting.', '4 years', 'fas fa-calculator', '#a8d8ea'),
('Human Resources', 'Talent management, recruiting, and organizational culture.', '4 years', 'fas fa-users', '#aa96da'),
('Environmental Science', 'Environmental systems and sustainability.', '4 years', 'fas fa-leaf', '#5dd9c1'),
('Digital Media', 'Creation and management of digital content.', '4 years', 'fas fa-film', '#fcbad3'),
('Healthcare Administration', 'Management of healthcare facilities and operations.', '4 years', 'fas fa-hospital', '#a1d82f'),
('Public Relations', 'Communication strategy and media relations.', '4 years', 'fas fa-megaphone', '#f8b500'),
('Information Systems', 'Technology systems for business operations.', '4 years', 'fas fa-network-wired', '#667eea'),
('Cybersecurity', 'Protection of systems and networks from attacks.', '4 years', 'fas fa-shield-alt', '#ff6b6b'),
('Supply Chain Management', 'Planning and managing product supply chains.', '4 years', 'fas fa-shipping-fast', '#4ecdc4'),
('Law', 'Legal systems, regulations, and advocacy.', '3 years', 'fas fa-gavel', '#ffa502'),
('Architecture', 'Design of buildings, spaces, and environments.', '5 years', 'fas fa-cube', '#ff9fb2'),
('Biomedical Engineering', 'Engineering solutions for medical applications.', '4 years', 'fas fa-heartbeat', '#ffd93d'),
('Journalism', 'Reporting, storytelling, and news production.', '4 years', 'fas fa-newspaper', '#6bcf7f'),
('Film & Media Production', 'Production and analysis of film and digital media.', '4 years', 'fas fa-video', '#ff6b9d'),
('Graphic Design', 'Visual design for print and digital media.', '4 years', 'fas fa-image', '#55b3d3'),
('Statistics', 'Mathematical analysis of data and probability.', '4 years', 'fas fa-chart-line', '#a8d8ea'),
('Economics', 'Markets, resources, and economic systems.', '4 years', 'fas fa-chart-pie', '#aa96da'),
('Sports Management', 'Business operations and marketing in sports.', '4 years', 'fas fa-trophy', '#5dd9c1'),
('Hospitality Management', 'Running hotels, restaurants, and guest services.', '4 years', 'fas fa-utensils', '#fcbad3'),
('Philosophy', 'Critical thinking, ethics, and logic.', '4 years', 'fas fa-lightbulb', '#a1d82f'),
('Sustainability Studies', 'Environmental and social sustainability approaches.', '4 years', 'fas fa-tree', '#f8b500'),
('Human-Computer Interaction', 'Design of interactive systems and user experiences.', '4 years', 'fas fa-mouse', '#667eea'),
('Robotics', 'Design and control of autonomous machines.', '4 years', 'fas fa-robot', '#ff6b6b'),
('Data Engineering', 'Building data pipelines and analytics infrastructure.', '4 years', 'fas fa-database', '#4ecdc4'),
('Project Management', 'Planning, executing, and monitoring projects.', '4 years', 'fas fa-tasks', '#ffa502'),
('Software Engineering', 'Engineering principles applied to software development.', '4 years', 'fas fa-code', '#ff9fb2'),
('Web Development', 'Building websites and web applications.', '4 years', 'fas fa-globe', '#ffd93d'),
('Game Design', 'Creating gameplay experiences for digital games.', '4 years', 'fas fa-gamepad', '#6bcf7f'),
('Entrepreneurship', 'Launching businesses and innovation management.', '4 years', 'fas fa-rocket', '#ff6b9d');

-- ============================================
-- SEED DATA - CAREERS (25 core career paths)
-- ============================================
INSERT INTO `careers` (`name`, `description`, `average_salary`, `job_outlook`, `icon`, `color_code`) VALUES
('Software Developer', 'Create and maintain applications and systems that power modern technology.', '$90,000 - $150,000', 'Growing 23% through 2030', 'fas fa-code', '#667eea'),
('Data Scientist', 'Extract insights from data to drive business decisions and innovation.', '$95,000 - $160,000', 'Growing 36% through 2030', 'fas fa-chart-bar', '#764ba2'),
('UX/UI Designer', 'Design intuitive and beautiful user experiences for digital products.', '$75,000 - $130,000', 'Growing 13% through 2030', 'fas fa-paint-brush', '#f093fb'),
('Product Manager', 'Lead product development from conception to market launch.', '$100,000 - $180,000', 'Growing 8% through 2030', 'fas fa-briefcase', '#ff6b6b'),
('DevOps Engineer', 'Manage infrastructure, automate processes, and ensure system reliability.', '$105,000 - $170,000', 'Growing 15% through 2030', 'fas fa-server', '#4ecdc4'),
('Cybersecurity Analyst', 'Protect organizations from digital threats and vulnerabilities.', '$100,000 - $165,000', 'Growing 33% through 2030', 'fas fa-shield-alt', '#ffa502'),
('Business Analyst', 'Analyze business needs and recommend process improvements.', '$75,000 - $130,000', 'Growing 11% through 2030', 'fas fa-chart-pie', '#ff9fb2'),
('Marketing Manager', 'Develop strategies to promote products and build brand awareness.', '$70,000 - $140,000', 'Growing 10% through 2030', 'fas fa-megaphone', '#ffd93d'),
('Financial Analyst', 'Analyze financial data to guide investment and business decisions.', '$75,000 - $135,000', 'Growing 5% through 2030', 'fas fa-dollar-sign', '#6bcf7f'),
('Mechanical Engineer', 'Design and develop mechanical equipment and systems.', '$75,000 - $130,000', 'Growing 7% through 2030', 'fas fa-cog', '#ff6b9d'),
('Electrical Engineer', 'Design and maintain electrical systems and infrastructure.', '$80,000 - $140,000', 'Growing 4% through 2030', 'fas fa-bolt', '#55b3d3'),
('Quality Assurance Engineer', 'Test software and systems to ensure quality and reliability.', '$70,000 - $125,000', 'Growing 7% through 2030', 'fas fa-check-circle', '#a8d8ea'),
('Consultant', 'Advise organizations on strategy, operations, and management.', '$85,000 - $180,000', 'Growing 14% through 2030', 'fas fa-handshake', '#aa96da'),
('Healthcare Administrator', 'Manage healthcare facilities and improve patient care operations.', '$80,000 - $140,000', 'Growing 32% through 2030', 'fas fa-hospital', '#5dd9c1'),
('Teacher/Educator', 'Inspire and educate students in academic or vocational settings.', '$40,000 - $80,000', 'Growing 10% through 2030', 'fas fa-chalkboard-teacher', '#fcbad3'),
('Journalist', 'Report news and investigate stories for public information.', '$45,000 - $100,000', 'Declining -6% through 2030', 'fas fa-newspaper', '#a1d82f'),
('Content Creator', 'Produce engaging content for digital platforms and media.', '$35,000 - $120,000', 'Growing 5% through 2030', 'fas fa-film', '#f8b500'),
('Data Engineer', 'Build and maintain data infrastructure for analytics.', '$100,000 - $170,000', 'Growing 36% through 2030', 'fas fa-database', '#667eea'),
('Solutions Architect', 'Design technical solutions for complex business problems.', '$120,000 - $200,000', 'Growing 5% through 2030', 'fas fa-cube', '#ff6b6b'),
('Machine Learning Engineer', 'Develop AI and machine learning models and systems.', '$110,000 - $200,000', 'Growing 36% through 2030', 'fas fa-brain', '#4ecdc4'),
('Graphic Designer', 'Create visual content for marketing and communication.', '$50,000 - $110,000', 'Growing 3% through 2030', 'fas fa-image', '#ffa502'),
('HR Manager', 'Manage talent, recruitment, and organizational development.', '$65,000 - $130,000', 'Growing 7% through 2030', 'fas fa-users', '#ff9fb2'),
('Entrepreneur/Startup Founder', 'Launch and grow innovative business ventures.', 'Variable', 'High Growth Potential', 'fas fa-rocket', '#ffd93d'),
('Environmental Scientist', 'Study and solve environmental challenges.', '$70,000 - $125,000', 'Growing 8% through 2030', 'fas fa-tree', '#6bcf7f'),
('Architect', 'Design buildings and urban spaces for communities.', '$80,000 - $150,000', 'Growing 1% through 2030', 'fas fa-building', '#ff6b9d');

-- ============================================
-- SEED DATA - CAREER-DEGREE RELATIONSHIPS
-- ============================================
INSERT INTO `career_degrees` (`career_id`, `degree_id`) VALUES
(1, 1), (1, 18), (1, 37),
(2, 2), (2, 27), (2, 35),
(3, 4), (3, 32), (3, 26),
(4, 1), (4, 3), (4, 37),
(5, 1), (5, 18), (5, 8),
(6, 19), (6, 1), (6, 18),
(7, 1), (7, 3), (7, 27),
(8, 10), (8, 9), (8, 3),
(9, 11), (9, 27), (9, 3),
(10, 7), (10, 9), (10, 8),
(11, 8), (11, 9), (11, 7),
(12, 1), (12, 37), (12, 18),
(13, 3), (13, 10), (13, 13),
(14, 16), (14, 15), (14, 11),
(15, 6), (15, 5), (15, 4),
(16, 24), (16, 17), (16, 14),
(17, 14), (17, 15), (17, 26),
(18, 2), (18, 35), (18, 1),
(19, 1), (19, 8), (19, 37),
(20, 1), (20, 2), (20, 35),
(21, 4), (21, 26), (21, 27),
(22, 13), (22, 5), (22, 10),
(23, 36), (23, 3), (23, 10),
(24, 14), (24, 31), (24, 9),
(25, 22), (25, 9), (25, 7);

-- ============================================
-- SEED DATA - 20 ASSESSMENT QUESTIONS
-- ============================================
INSERT INTO `questions` (`question_text`, `category`, `sequence`, `question_type`) VALUES
('What energizes you most in a work environment?', 'Work Environment', 1, 'multiple_choice'),
('How do you prefer to solve problems?', 'Problem Solving', 2, 'multiple_choice'),
('What kind of tasks do you enjoy most?', 'Task Preference', 3, 'multiple_choice'),
('How important is creativity in your ideal career?', 'Creativity', 4, 'multiple_choice'),
('What is your preferred work pace?', 'Work Pace', 5, 'multiple_choice'),
('How do you feel about working with technology?', 'Technology Affinity', 6, 'multiple_choice'),
('What motivates you financially?', 'Financial Motivation', 7, 'multiple_choice'),
('How do you prefer to work with others?', 'Collaboration', 8, 'multiple_choice'),
('What level of structure do you prefer at work?', 'Structure', 9, 'multiple_choice'),
('How important is helping others in your career?', 'Social Impact', 10, 'multiple_choice'),
('What type of learning interests you most?', 'Learning Style', 11, 'multiple_choice'),
('How do you handle pressure and deadlines?', 'Pressure Tolerance', 12, 'multiple_choice'),
('What work environment do you prefer?', 'Environment', 13, 'multiple_choice'),
('How important is career growth and advancement?', 'Career Growth', 14, 'multiple_choice'),
('What attracts you to leadership roles?', 'Leadership', 15, 'multiple_choice'),
('How do you prefer to communicate?', 'Communication Style', 16, 'multiple_choice'),
('What role do you prefer in team projects?', 'Team Role', 17, 'multiple_choice'),
('How important is work-life balance to you?', 'Work-Life Balance', 18, 'multiple_choice'),
('What kind of problems do you like solving?', 'Problem Type', 19, 'multiple_choice'),
('What is your ideal career trajectory?', 'Career Path', 20, 'multiple_choice');

-- ============================================
-- SEED DATA - QUESTION OPTIONS FOR EACH QUESTION
-- ============================================
INSERT INTO `question_options` (`question_id`, `option_text`, `option_value`, `sequence`) VALUES
(1, 'Collaborative and social environment', 1, 1),
(1, 'Independent work with minimal interruptions', 2, 2),
(1, 'Dynamic, fast-paced startup culture', 3, 3),
(1, 'Structured corporate environment', 4, 4),
(2, 'Logical analysis and data-driven approaches', 1, 1),
(2, 'Creative brainstorming and experimentation', 2, 2),
(2, 'Following proven methodologies', 3, 3),
(2, 'Collaborative discussion and consensus', 4, 4),
(3, 'Building and creating new things', 1, 1),
(3, 'Analyzing data and patterns', 2, 2),
(3, 'Helping and supporting others', 3, 3),
(3, 'Managing and organizing systems', 4, 4),
(4, 'Highly creative work is essential', 1, 1),
(4, 'Creative elements are nice to have', 2, 2),
(4, 'Creativity is not a priority', 3, 3),
(4, 'I am unsure about this aspect', 4, 4),
(5, 'Fast-paced, high-energy environment', 1, 1),
(5, 'Steady, predictable pace', 2, 2),
(5, 'Flexible pace depending on projects', 3, 3),
(5, 'Laid-back with flexible deadlines', 4, 4),
(6, 'Love technology, want to work with it daily', 1, 1),
(6, 'Comfortable with technology', 2, 2),
(6, 'Prefer minimal technology use', 3, 3),
(6, 'Technology makes me uncomfortable', 4, 4),
(7, 'Earning high income is a top priority', 1, 1),
(7, 'Fair compensation is important', 2, 2),
(7, 'Financial reward is less important than fulfillment', 3, 3),
(7, 'Security and stability matter most', 4, 4),
(8, 'Prefer working in teams on joint projects', 1, 1),
(8, 'Mix of teamwork and independent tasks', 2, 2),
(8, 'Work best independently', 3, 3),
(8, 'Manage others rather than work alongside them', 4, 4),
(9, 'Highly structured with clear guidelines', 1, 1),
(9, 'Some structure with room for flexibility', 2, 2),
(9, 'Minimal structure, self-directed', 3, 3),
(9, 'Constantly changing priorities and adapting', 4, 4),
(10, 'Making a positive impact on society is crucial', 1, 1),
(10, 'Some social impact would be nice', 2, 2),
(10, 'Not particularly important', 3, 3),
(10, 'I want to focus on business success', 4, 4),
(11, 'Technical and hands-on learning', 1, 1),
(11, 'Visual and design-focused learning', 2, 2),
(11, 'People and communication skills learning', 3, 3),
(11, 'Business and strategy learning', 4, 4),
(12, 'Thrive under pressure and tight deadlines', 1, 1),
(12, 'Handle pressure well with support', 2, 2),
(12, 'Prefer avoiding high-pressure situations', 3, 3),
(12, 'Stress significantly impacts my performance', 4, 4),
(13, 'Office-based with clear separation', 1, 1),
(13, 'Mix of office and remote work', 2, 2),
(13, 'Fully remote or flexible location', 3, 3),
(13, 'Field or travel-based work', 4, 4),
(14, 'Rapid advancement is very important', 1, 1),
(14, 'Steady progression matters', 2, 2),
(14, 'Growth is secondary to satisfaction', 3, 3),
(14, 'I prefer stability over advancement', 4, 4),
(15, 'I aspire to lead teams and organizations', 1, 1),
(15, 'Leadership roles appeal to me sometimes', 2, 2),
(15, 'I prefer to contribute without leading', 3, 3),
(15, 'Leadership is not appealing to me', 4, 4),
(16, 'Public speaking and presentations', 1, 1),
(16, 'Written communication and documentation', 2, 2),
(16, 'One-on-one conversations', 3, 3),
(16, 'Minimal communication, mostly via tools', 4, 4),
(17, 'Lead and make decisions for the team', 1, 1),
(17, 'Support and enable team success', 2, 2),
(17, 'Contribute specialized expertise', 3, 3),
(17, 'Work independently on my assigned tasks', 4, 4),
(18, 'Willing to work long hours for success', 1, 1),
(18, 'Balance is important but flexible', 2, 2),
(18, 'Strict separation between work and personal', 3, 3),
(18, 'Family and personal life come first', 4, 4),
(19, 'Complex technical and engineering problems', 1, 1),
(19, 'Business strategy and operations problems', 2, 2),
(19, 'Creative and design-related problems', 3, 3),
(19, 'People and relationship problems', 4, 4),
(20, 'Climb the corporate ladder to senior leadership', 1, 1),
(20, 'Become an expert specialist in my field', 2, 2),
(20, 'Start my own business or venture', 3, 3),
(20, 'Achieve expertise and stability', 4, 4);

-- ============================================
-- SEED DATA - CAREER SCORING MAPPINGS
-- ============================================
INSERT INTO `career_scoring` (`career_id`, `question_id`, `selected_option_value`, `score_weight`) VALUES
(1, 6, 1, 3.00), (1, 2, 1, 2.50), (1, 3, 1, 2.50), (1, 5, 1, 2.00), (1, 11, 1, 2.00), (1, 12, 1, 1.50), (1, 1, 2, 2.00), (1, 19, 1, 2.50),
(2, 6, 1, 3.00), (2, 2, 1, 3.00), (2, 11, 1, 2.50), (2, 7, 1, 2.00), (2, 14, 1, 1.50), (2, 3, 2, 2.00), (2, 12, 1, 1.50), (2, 19, 1, 2.50),
(3, 4, 1, 3.00), (3, 3, 1, 2.50), (3, 2, 2, 2.00), (3, 1, 1, 2.00), (3, 11, 2, 2.50), (3, 16, 1, 1.50), (3, 8, 1, 1.50), (3, 19, 3, 2.50),
(4, 1, 1, 2.50), (4, 8, 1, 2.00), (4, 15, 1, 2.50), (4, 16, 1, 2.00), (4, 2, 4, 2.00), (4, 14, 1, 2.00), (4, 17, 2, 2.00), (4, 3, 4, 1.50),
(5, 6, 1, 3.00), (5, 2, 1, 2.50), (5, 9, 1, 2.00), (5, 12, 1, 2.00), (5, 1, 2, 2.00), (5, 19, 1, 2.50), (5, 5, 1, 1.50), (5, 3, 1, 1.50),
(6, 6, 1, 3.00), (6, 2, 1, 3.00), (6, 19, 1, 2.50), (6, 12, 1, 2.00), (6, 1, 2, 2.00), (6, 9, 1, 1.50), (6, 3, 1, 1.50), (6, 5, 1, 1.50),
(7, 2, 1, 2.50), (7, 8, 1, 2.50), (7, 16, 1, 2.00), (7, 19, 2, 2.50), (7, 11, 4, 2.00), (7, 9, 1, 1.50), (7, 3, 4, 2.00), (7, 6, 2, 1.50),
(8, 4, 1, 2.50), (8, 1, 1, 2.50), (8, 16, 1, 2.50), (8, 15, 1, 2.00), (8, 8, 1, 2.00), (8, 3, 4, 1.50), (8, 11, 4, 1.50), (8, 7, 2, 1.50),
(9, 2, 1, 2.50), (9, 7, 1, 2.50), (9, 11, 4, 2.00), (9, 19, 2, 2.00), (9, 9, 1, 1.50), (9, 12, 1, 1.50), (9, 3, 4, 1.50), (9, 6, 2, 1.00),
(10, 6, 1, 2.50), (10, 2, 1, 2.50), (10, 19, 1, 2.50), (10, 3, 1, 2.00), (10, 11, 1, 2.00), (10, 9, 1, 1.50), (10, 5, 1, 1.50), (10, 1, 2, 1.50),
(11, 6, 1, 3.00), (11, 2, 1, 2.50), (11, 19, 1, 2.50), (11, 3, 1, 2.00), (11, 11, 1, 2.00), (11, 9, 1, 1.50), (11, 5, 1, 1.50), (11, 4, 3, 1.00),
(12, 6, 1, 2.50), (12, 2, 1, 2.50), (12, 3, 1, 2.00), (12, 9, 1, 2.00), (12, 12, 1, 1.50), (12, 19, 1, 2.00), (12, 1, 2, 1.50), (12, 5, 2, 1.50),
(13, 16, 1, 2.50), (13, 15, 1, 2.50), (13, 8, 1, 2.50), (13, 2, 4, 2.00), (13, 19, 2, 2.00), (13, 7, 1, 2.00), (13, 14, 1, 2.00), (13, 1, 1, 1.50),
(14, 8, 1, 2.50), (14, 10, 1, 2.50), (14, 15, 1, 2.00), (14, 16, 1, 2.00), (14, 3, 3, 2.50), (14, 9, 1, 1.50), (14, 12, 1, 1.50), (14, 18, 3, 1.50),
(15, 10, 1, 3.00), (15, 8, 1, 2.50), (15, 16, 1, 2.50), (15, 3, 3, 2.00), (15, 4, 2, 2.00), (15, 11, 3, 1.50), (15, 1, 1, 2.00), (15, 18, 3, 2.00),
(16, 16, 1, 3.00), (16, 4, 1, 2.50), (16, 1, 1, 2.50), (16, 2, 2, 2.00), (16, 19, 2, 1.50), (16, 3, 4, 1.50), (16, 11, 3, 1.50), (16, 10, 2, 2.00),
(17, 4, 1, 3.00), (17, 1, 1, 2.50), (17, 16, 1, 2.00), (17, 3, 1, 2.00), (17, 11, 2, 2.00), (17, 5, 1, 1.50), (17, 18, 3, 1.50), (17, 14, 1, 1.50),
(18, 6, 1, 3.00), (18, 2, 1, 3.00), (18, 3, 1, 2.50), (18, 19, 1, 2.00), (18, 11, 1, 2.00), (18, 9, 1, 1.50), (18, 12, 1, 1.50), (18, 1, 2, 1.50),
(19, 6, 1, 2.50), (19, 2, 1, 2.50), (19, 15, 1, 2.50), (19, 8, 1, 2.00), (19, 19, 1, 2.50), (19, 7, 1, 2.00), (19, 14, 1, 2.00), (19, 11, 1, 1.50),
(20, 6, 1, 3.00), (20, 2, 1, 3.00), (20, 11, 1, 2.50), (20, 3, 1, 2.50), (20, 19, 1, 2.50), (20, 7, 1, 2.00), (20, 12, 1, 1.50), (20, 1, 2, 1.50),
(21, 4, 1, 3.00), (21, 11, 2, 2.50), (21, 3, 1, 2.50), (21, 1, 1, 2.00), (21, 2, 2, 1.50), (21, 16, 1, 1.50), (21, 19, 3, 2.00), (21, 5, 1, 1.50),
(22, 8, 1, 2.50), (22, 10, 1, 2.50), (22, 15, 1, 2.50), (22, 16, 1, 2.00), (22, 3, 3, 2.00), (22, 1, 1, 2.00), (22, 11, 3, 1.50), (22, 19, 3, 1.50),
(23, 3, 1, 2.50), (23, 15, 1, 2.50), (23, 7, 1, 2.50), (23, 5, 1, 2.00), (23, 12, 1, 2.00), (23, 14, 1, 2.50), (23, 20, 3, 2.50), (23, 2, 2, 1.50),
(24, 10, 1, 3.00), (24, 2, 1, 2.50), (24, 19, 2, 2.00), (24, 11, 1, 2.00), (24, 3, 3, 1.50), (24, 6, 2, 1.50), (24, 1, 2, 1.50), (24, 8, 1, 1.50),
(25, 4, 1, 3.00), (25, 3, 1, 2.50), (25, 19, 3, 2.50), (25, 2, 4, 2.00), (25, 11, 2, 2.00), (25, 9, 1, 1.50), (25, 1, 2, 1.50), (25, 5, 2, 1.50);

-- ============================================
-- SAMPLE ADMIN USER (password: admin123)
-- ============================================
INSERT INTO `admin_users` (`username`, `email`, `password`) VALUES
('admin', 'admin@skillnexus.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36LRW06m');

COMMIT;