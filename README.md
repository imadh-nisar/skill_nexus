# 🚀 Skill NEXUS - Career Guidance Platform

## Overview

Skill NEXUS is a modern, AI-powered career guidance platform that helps users discover their ideal career paths through an intelligent 20-question assessment. Built with PHP, MySQL, and modern web technologies.

## ✨ Features

### 🎯 User-Facing Features

- **Free & Anonymous Assessment**: No registration required, take the 20-question test anytime
- **Intelligent Scoring**: Advanced algorithm matches user answers to 100+ careers
- **Career Recommendations**: Personalized career matches with detailed insights
- **Educational Pathways**: Linked degree recommendations for each career
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile devices
- **Modern UI/UX**: Vibrant gradients, smooth animations, card-based layouts
- **Progress Tracking**: Real-time progress bar and question indicators

### 🛠️ Admin Dashboard

- **Comprehensive Management**: Full CRUD operations for careers, degrees, and questions
- **Secure Authentication**: Admin-only access with login panel
- **Analytics**: View test results and user insights
- **Easy Scalability**: Unlimited careers and degrees, 20 core assessment questions
- **Modern Interface**: Intuitive sidebar navigation and modal-based editing

## 📋 System Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache web server with mod_rewrite
- 50MB disk space minimum

## 🔧 Installation & Setup

### Step 1: Database Setup

1. Open phpMyAdmin or MySQL command line
2. Run the SQL script from `schema.sql`:
   ```bash
   mysql -u root -p < schema.sql
   ```
3. Or paste the contents of `schema.sql` into phpMyAdmin

### Step 2: Initial Admin User

The database comes with a default admin account:

- **Username**: `admin`
- **Password**: `admin123`

**⚠️ IMPORTANT**: Change this password immediately after first login!

### Step 3: Configuration

Edit `config.php` if needed:

- Database host (default: localhost)
- Database name (default: career_guidance)
- Database user (default: root)
- Database password (default: empty)

### Step 4: Test the Installation

1. Navigate to: `http://localhost/skill_nexus/Skill%20Nexus/index.php`
2. You should see the modern homepage
3. Click "Start Test Now" to take the assessment
4. Click "Admin" to access the dashboard

## 📚 User Guide

### Taking the Career Assessment

1. Visit the home page and click "Begin Career Assessment"
2. Answer all 20 thoughtful questions about your interests and values
3. Track your progress with the visual progress bar and question indicators
4. Submit your answers to receive personalized career recommendations
5. View your top 5 career matches with detailed information

### Admin Dashboard Usage

#### Managing Careers

- **Add Career**: Click "Add New Career" button
  - Enter career name, description, salary range, job outlook
  - Set career icon and mark as active
- **Edit Career**: Click "Edit" button on any career row
- **Delete Career**: Click "Delete" button (confirmation required)

#### Managing Degrees

- Similar workflow to careers
- Add educational programs with duration and color coding
- Link degrees to careers through the database relationships

#### Managing Questions

- **Edit Questions**: Modify the 20 core assessment questions
- **Change Category**: Organize questions by category (Interest, Motivation, etc.)
- **Reorder Questions**: Set sequence numbers for question ordering
- **Toggle Activity**: Activate/deactivate questions without deleting

#### Viewing Results

- Dashboard shows total assessment count
- View individual test results with session details
- See career recommendations for each test
- Track assessment analytics over time

## 🎨 Design & Technology

### Frontend

- **HTML5**: Semantic markup
- **CSS3**: Modern gradients, animations, flexbox/grid layouts
- **Bootstrap 5**: Responsive grid system and components
- **JavaScript**: Vanilla JS for interactivity (no jQuery required)
- **Font Awesome 6**: Icon library

### Backend

- **PHP 7.4+**: Server-side logic
- **PDO (PHP Data Objects)**: Database abstraction for security
- **Prepared Statements**: Protection against SQL injection
- **Session Management**: Secure admin authentication

### Animations & Effects

- Fade-in animations on page load
- Smooth hover transitions on buttons and cards
- Progress bar animations
- Slide and float animations
- Gradient overlays and backdrop effects

### Responsive Breakpoints

- Desktop: Full layout with sidebar navigation
- Tablet: Adjusted grid layouts
- Mobile: Single-column layout, touch-friendly buttons

## 🔐 Security Features

- **Password Hashing**: Admin passwords use bcrypt hashing
- **Prepared Statements**: All database queries use parameterized statements
- **Input Validation**: HTML escaping with `htmlspecialchars()`
- **Session Security**: Server-side session management
- **CSRF Protection**: Form-based admin operations
- **No User Data Storage**: Assessments are anonymous

## 📊 Career Scoring Algorithm

The system uses a weighted scoring mechanism:

1. Each career has preferred answer patterns from the assessment
2. For each question, higher weights are assigned to matching answers
3. Total score is calculated as: `(matching_weight_sum / total_weight) * 100`
4. Top 5 careers are recommended based on highest scores
5. Related degrees are fetched for each recommended career

## 🚀 Performance Optimization

- **Database Indexing**: Optimized indexes on all foreign keys and frequently queried columns
- **Query Optimization**: Efficient SELECT statements with proper JOINs
- **Caching**: Session-based caching for test results
- **Lazy Loading**: Data loaded only when needed
- **CDN Resources**: Bootstrap and Font Awesome from CDN

## 📁 Project Structure

```
Skill Nexus/
├── index.php                 # Modern homepage
├── config.php                # Database config & helpers
├── schema.sql                # Database schema
├── README.md                 # This file
├── admin/
│   ├── login.php            # Admin authentication
│   ├── dashboard.php        # Admin home with stats
│   ├── careers.php          # Manage careers (CRUD)
│   ├── degrees.php          # Manage degrees (CRUD)
│   ├── questions.php        # Manage assessment questions
│   ├── results.php          # View test analytics
│   └── logout.php           # Admin logout
├── career/
│   ├── test.php             # 20-question assessment interface
│   └── results.php          # Career recommendations page
├── results/
│   ├── careers.php          # Browse all careers
│   └── degrees.php          # Browse all degrees
├── auth/
│   ├── login.php            # (Legacy - not used)
│   └── registration.php     # (Legacy - not used)
└── cj/
    └── css/                 # Bootstrap CSS files
```

## 🛠️ Troubleshooting

### Database Connection Error

- Check MySQL is running
- Verify credentials in `config.php`
- Ensure database name is `career_guidance`

### Admin Login Not Working

- Default credentials: admin / admin123
- Check `admin_users` table exists
- Ensure your browser accepts cookies

### Test Not Submitting

- Check browser console for JavaScript errors
- Verify all 20 questions have answers selected
- Check PHP error logs in Apache

### Animations Not Working

- Update your browser (IE 11 may have issues)
- Check CSS is loading from Bootstrap CDN
- Clear browser cache and reload

## 📈 Future Enhancements

- [ ] User accounts for saving results
- [ ] Job posting integration
- [ ] Video tutorials for each career
- [ ] Skill gap analysis
- [ ] Career path planning with milestones
- [ ] Employer partnerships
- [ ] Mobile app version
- [ ] Multi-language support
- [ ] Advanced analytics dashboard
- [ ] Email notifications

## 📞 Support & Maintenance

For issues or questions:

1. Check the troubleshooting section
2. Review PHP error logs: `/var/log/apache2/error.log`
3. Inspect browser console (F12)
4. Verify database integrity with phpMyAdmin

## 📄 License & Credits

Built with modern web technologies and best practices for scalability, security, and user experience.

---

**Version**: 1.0  
**Last Updated**: April 2026  
**Platform**: Skill NEXUS Career Guidance
