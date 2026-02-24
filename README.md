# 🏠 EasyColoc

EasyColoc is a modern and intuitive web application designed to help roommates seamlessly manage their shared living expenses, household tasks, and communications. Built with robustness and simplicity in mind, it provides a centralized dashboard to ensure transparency and harmony in any colocation.

## ✨ Features

- **🔐 User Roles & Authentication**: Secure registration and login using Laravel's core auth systems, tailored with a professional UI.
- **🏢 Colocation Management**: Create, update, and manage your colocation space. Leave or delete a colocation when moving out.
- **✉️ Invitation System**: Easily invite new roommates to join your colocation via secure invites (with expiration for added security).
- **💸 Expense Tracking**: Log shared bills and purchases. Track who paid for what:
  - Add expense details (title, amount, date, and category).
  - Automatically calculate split shares among roommates.
  - "Mark as Paid" functionality for expense creators.
- **📊 Expense Categories**: Organize expenses into categories for better financial overview.
- **🤝 Settlements**: Handle individual settlements between roommates to balance out shared costs automatically.
- **🛡️ Administration**: Admin dashboard to manage and oversee platform activity.

## 🛠️ Tech Stack

- **Backend**: [Laravel](https://laravel.com/) (PHP)
- **Frontend**: Blade Templates, [Tailwind CSS](https://tailwindcss.com/) (Custom *Cyan* design system)
- **Database**: MySQL / SQLite (via Laravel Eloquent ORM)
- **Assets**: Vite

## 🚀 Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- Node.js & npm

### Installation

1. **Clone the repository** (if applicable):
   ```bash
   git clone https://github.com/Ot4man/Easy_Coloc.git
   cd Easy_Coloc
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Install Frontend dependencies**:
   ```bash
   npm install
   ```

4. **Environment Setup**:
   Copy the example environment file and configure your database settings.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Run Migrations & Seeders**:
   Set up your tables—roles, users, colocations, invitations, categories, expenses, and settlements.
   ```bash
   php artisan migrate --seed
   ```

6. **Compile Assets**:
   ```bash
   npm run build
   # Or for development:
   # npm run dev
   ```

7. **Serve the Application**:
   ```bash
   php artisan serve
   ```
   



## 📄 License

This project is licensed under the [MIT license](https://opensource.org/licenses/MIT).
