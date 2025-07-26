# 🗑️ Waste Management Feedback System

## 📖 Overview

The **Waste Management Feedback System** is a web-based application designed to streamline communication between residents and waste management authorities. It enables users to:

- Report waste-related issues
- Track resolution status
- View waste collection schedules

---

## ✨ Features

### 👥 User Roles
- **Residents:** Submit feedback and monitor issue status  
- **Waste Team Members:** Manage assigned tasks and update progress  
- **Administrators:** Oversee system operations and manage users

### ⚙️ Core Functionality
- Image-supported feedback submissions  
- Task assignment & progress tracking  
- Collection schedule management  
- Real-time status updates  
- Notification system for updates

---

## ⚙️ Installation

### ✅ Requirements
- PHP 7.4 or higher  
- MySQL 5.7 or higher  
- Apache/Nginx Web Server  
- [Composer](https://getcomposer.org/) for dependency management  

### 📦 Setup Instructions

1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/waste-management-system.git
   cd waste-management-system
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Set up the database:**
   - Create a MySQL database.
   - Import the schema:
     ```sql
     Import database/waste_management_system.sql
     ```
   - Configure your database connection in:
     ```
     config/db.php
     ```

4. **Configure XAMPP:**
   - Place the project folder in:
     ```
     C:\xampp\htdocs\Waste-Management
     ```
   - Start **Apache** and **MySQL** in XAMPP.

5. **Set file permissions (Linux/Unix only):**
   ```bash
   chmod -R 755 assets/images/uploads
   ```

---

## 🚀 Usage

### 🔗 Accessing the System
- **Frontend:** `http://localhost/Waste-Management`  
- **Admin Panel:** `http://localhost/Waste-Management/admin/dashboard.php`

### 🔐 Default Accounts

| Role               | Email                | Password     |
|--------------------|----------------------|--------------|
| **Admin**          | admin@example.com    | `password` *(change immediately)* |
| **Waste Team**     | team@example.com     | `wasteteam`  |
| **Resident**       | Register via the signup page |

---

## 📁 File Structure

```
/Waste-Management
├── /admin               # Admin panel
├── /assets              # Static files
│   ├── /css             # Stylesheets
│   ├── /images          # Image uploads
│   └── /js              # JavaScript files
├── /config              # Configuration files
├── /database            # SQL schema
├── /includes            # Shared PHP components
├── index.php            # Homepage
├── login.php            # Login page
├── register.php         # Signup page
├── feedback.php         # Feedback form
├── status.php           # Feedback status page
└── schedule.php         # Collection schedule
```

---

## 🧩 API Endpoints *(Optional)*

> If your system exposes any APIs, list them here. Otherwise, remove this section.

---

## 🛠️ Troubleshooting

### 🐘 Database Connection Errors
- Double-check credentials in `config/db.php`
- Ensure the MySQL service is running

### 🖼️ File Upload Issues
- Confirm `assets/images/uploads` is writable
- Verify PHP settings (`upload_max_filesize`, `post_max_size`) in `php.ini`

### 🔍 Page Not Found Errors
- Check `.htaccess` file configuration
- Verify all file paths in the `/includes` directory

---

## 🤝 Contributing

1. Fork the repository  
2. Create a new branch  
   ```bash
   git checkout -b feature/AmazingFeature
   ```
3. Make your changes and commit  
   ```bash
   git commit -m "Add AmazingFeature"
   ```
4. Push to your fork  
   ```bash
   git push origin feature/AmazingFeature
   ```
5. Open a Pull Request  

---

## 📄 License

This project is licensed under the [MIT License](LICENSE.md).
