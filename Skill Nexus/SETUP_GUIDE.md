# Skill NEXUS Career Guidance Platform - Complete Setup & Deployment Guide

## 🚀 PROJECT OVERVIEW

Skill NEXUS is a modern, comprehensive career guidance platform with:

- **20 Strategic Assessment Questions** mapping to 25+ careers
- **Bidirectional Career-Degree Linking** (each career linked to relevant degrees)
- **Full Admin Dashboard** with CRUD operations
- **Responsive Design** with gradient backgrounds and smooth animations
- **Efficient Codebase** with proper database relationships and scoring system

---

## 📋 SETUP INSTRUCTIONS

### Step 1: Database Setup

1. **Open phpMyAdmin** at `http://localhost/phpmyadmin`
2. **Create a new database** (if not existing):
   - Name: `career_guidance`
   - Charset: `utf8mb4`
   - Collation: `utf8mb4_unicode_ci`

3. **Run the database schema**:
   - Navigate to the SQL tab
   - Copy the entire contents of `schema_final.sql`
   - Paste and execute
   - This will create all tables with seed data

### Step 2: Configuration

1. **Verify config.php** at root:
   - Database: `career_guidance`
   - Host: `localhost`
   - User: `root`
   - Password: `` (empty)

2. **Base URL**:
   - Path: `/skill_nexus/Skill%20Nexus`
   - Update if your folder structure differs

### Step 3: Admin Access

1. **Default Admin Credentials**:
   - Username: `admin`
   - Password: `admin123`

2. **Admin Dashboard**: `http://localhost/skill_nexus/Skill%20Nexus/admin/dashboard.php`

---

## 📁 PROJECT STRUCTURE

```
Skill Nexus/
├── config.php                  # Main configuration & DB functions
├── helpers.php                 # Utility helpers
├── index.php                   # Homepage
├── schema_final.sql           # Complete database schema (RUN THIS!)
│
├── admin/
│   ├── login.php              # Admin login
│   ├── logout.php             # Admin logout
│   ├── dashboard.php          # Admin dashboard
│   ├── manage_careers.php     # ✅ NEW: Career CRUD with degree linking
│   ├── manage_degrees.php     # ✅ NEW: Degree CRUD
│   ├── manage_questions.php   # ✅ NEW: Question CRUD with options
│   ├── results.php
│   ├── questions.php
│   └── degrees.php
│
├── career/
│   ├── career_test.php        # Test introduction page
│   ├── test.php               # Main assessment test
│   └── results.php            # Test results display
│
├── results/
│   ├── careers.php            # Browse all careers
│   ├── degrees.php            # Browse all degrees
│   └── result.php
│
└── cj/ & partners & blog/     # Frontend resources
```

---

## ✅ COMPLETED FEATURES

### Database Schema (schema_final.sql)

- ✅ **Proper Foreign Keys** on all tables
- ✅ **Career-Degree Junction Table** for many-to-many relationships
- ✅ **20 Assessment Questions** with multiple choice options
- ✅ **Career Scoring System** mapping answers to careers
- ✅ **40 Degree Options** pre-seeded
- ✅ **25 Career Paths** pre-seeded
- ✅ **Complete Relationships** between all entities

### Core Functions (config.php)

- ✅ `getCareerDegrees($pdo, $career_id)` - Returns all degrees for a career
- ✅ `getDegreeCareers($pdo, $degree_id)` - Returns all careers for a degree
- ✅ `linkCareerToDegree($pdo, $career_id, $degree_id)` - Create links
- ✅ `unlinkCareerFromDegree($pdo, $career_id, $degree_id)` - Remove links
- ✅ `calculateCareerScores($pdo, $answers)` - Score test answers
- ✅ `getCareerRecommendationsWithDetails($pdo, $answers, $limit)` - Get recommendations with scoring

### Admin Dashboard Pages

#### manage_careers.php ✅

- **Full CRUD Operations**:
  - Create new careers with icon, color, salary, outlook
  - Edit existing careers
  - Delete careers
  - Toggle active/inactive status
- **Degree Linking UI**:
  - Checkbox list of all degrees
  - Multi-select to link degrees to careers
  - Auto-update relationships on save
- **Modern UI**:
  - Gradient backgrounds (#667eea → #764ba2 → #f093fb)
  - Card-based layout with hover effects
  - Responsive table with action buttons
  - Side-by-side form and list layout

#### manage_degrees.php ✅

- **Full CRUD Operations** for degrees
- **Duration, Icon, Color** fields
- **Status Toggle** (active/inactive)
- **Modern Gradient Design** matching theme

#### manage_questions.php ✅

- **Question Management**:
  - Create questions with sequence
  - Edit question text and category
  - Delete with options
  - Toggle active/inactive
- **Options Management**:
  - Add multiple choice options
  - Assign option values (for scoring)
  - Display existing options with delete
  - Inline option management

### Assessment System (20 Questions)

The platform includes 20 strategic questions covering:

1. Work Environment Preferences
2. Problem Solving Style
3. Task Preferences
4. Creativity Level
5. Work Pace
6. Technology Affinity
7. Financial Motivation
8. Collaboration Style
9. Preference for Structure
10. Social Impact Importance
11. Learning Style
12. Pressure Handling
13. Work Environment Type
14. Career Growth Importance
15. Leadership Aspirations
16. Communication Style
17. Team Role Preference
18. Work-Life Balance
19. Problem Type Interest
20. Career Trajectory Goals

Each question has 4 strategically designed options with values 1-4 for scoring.

---

## 🎯 CAREER & DEGREE MAPPING

### 25 Core Careers Mapped to Degrees:

1. **Software Developer** → CS, Systems, Web Dev
2. **Data Scientist** → Data Science, Statistics, Analytics
3. **UX/UI Designer** → Design, HCI, Graphic Design
4. **Product Manager** → CS, Business, Product Management
5. **DevOps Engineer** → CS, Systems, Infrastructure
6. **Cybersecurity Analyst** → Cybersecurity, Systems, CS
7. **Business Analyst** → Business, Data, Analytics
8. **Marketing Manager** → Marketing, Business, Digital Marketing
9. **Financial Analyst** → Finance, Statistics, Business
10. **Mechanical Engineer** → Mechanical, Civil, Electrical Engineering
    ... and 15 more careers

### 40 Degree Programs:

- Computer Science, Data Science, Business Administration
- Design (UX/UI), Psychology, Education
- Multiple Engineering disciplines
- Marketing, Finance, Accounting, HR
- Environmental Science, Law, Architecture
- Healthcare, Technology, and more

---

## 🔧 USAGE GUIDE

### For End Users

1. **Visit Career Test**: `/career/career_test.php`
2. **Take Assessment**: Answer 20 questions honestly
3. **View Results**: Get 5-10 career recommendations with match scores
4. **Browse Careers**: `/results/careers.php` - Explore all careers
5. **Browse Degrees**: `/results/degrees.php` - See all degree programs

### For Administrators

1. **Login**: `/admin/login.php` (admin/admin123)
2. **Manage Careers**:
   - Add new careers with details
   - Link related degrees
   - Edit or delete careers
3. **Manage Degrees**:
   - Add degree programs
   - Set duration and details
   - Activate/deactivate
4. **Manage Questions**:
   - Create/edit questions
   - Add multiple choice options
   - Control assessment flow
5. **View Results**: Analytics and test results

---

## 🎨 DESIGN FEATURES

### Color Scheme

- **Primary Gradient**: `#667eea` → `#764ba2` → `#f093fb`
- **Secondary**: Various vibrant accent colors for degrees/careers

### Typography

- **Font**: Segoe UI, Tahoma, Geneva, Verdana, Sans-serif
- **Sizes**: Responsive, adaptive to screen size

### Animations

- **Fade-in-up**: Cards sliding up on load
- **Hover Effects**: Smooth lift on cards
- **Transitions**: 0.3s ease for all interactive elements
- **Progress Indicators**: Visual feedback for assessments

### Layout

- **Card-Based**: Modular design with individual cards
- **Sidebar Navigation**: Fixed admin sidebar
- **Responsive Grid**: Mobile-first responsive design
- **Max-width Containers**: Optimized readability

---

## 🔐 SECURITY FEATURES

### Database

- ✅ Parameterized Queries (PDO prepared statements)
- ✅ Foreign key constraints
- ✅ Data validation on all inputs
- ✅ Unique constraints on names

### Admin Panel

- ✅ Session-based authentication
- ✅ Admin login requirement on all admin pages
- ✅ Password hashing (bcrypt)
- ✅ Input sanitization with `e()` helper

### Data Protection

- ✅ CSRF protection ready
- ✅ XSS prevention via htmlspecialchars
- ✅ SQL injection prevention via parameterized queries

---

## 📊 DATABASE RELATIONSHIPS

```
careers (1) ─── (many) career_degrees ─── (many) degrees
   ↓
career_scoring ← questions → question_options
   ↓
test_results

Foreign Keys:
- career_degrees → careers.id (ON DELETE CASCADE)
- career_degrees → degrees.id (ON DELETE CASCADE)
- career_scoring → careers.id (ON DELETE CASCADE)
- career_scoring → questions.id (ON DELETE CASCADE)
- question_options → questions.id (ON DELETE CASCADE)
```

---

## 🚀 DEPLOYMENT CHECKLIST

- [ ] Database schema imported (`schema_final.sql`)
- [ ] Admin credentials configured
- [ ] Base URL updated in config.php
- [ ] File permissions set (writeable folders for logs)
- [ ] Test admin login works
- [ ] Test career assessment works
- [ ] Test admin CRUD operations
- [ ] Verify all career-degree links
- [ ] Check mobile responsiveness
- [ ] Test on different browsers (Chrome, Firefox, Safari, Edge)

---

## 📝 NOTES

- **Session Handling**: All user sessions are tracked for test results
- **No User Registration**: Platform doesn't require login for assessments
- **Admin-Only Features**: All CRUD operations require admin authentication
- **Scalable Architecture**: Can easily add more careers, degrees, and questions
- **Analytics Ready**: Test results stored in database for analytics

---

## 🆘 TROUBLESHOOTING

### Admin Login Not Working

- Clear browser cookies/cache
- Verify database connection in config.php
- Check that `admin_users` table has data

### Career Test Not Loading

- Verify questions are seeded in database
- Check database connection
- Review browser console for JavaScript errors

### Degree Links Not Showing

- Verify `career_degrees` junction table has data
- Check `getCareerDegrees()` function in config.php
- Ensure both career and degree are marked as active

### Admin Pages Blank

- Check PHP error logs
- Verify all includes are loading correctly
- Test database connection

---

## 📞 SUPPORT

For issues or questions:

1. Check error logs in browser console
2. Verify database structure with phpMyAdmin
3. Review config.php settings
4. Test database queries manually

---

## 📅 VERSION INFO

- **Version**: 1.0 - Complete Refactor
- **Date**: April 2026
- **Database**: MySQL with InnoDB
- **PHP**: 7.4+
- **Framework**: Bootstrap 5.3.2
- **Icons**: Font Awesome 6.4.0

---

## ✨ KEY IMPROVEMENTS IN THIS REFACTOR

1. ✅ **Proper Database Schema** with foreign keys and relationships
2. ✅ **Comprehensive Admin Dashboard** with full CRUD
3. ✅ **Career-Degree Bidirectional Linking** with UI
4. ✅ **20 Strategic Questions** mapped to careers
5. ✅ **Modern UI/UX** with gradient backgrounds and animations
6. ✅ **Responsive Design** for all devices
7. ✅ **Efficient Codebase** with reusable functions
8. ✅ **Security Best Practices** throughout

---

**Ready to launch! 🚀**
