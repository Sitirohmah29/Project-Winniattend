**Siti Rohmah - Fullstack Developer**  
**Risma Handayani - Laravel Developer**
**Teknologi Informasi - Institut Bisnis dan Informatika Kesatuan**

# Project WinniAttend

WinniAttend is a Laravel-based attendance web application using Tailwind CSS for frontend styling.

## 📦 Cloning and Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/Sitirohmah29/Project-Winniattend.git
   cd Project-Winniattend
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies (Tailwind & others)**
   ```bash
   npm install
   ```

4. **Copy the environment configuration**
   ```bash
   cp .env.example .env
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Run database migrations**
   ```bash
   php artisan migrate
   ```

> Make sure your `.env` file is properly configured for your database before running the migration.

---

## 🚀 Running the Application

### 1. Start Laravel backend server
```bash
php artisan serve
```
The application will be available at `http://localhost:8000`

### 2. Start Tailwind (frontend development build)
```bash
npm run dev
```
This watches your Tailwind CSS files and recompiles them when changes are made.

> Use `npm run build` for a production-optimized CSS build.

---

## 🔀 Git Workflow Guide

### Check your current branch
```bash
git branch
```

### Create a new branch
```bash
git checkout -b your-branch-name
```

### Push your branch to GitHub
```bash
git push origin your-branch-name
```

### Merge a teammate's branch into main

1. Switch to main and pull the latest changes:
   ```bash
   git checkout main
   git pull origin main
   ```

2. Merge the remote branch:
   ```bash
   git merge origin/teammate-branch-name
   ```

3. Push the updated main branch to GitHub:
   ```bash
   git push origin main
   ```

---

## ✅ Tips

- Always commit or stash your local changes before performing a merge.
- Use `npm run dev` during development to keep Tailwind styles updated.
- Confirm your `.env` file has the correct database and app config.

---

## 🛠️ Technologies Used

- Laravel 10.x
- Tailwind CSS
- MySQL / MariaDB
- Git & GitHub

