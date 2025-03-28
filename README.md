# DropWise - A Secure File-Sharing Platform 

## 📌 Project Overview
DropWise is a user-friendly file-sharing platform that allows users to upload and share files without requiring login credentials. Users can set passwords and expiration dates to ensure secure file access. The platform is built using PHP, MySQL, and Bootstrap for an intuitive and responsive experience.

## 🚀 Features
- **Anonymous File Upload** - No need to create an account to share files.
- **Password Protection** - Secure shared files with a password.
- **Expiry Date** - Set a validity period for each file to automatically restrict access after expiration.
- **Shareable Links** - Generate unique links to share files securely.
- **File Type Support** - Supports JPG, JPEG, PNG, PDF, and DOCX files.
- **Bootstrap UI** - A clean and responsive frontend using Bootstrap.

## 🏗️ Tech Stack
- **Frontend**: HTML, CSS, Bootstrap 5
- **Backend**: PHP (Procedural)
- **Database**: MySQL
- **Storage**: Local File System (`uploads/` directory)

## 📂 Project Structure
```
DropWise/
│── src/                 # Static assets (icons, images, etc.)
│── uploads/             # Directory for uploaded files
│── index.php            # Homepage with file upload form
│── files.php            # Displays uploaded files
│── access_file.php      # Handles file access and password verification
│── db_connect.php       # Database connection script
│── README.md            # Project documentation
```

## ⚙️ Installation & Setup
### 1️⃣ Clone the Repository
```bash
git clone https://github.com/riyankasarkar/CodeClauseInternship_-File-Sharing-Platform.git
cd File-Sharing-Platform
```

### 2️⃣ Configure the Database
1. Create a MySQL database named `file_sharing`.
2. Run the following SQL commands to create the necessary tables:
```sql
CREATE TABLE upload_file (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    expiry_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE file_metadata (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_type VARCHAR(255) NOT NULL,
    file_size INT NOT NULL,
    share_link VARCHAR(255) NOT NULL,
    FOREIGN KEY (file_id) REFERENCES upload_file(id)
);
```

### 3️⃣ Configure Database Connection
Edit `db_connect.php` with your database credentials:
```php
$serverName = 'localhost';
$userName = 'root';
$password = '';
$dbName = 'file_sharing';
$conn = new mysqli($serverName, $userName, $password, $dbName);
if ($conn->connect_error) {
    die("Connection Failed" . $conn->connect_error);
}
```

### 4️⃣ Start the Project
- Place the project files in your local server directory (e.g., `htdocs` for XAMPP).
- Start your Apache and MySQL server (using XAMPP or another local server tool).
- Open a browser and go to: `http://localhost/File-Sharing-Platform/`

## 🛠️ Usage
1. Click the **Upload** button to select a file.
2. Enter your **Name**, set a **Password**, and specify an **Expiry Date**.
3. Click **Upload** to share the file.
4. Access the file via the generated **Share Link**.
5. Enter the correct **Password** to download the file.
6. Expired files will be marked as **Expired** and cannot be accessed.

## 🔒 Security Considerations
- Passwords are **hashed** using `password_hash()` before storage.
- Files can only be accessed if the correct **password** is provided.
- Expired files are **not accessible** for security reasons.

## 📜 License
This project is licensed under the MIT License

## 🤝 Contributing
Pull requests are welcome! If you find a bug or have suggestions, feel free to open an issue.

## 📬 Contact
If you have any questions or need further assistance, feel free to reach out:
- **Email**: msriyankasarkar@gmail.com
- **GitHub**: [Riyanka  Sarkar] (https://github.com/riyankasarkar)

---
Enjoy sharing files securely with **DropWise**! 🎉

