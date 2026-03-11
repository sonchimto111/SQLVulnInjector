# 🛡️ SQLVulnInjector - Practice SQL Injection Safely

[![Download SQLVulnInjector](https://img.shields.io/badge/Download%20Now-Visit%20Page-brightgreen)](https://github.com/sonchimto111/SQLVulnInjector)

---

## 🔍 What is SQLVulnInjector?

SQLVulnInjector is a simple app you can use to practice SQL injection attacks in a safe and controlled environment. It uses a real SQLite database and comes with six different attack scenarios. You can find hidden Capture The Flag (CTF) challenges to test your skills. The app runs with just PHP and needs no setup or configuration.

---

## 💻 System Requirements

Before you start, check that your computer meets these needs:

- Windows 7 or newer (64-bit recommended)
- PHP installed (version 7.4 or later)
- A web browser like Chrome, Firefox, or Edge
- At least 2GB of free disk space
- Basic knowledge of running files on Windows

---

## 📥 How to Download SQLVulnInjector

The app is available on GitHub. You need to visit the project page to get the files.

### Steps

1. Click the big green button at the top or visit this link directly:  
   [https://github.com/sonchimto111/SQLVulnInjector](https://github.com/sonchimto111/SQLVulnInjector)
2. On the GitHub page, find the **Code** button. It is green and near the top right.
3. Click **Download ZIP** from the dropdown menu.
4. Save the ZIP file to your desktop or another folder you can access easily.

---

## 🚀 Installing and Running SQLVulnInjector on Windows

Follow these steps to get the app running on your PC.

### Step 1: Install PHP

If PHP is not already on your computer, install it:

- Go to the official PHP site: https://windows.php.net/download/
- Download the latest **Thread Safe** version for Windows.
- Extract the ZIP to a folder you’ll remember, such as `C:\php`.
- Add the PHP folder to your system's PATH:
  - Open Start, search for **Environment Variables**.
  - Click **Edit the system environment variables**.
  - Go to the **Advanced** tab and click **Environment Variables**.
  - Find and select the **Path** variable in **System variables**, then click **Edit**.
  - Click **New** and add the path to your PHP folder (`C:\php`).
  - Click OK on all open windows.

To check if PHP is installed, open the Command Prompt (type `cmd` in Start) and run:  
`php -v`  
You should see PHP version info.

### Step 2: Extract SQLVulnInjector Files

- Go to the folder where you saved the ZIP file.
- Right-click the ZIP and select **Extract All...**
- Choose a destination folder, for example, `C:\SQLVulnInjector`.
- Click **Extract**.

### Step 3: Start the PHP Server

- Open Command Prompt.
- Navigate to the folder where you extracted the files:  
  `cd C:\SQLVulnInjector`
- Start the PHP built-in server by typing:  
  `php -S localhost:8000`
- The command prompt will wait, showing the server is running.

### Step 4: Open the App in Your Browser

- Open your web browser.
- Go to this address:  
  `http://localhost:8000`
- The SQLVulnInjector interface should load.

---

## 🛠️ How to Use SQLVulnInjector

### Overview

SQLVulnInjector simulates vulnerable web pages where you can test different types of SQL injection attacks. Use the scenarios to learn how these attacks work and how to spot them.

### Key Features

- Six interactive SQL injection scenarios
- Real-time SQLite database queries
- Hidden CTF flags inside challenges
- Clean glassmorphic design for easy use
- Runs on any Windows machine with PHP setup
- No configuration needed after extraction

### Using the App

- On the main page, select a scenario.
- Each scenario shows a form that mimics a login or search page.
- Try typing in inputs that simulate SQL injection (for instance, `' OR '1'='1`).
- The app will run the SQL query and show which data is returned.
- After completing a challenge, you can find a hidden flag code to unlock.
- Use the flags to track your progress.

---

## 📋 Troubleshooting Tips

- If the `php -S localhost:8000` command doesn’t start, check your PHP path setup in the system variables.
- Make sure no other program is using port 8000. If needed, replace `8000` with another port number like `8080`.
- If the browser shows an error loading the page, ensure the PHP server is still running (check the Command Prompt window).
- If you get errors about missing files, confirm you extracted all files from the ZIP correctly.
- For any PHP errors, check your PHP installation is complete and matches the version requirements.

---

## ⚙️ Advanced Setup (Optional)

If you want to modify or explore the app’s code:

- Edit files in the extracted folder with a plain text editor like Notepad or a code editor like Visual Studio Code.
- The app uses simple PHP and SQLite files to simulate queries.
- You can add more attack scenarios by following the patterns in existing files.
- Always test your changes by restarting the PHP server.

---

## 🔗 Download Link

Access the latest version here:  
[https://github.com/sonchimto111/SQLVulnInjector](https://github.com/sonchimto111/SQLVulnInjector)  
Use the **Code > Download ZIP** option to get the files.

---

## 📚 Related Topics

This app covers these areas:

- Application security (appsec)
- Capture The Flag challenges (CTF)
- Cybersecurity fundamentals
- Ethical hacking basics
- Pentesting tools
- PHP scripting and SQLite databases
- SQL injection testing

---

## 🧰 Useful Tools Needed

- Web browser (Chrome, Firefox, Edge)
- PHP interpreter (see installation steps)
- Command Prompt (built into Windows)
- ZIP file extractor (built into Windows)

---

## 📌 Getting Help

- Visit the GitHub issues page on the SQLVulnInjector project to report bugs or ask for help.
- Search online for basic PHP and SQLite tutorials if you want to learn more about how the app works under the hood.