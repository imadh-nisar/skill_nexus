-- Modern Database Schema for Skill Nexus (Career Guidance)  
-- Updated: April 2026
-- Efficient, scalable structure - no user login required for main platform

CREATE DATABASE IF NOT EXISTS `career_guidance` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `career_guidance`;

-- Admin users (ONLY for dashboard, no constraints on main platform)
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(191) NOT NULL UNIQUE,
  `email` VARCHAR(191) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Career-Degree relationships (many-to-many)
DROP TABLE IF EXISTS `career_degrees`;
CREATE TABLE `career_degrees` (
  `career_id` INT UNSIGNED NOT NULL,
  `degree_id` INT UNSIGNED NOT NULL,
  `PRIMARY KEY` (`career_id`, `degree_id`),
  INDEX (`career_id`),
  INDEX (`degree_id`),
  CONSTRAINT `fk_career_degrees_career` FOREIGN KEY (`career_id`) REFERENCES `careers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_career_degrees_degree` FOREIGN KEY (`degree_id`) REFERENCES `degrees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  INDEX (`question_id`),
  CONSTRAINT `fk_question_options_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  INDEX (`career_id`),
  INDEX (`question_id`),
  UNIQUE KEY `unique_combination` (`career_id`, `question_id`, `selected_option_value`),
  CONSTRAINT `fk_career_scoring_career` FOREIGN KEY (`career_id`) REFERENCES `careers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_career_scoring_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Test Results (for analytics)
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed data (basic starter data)

INSERT INTO `degrees` (`name`, `description`) VALUES
  ('Computer Science', 'Computer Science is the foundation for software engineering, systems design, and technical problem-solving.'),
  ('Data Science', 'Data Science combines statistics, coding, and domain expertise to extract insights from data.'),
  ('Business Administration', 'Focuses on management, leadership, and business operations.'),
  ('Design', 'Covers visual communication, UX/UI, and creativity-driven problem solving.'),
  ('Psychology', 'Study of behavior and mind, useful for counseling and HR roles.'),
  ('Education', 'Training in teaching strategies, curriculum development, and learning theory.'),
  ('Mechanical Engineering', 'Designs and builds mechanical systems and devices.'),
  ('Electrical Engineering', 'Focuses on electrical systems, circuits, and power.'),
  ('Civil Engineering', 'Design and construction of buildings, roads, and infrastructure.'),
  ('Marketing', 'Business promotion, branding, and customer engagement.'),
  ('Finance', 'Financial planning, analysis, and investment strategies.'),
  ('Accounting', 'Professional bookkeeping, auditing, and financial reporting.'),
  ('Human Resources', 'Managing talent, recruiting, and organizational culture.'),
  ('Environmental Science', 'Study of environmental systems and sustainability.'),
  ('Digital Media', 'Creation and management of digital content and platforms.'),
  ('Healthcare Administration', 'Management of healthcare facilities and operations.'),
  ('Public Relations', 'Communication strategy and media relations.'),
  ('Information Systems', 'Technology systems used to manage business operations.'),
  ('Cybersecurity', 'Protecting systems and networks from digital attacks.'),
  ('Supply Chain Management', 'Planning and managing product supply chains.'),
  ('International Relations', 'Global politics, diplomacy, and policy analysis.'),
  ('Law', 'Study of legal systems, regulations, and advocacy.'),
  ('Architecture', 'Design of buildings, spaces, and urban environments.'),
  ('Biomedical Engineering', 'Engineering solutions for medical applications.'),
  ('Journalism', 'Reporting, storytelling, and news production.'),
  ('Film & Media', 'Production and analysis of film, TV, and digital media.'),
  ('Graphic Design', 'Visual design for print and digital media.'),
  ('Statistics', 'Mathematical analysis of data and probability.'),
  ('Economics', 'Study of markets, resources, and economic systems.'),
  ('Sports Management', 'Business operations and marketing in sports.'),
  ('Hospitality Management', 'Running hotels, restaurants, and guest services.'),
  ('Philosophy', 'Critical thinking, ethics, and logic.'),
  ('Sustainability Studies', 'Approaches to environmental and social sustainability.'),
  ('Human-Computer Interaction', 'Design of interactive systems and user experiences.'),
  ('Robotics', 'Design and control of autonomous machines.'),
  ('Data Engineering', 'Building data pipelines and analytics infrastructure.'),
  ('Project Management', 'Planning, executing, and monitoring projects.'),
  ('Sales', 'Client relationships and revenue generation.'),
  ('Social Work', 'Supporting individuals, families, and communities.'),
  ('UX Research', 'Studying user behavior to inform product design.'),
  ('Digital Marketing', 'Online marketing, social media and analytics.'),
  ('Web Development', 'Building websites and web applications.'),
  ('Game Design', 'Creating gameplay experiences for digital games.'),
  ('Software Engineering', 'Engineering principles applied to software development.'),
  ('Network Engineering', 'Design and maintenance of computer networks.'),
  ('Biotechnology', 'Biological innovation and product development.'),
  ('Graphic Communications', 'Visual messaging and print media.'),
  ('Entrepreneurship', 'Launching businesses and innovation management.'),
  ('Logistics', 'Transportation planning and distribution networks.'),
  ('Occupational Therapy', 'Helping patients regain daily living skills.'),
  ('Nursing', 'Clinical care and health support.'),
  ('Dentistry', 'Oral healthcare and dental procedures.'),
  ('Information Technology', 'IT systems, support, and infrastructure.'),
  ('Cloud Computing', 'Distributed systems and cloud platforms.'),
  ('Artificial Intelligence', 'Machine learning and intelligent systems.'),
  ('Data Visualization', 'Presenting data insights graphically.'),
  ('Media Studies', 'Analysis of media, culture, and communication.'),
  ('Urban Planning', 'Design and development of cities and communities.'),
  ('Biomedical Science', 'Research in biology and medicine.'),
  ('Chemistry', 'Study of matter, reactions, and materials.'),
  ('Physics', 'Principles of energy, motion, and matter.'),
  ('Statistics & Analytics', 'Data analysis and predictive modeling.'),
  ('Film Production', 'Making and distributing movies and video content.'),
  ('Fashion Design', 'Creating apparel and fashion products.'),
  ('Interior Design', 'Designing functional indoor spaces.'),
  ('Renewable Energy', 'Clean energy systems and sustainability.'),
  ('Public Health', 'Population health and disease prevention.'),
  ('Clinical Psychology', 'Mental health evaluation and treatment.'),
  ('Speech Pathology', 'Communication disorders and therapy.'),
  ('Gerontology', 'Study of aging and elder care.'),
  ('Nutrition Science', 'Food science and dietary planning.'),
  ('Veterinary Science', 'Animal health and veterinary care.'),
  ('Aerospace Engineering', 'Aircraft and spacecraft design and systems.'),
  ('Industrial Design', 'Product design and user-centered solutions.'),
  ('Music Production', 'Audio recording and music creation.'),
  ('Performing Arts', 'Theater, dance, and performance production.'),
  ('Artificial Intelligence Ethics', 'Ethical issues in AI and technology.'),
  ('Quantum Computing', 'Next-generation computing systems.'),
  ('Environmental Engineering', 'Engineering solutions for environmental issues.'),
  ('Sports Science', 'Athletic performance and exercise science.'),
  ('Forensic Science', 'Crime scene investigation and analysis.'),
  ('Data Privacy', 'Protecting information and compliance.'),
  ('Blockchain', 'Distributed ledger systems and cryptocurrency.'),
  ('Augmented Reality', 'Mixed reality and immersive technologies.'),
  ('Robotics Engineering', 'Design and control of robotic systems.'),
  ('Software Quality Assurance', 'Testing and ensuring software quality.');

-- 20-Question Assessment System with Options
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
  ('Are you motivated by high salary rather than personal satisfaction?', 'Motivation', 11),
  ('Do you enjoy solving puzzles and complex problems?', 'Interest', 12),
  ('Would you like to work in healthcare or education?', 'Interest', 13),
  ('Do you prefer working indoors rather than outdoors?', 'Lifestyle', 14),
  ('Are you interested in entrepreneurship and starting your own business?', 'Motivation', 15),
  ('Do you have strong communication skills?', 'Interest', 16),
  ('Do you prefer helping others over competing?', 'WorkStyle', 17),
  ('Are you highly creative and design-focused?', 'Interest', 18),
  ('Do you want a career with leadership opportunities?', 'Motivation', 19),
  ('Do you enjoy learning about emerging technologies?', 'Interest', 20);

-- Question Options (1-4 scale for scoring)
INSERT INTO `question_options` (`question_id`, `option_text`, `option_value`, `sequence`) VALUES
  -- Q1 options
  (1, 'Strongly Agree', 4, 1),
  (1, 'Agree', 3, 2),
  (1, 'Disagree', 2, 3),
  (1, 'Strongly Disagree', 1, 4),
  -- Q2 options
  (2, 'Strongly Agree', 4, 1),
  (2, 'Agree', 3, 2),
  (2, 'Disagree', 2, 3),
  (2, 'Strongly Disagree', 1, 4),
  -- Q3 options
  (3, 'Strongly Agree', 4, 1),
  (3, 'Agree', 3, 2),
  (3, 'Disagree', 2, 3),
  (3, 'Strongly Disagree', 1, 4),
  -- Q4 options
  (4, 'Strongly Agree', 4, 1),
  (4, 'Agree', 3, 2),
  (4, 'Disagree', 2, 3),
  (4, 'Strongly Disagree', 1, 4),
  -- Q5 options
  (5, 'Strongly Agree', 4, 1),
  (5, 'Agree', 3, 2),
  (5, 'Disagree', 2, 3),
  (5, 'Strongly Disagree', 1, 4),
  -- Q6 options
  (6, 'Strongly Agree', 4, 1),
  (6, 'Agree', 3, 2),
  (6, 'Disagree', 2, 3),
  (6, 'Strongly Disagree', 1, 4),
  -- Q7 options
  (7, 'Strongly Agree', 4, 1),
  (7, 'Agree', 3, 2),
  (7, 'Disagree', 2, 3),
  (7, 'Strongly Disagree', 1, 4),
  -- Q8 options
  (8, 'Strongly Agree', 4, 1),
  (8, 'Agree', 3, 2),
  (8, 'Disagree', 2, 3),
  (8, 'Strongly Disagree', 1, 4),
  -- Q9 options
  (9, 'Strongly Agree', 4, 1),
  (9, 'Agree', 3, 2),
  (9, 'Disagree', 2, 3),
  (9, 'Strongly Disagree', 1, 4),
  -- Q10 options
  (10, 'Strongly Agree', 4, 1),
  (10, 'Agree', 3, 2),
  (10, 'Disagree', 2, 3),
  (10, 'Strongly Disagree', 1, 4),
  -- Q11 options
  (11, 'Strongly Agree', 4, 1),
  (11, 'Agree', 3, 2),
  (11, 'Disagree', 2, 3),
  (11, 'Strongly Disagree', 1, 4),
  -- Q12 options
  (12, 'Strongly Agree', 4, 1),
  (12, 'Agree', 3, 2),
  (12, 'Disagree', 2, 3),
  (12, 'Strongly Disagree', 1, 4),
  -- Q13 options
  (13, 'Strongly Agree', 4, 1),
  (13, 'Agree', 3, 2),
  (13, 'Disagree', 2, 3),
  (13, 'Strongly Disagree', 1, 4),
  -- Q14 options
  (14, 'Strongly Agree', 4, 1),
  (14, 'Agree', 3, 2),
  (14, 'Disagree', 2, 3),
  (14, 'Strongly Disagree', 1, 4),
  -- Q15 options
  (15, 'Strongly Agree', 4, 1),
  (15, 'Agree', 3, 2),
  (15, 'Disagree', 2, 3),
  (15, 'Strongly Disagree', 1, 4),
  -- Q16 options
  (16, 'Strongly Agree', 4, 1),
  (16, 'Agree', 3, 2),
  (16, 'Disagree', 2, 3),
  (16, 'Strongly Disagree', 1, 4),
  -- Q17 options
  (17, 'Strongly Agree', 4, 1),
  (17, 'Agree', 3, 2),
  (17, 'Disagree', 2, 3),
  (17, 'Strongly Disagree', 1, 4),
  -- Q18 options
  (18, 'Strongly Agree', 4, 1),
  (18, 'Agree', 3, 2),
  (18, 'Disagree', 2, 3),
  (18, 'Strongly Disagree', 1, 4),
  -- Q19 options
  (19, 'Strongly Agree', 4, 1),
  (19, 'Agree', 3, 2),
  (19, 'Disagree', 2, 3),
  (19, 'Strongly Disagree', 1, 4),
  -- Q20 options
  (20, 'Strongly Agree', 4, 1),
  (20, 'Agree', 3, 2),
  (20, 'Disagree', 2, 3),
  (20, 'Strongly Disagree', 1, 4);

-- Career Scoring Rules (maps answer values to career scores)
-- Example: If user scores high (3-4) on technical questions, they match tech careers
INSERT INTO `career_scoring` (`career_id`, `question_id`, `selected_option_value`, `score_weight`) VALUES
  -- Software Developer (career_id = 1)
  (1, 1, 4, 2.0), (1, 1, 3, 1.5),  -- High on numbers
  (1, 2, 4, 2.0), (1, 2, 3, 1.5),  -- Logic-focused
  (1, 6, 4, 2.0), (1, 6, 3, 1.5),  -- Tech comfort
  (1, 12, 4, 2.0), (1, 12, 3, 1.5), -- Problem solving
  
  -- Data Analyst (career_id = 2)
  (2, 1, 4, 2.0), (2, 1, 3, 1.5),  -- Works with data
  (2, 8, 4, 1.5), (2, 8, 3, 1.0),  -- Research writing
  (2, 12, 4, 1.5), (2, 12, 3, 1.0), -- Problem solving
  
  -- Project Manager (career_id = 3)
  (3, 9, 4, 2.0), (3, 9, 3, 1.5),  -- Managing people
  (3, 5, 4, 1.5), (3, 5, 3, 1.0),  -- Explaining concepts
  (3, 16, 4, 1.5), (3, 16, 3, 1.0), -- Communication
  
  -- UI/UX Designer (career_id = 4)
  (4, 18, 4, 2.0), (4, 18, 3, 1.5), -- Creative design
  (4, 5, 4, 1.5), (4, 5, 3, 1.0),   -- Explaining concepts
  (4, 2, 1, 1.5), (4, 2, 2, 1.0),   -- NOT just logical
  
  -- Healthcare roles (career_id = 25)
  (25, 13, 4, 2.0), (25, 13, 3, 1.5), -- Interest in healthcare
  (25, 17, 4, 1.5), (25, 17, 3, 1.0), -- Helping others
  (25, 3, 2, 1.0), (25, 3, 1, 1.5);   -- Team work preferred
  ('Data Analyst', 'Analyzes data to provide insights and inform decisions.', 2),
  ('Project Manager', 'Coordinates teams, plans projects, and delivers outcomes.', 3),
  ('UI/UX Designer', 'Designs user experiences and interfaces for digital products.', 4),
  ('Business Analyst', 'Translates business needs into technical requirements.', 2),
  ('Marketing Manager', 'Leads marketing strategy and campaigns.', 10),
  ('Financial Analyst', 'Evaluates financial data and supports decisions.', 11),
  ('HR Specialist', 'Recruits, trains, and supports employees.', 13),
  ('Mechanical Engineer', 'Designs and tests mechanical systems.', 7),
  ('Electrical Engineer', 'Develops electrical systems and circuits.', 8),
  ('Civil Engineer', 'Plans and builds public infrastructure projects.', 9),
  ('UX Researcher', 'Studies user behavior to improve products.', 39),
  ('Content Strategist', 'Plans and creates content across platforms.', 15),
  ('Data Engineer', 'Builds and maintains data pipelines.', 36),
  ('Cybersecurity Analyst', 'Protects systems from security threats.', 19),
  ('Network Administrator', 'Maintains computer networks and systems.', 44),
  ('Product Manager', 'Defines product vision and leads delivery.', 38),
  ('Operations Manager', 'Oversees operational efficiency and processes.', 20),
  ('Event Planner', 'Coordinates events and logistics.', 31),
  ('Digital Marketing Specialist', 'Executes online marketing strategies.', 40),
  ('Graphic Designer', 'Creates visual communications and branding.', 27),
  ('Architect', 'Designs buildings and structural concepts.', 23),
  ('Attorney', 'Provides legal advice and representation.', 22),
  ('Teacher', 'Educates students in a classroom setting.', 6),
  ('Nurse', 'Provides clinical care and patient support.', 52),
  ('Physician Assistant', 'Supports physicians with patient care.', 16),
  ('Environmental Consultant', 'Advises on sustainability and compliance.', 14),
  ('Healthcare Administrator', 'Manages healthcare operations and policy.', 16),
  ('Supply Chain Analyst', 'Optimizes supply chain processes.', 21),
  ('Logistics Coordinator', 'Coordinates transportation and inventory.', 45),
  ('Social Worker', 'Supports individuals and community services.', 36),
  ('Public Relations Specialist', 'Manages a company’s public image.', 17),
  ('Journalist', 'Researches and reports news stories.', 25),
  ('Film Producer', 'Manages film projects from development to release.', 26),
  ('Game Developer', 'Designs and creates video games.', 41),
  ('Machine Learning Engineer', 'Builds machine learning systems and models.', 49),
  ('AI Researcher', 'Advances artificial intelligence research.', 49),
  ('Cloud Architect', 'Designs cloud infrastructure and solutions.', 46),
  ('Database Administrator', 'Manages databases and data security.', 36),
  ('Customer Success Manager', 'Ensures customer satisfaction and retention.', 10),
  ('Sales Engineer', 'Supports technical sales and product demos.', 38),
  ('Corporate Trainer', 'Develops employee training programs.', 6),
  ('UX/UI Developer', 'Implements user interface designs in code.', 39),
  ('Quality Assurance Engineer', 'Tests and verifies software quality.', 64),
  ('Technical Writer', 'Creates documentation and technical content.', 25),
  ('Urban Planner', 'Designs policies for city development.', 57),
  ('Sustainability Specialist', 'Promotes environmental best practices.', 33),
  ('Biomedical Scientist', 'Conducts medical research and studies.', 60),
  ('Pharmacist', 'Prepares and dispenses medications.', 52),
  ('Physicist', 'Researches physical phenomena and theory.', 41),
  ('Chemist', 'Performs chemical analysis and experiments.', 42),
  ('Clinical Psychologist', 'Provides mental health diagnosis and therapy.', 54),
  ('Speech Therapist', 'Helps clients with communication disorders.', 58),
  ('Veterinarian', 'Provides medical care for animals.', 53),
  ('Aerospace Engineer', 'Designs aircraft and spacecraft systems.', 61),
  ('Industrial Designer', 'Creates products and user-centered designs.', 62),
  ('Entrepreneur', 'Starts and grows new business ventures.', 49),
  ('Sports Scientist', 'Supports athletic performance and training.', 62),
  ('Data Privacy Officer', 'Ensures compliance with data regulations.', 65),
  ('Blockchain Developer', 'Builds decentralized applications.', 67),
  ('AR/VR Developer', 'Creates immersive augmented/virtual reality experiences.', 66),
  ('Robotics Technician', 'Maintains and programs robotic systems.', 63);
INSERT INTO `questions` (`question_text`) VALUES
  ('Do you enjoy working with numbers and data?'),
  ('Do you prefer logical problem-solving over creative design?'),
  ('Do you prefer working alone rather than in a team?'),
  ('Would you enjoy a career that involves frequent travel?'),
  ('Do you like explaining concepts to others?'),
  ('Are you comfortable with coding and technology?'),
  ('Do you prefer stability over risk-taking in your career?'),
  ('Do you enjoy researching and writing?'),
  ('Would you like to manage people and projects?'),
  ('Do you prefer practical hands-on work over theoretical analysis?'),
  ('Are you motivated by high salary rather than personal satisfaction?'),
  ('Do you enjoy solving puzzles and complex problems?'),
  ('Would you like to work in healthcare or education?'),
  ('Do you prefer working indoors rather than outdoors?'),
  ('Are you interested in entrepreneurship and starting your own business?');

-- Answers for each question (yes/no/maybe)
INSERT INTO `answers` (`question_id`, `answer_value`) VALUES
  (1, 'yes'), (1, 'no'), (1, 'maybe'),
  (2, 'yes'), (2, 'no'), (2, 'maybe'),
  (3, 'yes'), (3, 'no'), (3, 'maybe'),
  (4, 'yes'), (4, 'no'), (4, 'maybe'),
  (5, 'yes'), (5, 'no'), (5, 'maybe'),
  (6, 'yes'), (6, 'no'), (6, 'maybe'),
  (7, 'yes'), (7, 'no'), (7, 'maybe'),
  (8, 'yes'), (8, 'no'), (8, 'maybe'),
  (9, 'yes'), (9, 'no'), (9, 'maybe'),
  (10, 'yes'), (10, 'no'), (10, 'maybe'),
  (11, 'yes'), (11, 'no'), (11, 'maybe'),
  (12, 'yes'), (12, 'no'), (12, 'maybe'),
  (13, 'yes'), (13, 'no'), (13, 'maybe'),
  (14, 'yes'), (14, 'no'), (14, 'maybe'),
  (15, 'yes'), (15, 'no'), (15, 'maybe');

-- Career <-> answer mapping (example match rules)
INSERT INTO `career_answer_map` (`career_id`, `answer_id`) VALUES
  -- Software Developer (career_id = 1)
  (1, 16), -- Q6=yes
  (1, 1),  -- Q1=yes
  (1, 34), -- Q12=yes

  -- Data Analyst (career_id = 2)
  (2, 1),  -- Q1=yes
  (2, 4),  -- Q2=yes
  (2, 34), -- Q12=yes

  -- Project Manager (career_id = 3)
  (3, 4),  -- Q2=yes
  (3, 43), -- Q15=yes
  (3, 13), -- Q5=yes

  -- UI/UX Designer (career_id = 4)
  (4, 4),  -- Q2=yes
  (4, 40), -- Q14=yes
  (4, 22);  -- Q8=yes

-- Bulk seed: add many degrees, careers, and career-answer mappings
-- (This section can be rerun when needed; it is idempotent if you clear the tables first.)

DELIMITER $$
CREATE PROCEDURE `seed_bulk_degrees_careers`()
BEGIN
  DECLARE i INT DEFAULT 5;

  -- Add 150 more degrees (IDs 5..154)
  WHILE i <= 154 DO
    INSERT INTO `degrees` (`name`, `description`)
    VALUES (CONCAT('Degree ', i), CONCAT('Detailed description for degree ', i, '.'));
    SET i = i + 1;
  END WHILE;

  SET i = 5;
  -- Add 300 more careers (IDs 5..304) and assign them to degrees in round-robin
  WHILE i <= 304 DO
    INSERT INTO `careers` (`name`, `description`, `degree_id`)
    VALUES (
      CONCAT('Career ', i),
      CONCAT('Detailed description for career ', i, '.'),
      ((i - 1) % 154) + 1
    );
    SET i = i + 1;
  END WHILE;

  -- Create career preferences for new careers.
  -- Each career will prefer 'yes' for a few core questions; adjust as needed.
  SET i = 5;
  WHILE i <= 304 DO
    INSERT INTO `career_preferences` (`career_id`, `question_id`, `preferred_answer`) VALUES
      (i, 1, 'yes'),
      (i, 6, 'yes'),
      (i, 12, 'yes');
    SET i = i + 1;
  END WHILE;
END$$
DELIMITER ;

-- Run the bulk seed procedure (comment out if running multiple times)
CALL `seed_bulk_degrees_careers`();
DROP PROCEDURE IF EXISTS `seed_bulk_degrees_careers`;

-- Notes:
-- 1) The `career_preferences` table is used by the application to match a user's submitted answers (stored in `user_answers.answer_value`) to career preferences.
-- 2) This seed inserts sample preferences for each career; adjust the questions and values to tailor the matching behavior.
-- 3) To refresh the seed data, drop the tables and re-run this file.
