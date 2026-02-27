<div align="center">

# ☠️ SQLVulnInjector ☠️

![License](https://img.shields.io/github/license/YOUR_USERNAME/SQLVulnInjector?color=blueviolet&style=for-the-badge)
![Language](https://img.shields.io/badge/Language-PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Database](https://img.shields.io/badge/Database-SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)
![Topic](https://img.shields.io/badge/Topic-SQL_Injection-red?style=for-the-badge&logo=databricks&logoColor=white)

> A state-of-the-art, visually stunning interactive laboratory for ethical hackers and penetration testers. **100% Zero-Configuration**. Built with an attacker's mindset, documented for defenders.

</div>

---

## 📌 What is SQL Injection?

SQL Injection (SQLi) is an attack technique where malicious SQL code is inserted into an input field to manipulate backend database queries. A successful exploit can allow an attacker to:

- 📖 Read sensitive data from the database
- ✏️ Modify records (Insert / Update / Delete)
- 🛑 Execute admin-level database operations (e.g. shutdown)
- 📂 Recover files from the server filesystem
- 💻 In some cases, execute OS-level commands

SQLi is especially prevalent in **PHP** and **ASP** applications due to older functional interfaces. Java and ASP.NET apps are comparatively less vulnerable due to safer programmatic interfaces — but not immune.

> ⚠️ Severity is limited only by the attacker's skill and imagination. Consider SQL Injection a **high impact** vulnerability.

---

## 🎯 Why This Lab is 10x Better (Features)

Unlike most SQL injection tutorials that just *simulate* strings or require you to set up bulky Docker/MySQL containers, **SQLVulnInjector uses a real, auto-generating SQLite database engine**.

- **Zero Configuration**: No XAMPP, no Docker, no MySQL setup required. Just run PHP. The lab generates a real `database.sqlite` file on the fly.
- **100% Authentic Execution**: Every single payload you enter is executed natively by the SQLite engine. If your payload is malformed, you fail. If it's brilliant, you succeed.
- **Mini-CTF (Capture The Flag)**: The auto-generated database includes a hidden `secret_flags` table. Your ultimate goal across the scenarios is to use `UNION SELECT` and Blind SQLi techniques to extract these flags!
- **Glassmorphic UI**: A dark, glowing interface that visualizes exactly how your payload manipulates the backend query in real-time.

---

## 🚀 Quick Start (Zero Config)

No complex setups required. Just a standard PHP environment.

```bash
git clone https://github.com/YOUR_USERNAME/SQLVulnInjector.git
cd SQLVulnInjector
# Serve with PHP's built-in robust server
php -S localhost:8000
```

Navigate to `http://localhost:8000/`. The lab will instantly build the database and you are ready to hack.

## 💣 Attack Vectors

### 1. Dump an Entire Table

**Goal:** Bypass authentication and extract all user credentials.

| Field | Malicious Input |
|-------|----------------|
| Username | `" OR ""="` |
| Password | `" OR ""="` |

Query becomes:

```sql
SELECT * FROM users WHERE name = "" OR ""="" AND pass = "" OR ""=""
```

`OR ""=""` always evaluates to **true** — returns every row in the table.

---

### 2. Drop a Table via Batched Statements

**Goal:** Permanently destroy a table using stacked queries.

| Field | Malicious Input |
|-------|----------------|
| Username | `nuclearfusion; DROP TABLE Suppliers` |
| Password | `password` |

Query becomes:

```sql
SELECT * FROM users WHERE username = "nuclearfusion"; DROP TABLE stockPortfolio;
```

`DROP TABLE` is an **auto-committed** statement — unlike `DELETE`, it cannot be rolled back. The data and table structure are gone permanently.

---

### 3. `getdata-prepare.php` (Level 3: Prepared Statements) 🔴
The ultimate, industry-standard defense. Parameters are treated strictly as variable data, not executable SQL code. Try your hardest payloads here—they will fail to alter the query logic.

---

## 🔮 Advanced & Modern Scenarios

### 4. `getdata-blind.php` (Blind SQLi / Time-Based) 🥷
The server hides all database errors and does not return the actual tables rows. To extract data, an attacker is forced to ask the database True/False questions (Boolean Blind). To measure time delays in SQLite, attackers can use heavy computational functions like `randomblob()`.
**Try injecting:** `' AND (SELECT randomblob(1000000000)) --`

### 5. `getdata-second-order.php` (Second-Order SQLi) 💣
Shows how a payload can be safely inserted into the database using prepared statements, only to detonate later when an unsuspecting admin script or background job reads that exact stored payload and injects it unsafely into a *second* query. Can you extract the `secret_flags` from here?

### 6. `getdata-json.php` (JSON Structure Injection) 🌐
Modern REST APIs receive JSON objects. This simulation takes a raw JSON string. Break the JSON schema to construct a malicious query.
**Try injecting:** `{"user_id": "1' UNION SELECT id, flag_name, flag_value FROM secret_flags --"}`

---

## 🛡️ Prevention — Parameterized Queries

SQL parameters treat all user input as **literal values**, never as executable SQL. They are added to the query at execution time, not during construction.

```python
name = getRequestString("PatientName")
addr = getRequestString("Address")
city = getRequestString("City")
zipc = getRequestString("Zip")

txtSQL = "INSERT INTO Patients (PatientName,Address,City,Zip) VALUES(@0,@1,@2,@3)"

db.Execute(txtSQL, name, addr, city, zipc)
```

In PHP using PDO:

```php
$stmt = $dbh->prepare("INSERT INTO Patients (PatientName,Address,City,Zip) VALUES (:name, :addr, :city, :zipc)");

$stmt->bindParam(':name', $name);
$stmt->bindParam(':addr', $addr);
$stmt->bindParam(':city', $city);
$stmt->bindParam(':zipc', $zipc);

$stmt->execute();
```

The SQL engine validates each parameter against its expected column type. Injected SQL is treated as a string — not executed.

---

## 📁 Repo Structure

```
SQLVulnInjector/
├── api/
│   ├── setup-db.php             # Auto-generates the SQLite database & CTF flags
│   ├── getdata.php              # Level 1: Raw Injection
│   ├── getdata-encoding.php     # Level 2: Encoding Bypass
│   ├── getdata-prepare.php      # Level 3: Parameterized (Safe)
│   ├── getdata-blind.php        # Level 4: Time-Based / Blind
│   ├── getdata-second-order.php # Level 5: Second-Order 
│   ├── getdata-json.php         # Level 6: JSON Structure Injection
│   └── database.sqlite          # Auto-generated by setup-db.php
├── public/
│   ├── index.html               # Main laboratory interface
│   ├── style.css                # Glassmorphism styling
│   └── script.js                # Frontend API interaction logic
└── README.md
```

---

## ⚡ Quick Start (Zero Config)

```bash
git clone https://github.com/YOUR_USERNAME/SQLVulnInjector.git
cd SQLVulnInjector
# Serve using PHP's built-in robust server, explicitly pointing to the public directory
php -S localhost:8000 -t public
```

Navigate to `http://localhost:8000/`. The lab will instantly build the database and you are ready to hack.

> Tested in a local environment. **Never deploy on a public server.**

---

## ⚠️ Disclaimer

This project is for **educational and research purposes only**. All demonstrations are conducted in an isolated local environment. Unauthorized use of these techniques against systems you do not own is illegal. The author bears no responsibility for misuse.

---

## 📜 License

MIT © [Salehin Ashfi](https://github.com/ashfiexe)

---

<div align="center">
  <sub>Built with curiosity. Documented for defenders.</sub>
</div>