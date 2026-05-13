# AI-Assisted English Grammar Learning Web Application

A web-based English grammar learning system developed using **Laravel**, **Blade**, **MySQL**, **Bootstrap**, and the **OpenAI API**.

The system helps English learners improve their grammar through AI-powered grammar checking, topic-based grammar quizzes, personal learning history, and progress tracking.

---

## Project Overview

Many English learners struggle with grammar when writing sentences or paragraphs. They may not always have access to a teacher or immediate feedback. This project addresses that problem by providing a simple web application where users can check grammar, understand mistakes, practise grammar through quizzes, and review their learning history.

The application focuses on two main learning activities:

1. AI-assisted grammar correction
2. Topic-based grammar practice quizzes

The system is designed to be simple, accessible, and suitable for beginner to intermediate English learners.

---

## Main Features

### User Features

- Register and log in
- Use the AI grammar checker
- Check grammar from typed text
- Upload supported document files for grammar checking
- View corrected text and simple explanations
- Download grammar check results
- View personal grammar check history
- Attempt quizzes by category and topic
- View quiz scores and answer explanations
- View quiz attempt history
- Access dashboard summary

### Admin Features

- Manage users
- View user learning history
- Manage grammar quiz categories
- Manage grammar quiz topics
- Manage quiz questions

---

## System Modules

### 1. AI Grammar Checker

Users can enter English text and receive:

- corrected grammar
- explanation of mistakes
- simple learning feedback

The grammar checker uses the OpenAI API to generate grammar corrections and explanations.

---

### 2. Grammar Check History

Each grammar check is saved in the database. Users can view their own previous grammar checks, including:

- original text
- corrected text
- explanation
- date and time checked

Each user can only view their own history.

---

### 3. Topic-Based Quiz Module

Users can practise grammar through quizzes. The quiz structure is organised as:

```text
Category -> Topic -> Questions -> Quiz Attempt
```

Example categories and topics may include:

- Tenses
- Prepositions
- Articles
- Subject-Verb Agreement
- Modal Verbs

After completing a quiz, users can view their score and answer explanations.

---

### 4. Dashboard

The dashboard provides a summary of the user’s learning activity, such as:

- total grammar checks
- total quiz attempts
- average quiz score
- latest grammar check
- latest quiz attempt

---

### 5. Admin Management

Admin users can manage the learning content of the system, including:

- users
- categories
- topics
- questions

This allows the quiz content to be updated without changing the application code.

---

## Technologies Used

| Area | Technology |
|---|---|
| Backend | Laravel |
| Frontend | Blade |
| Styling | Bootstrap |
| Database | MySQL |
| AI Integration | OpenAI API |
| Authentication | Laravel Breeze |
| Version Control | Git and GitHub |
| Development Tool | Visual Studio Code |

---

## Requirements

Before running the project, make sure the following are installed:

- PHP 8.3 or above
- Composer
- MySQL
- Git
- OpenAI API key

Node/NPM may be included by Laravel Breeze, but the main custom UI uses Bootstrap with Blade.

---

## Installation Guide

### 1. Clone the Repository

```bash
git clone https://github.com/ChoLwinThant/english-grammar-system.git
cd english-grammar-system
```

---

### 2. Install PHP Dependencies

```bash
composer install
```

---

### 3. Create Environment File

```bash
cp .env.example .env
```

On Windows, if the above command does not work, use:

```bash
copy .env.example .env
```

---

### 4. Generate Application Key

```bash
php artisan key:generate
```

---

### 5. Configure Database

Create a MySQL database:

```sql
CREATE DATABASE english_grammar_system;
```

Then update the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=english_grammar_system
DB_USERNAME=root
DB_PASSWORD=
```

If your MySQL has a password, add it to `DB_PASSWORD`.

---

### 6. Add OpenAI API Key

Add your OpenAI API key to the `.env` file:

```env
OPENAI_API_KEY=your_openai_api_key
OPENAI_MODEL=gpt-4.1-mini
```

Do not share your API key or push it to GitHub.

---

### 7. Run Database Migrations

```bash
php artisan migrate
```

---

### 8. Run Database Seeders

The project includes seeders for initial sample data such as admin user, quiz categories, topics, and questions.

To insert the sample data, run:

```bash
php artisan db:seed
```

Alternatively, to reset the database and insert the sample data again, run:

```bash
php artisan migrate:fresh --seed
```

> Warning: `migrate:fresh --seed` will delete all existing database records before recreating the tables and inserting seed data.

---

### 9. Start the Development Server

```bash
php artisan serve
```

Open the application in your browser:

```text
http://127.0.0.1:8000
```

---

## Admin Setup

By default, registered users are normal users.

To make a user an admin, update the `role` field in the `users` table:

```sql
UPDATE users 
SET role = 'admin' 
WHERE email = 'your-email@example.com';
```

After updating the role, log out and log in again.

Admin pages can then be accessed through the admin section of the application.

---

## Sample User Flow

1. Register a new account
2. Log in to the system
3. Open the grammar checker
4. Enter a sentence or paragraph
5. Submit the text for AI grammar correction
6. View the corrected text and explanation
7. Check grammar history
8. Open the quiz section
9. Choose a category
10. Choose a topic
11. Attempt the quiz
12. View score and explanations
13. Review progress from the dashboard

---

## Example Grammar Check

### User Input

```text
She go to school everyday.
```

### Expected Output

```text
Corrected Text:
She goes to school every day.

Explanation:
Use "goes" because the subject "She" is third person singular. Also, "every day" should be written as two words when referring to each day.
```

---

## Project Structure

```text
app/
├── Http/
│   ├── Controllers/
│   └── Middleware/
├── Models/
└── Services/

database/
├── migrations/
└── seeders/

resources/
└── views/

routes/
└── web.php
```

---

## Important Directories

| Path | Purpose |
|---|---|
| `app/Http/Controllers` | Handles application logic |
| `app/Models` | Database models |
| `app/Services` | Service classes such as AI grammar correction |
| `database/migrations` | Database table structure |
| `database/seeders` | Optional sample data setup |
| `resources/views` | Blade user interface files |
| `routes/web.php` | Web route definitions |

---

## Main Routes

| Route | Description |
|---|---|
| `/` | Home page |
| `/dashboard` | User dashboard |
| `/grammar-check` | AI grammar checker |
| `/grammar-history` | User grammar check history |
| `/quiz` | Quiz category selection |
| `/quiz-history` | User quiz attempt history |
| `/admin/users` | Admin user management |
| `/admin/categories` | Admin category management |
| `/admin/topics` | Admin topic management |
| `/admin/questions` | Admin question management |

---

## Security Notes

- User authentication is required for grammar checking, quiz attempts, and history.
- Admin pages are protected by admin middleware.
- Users can only view their own grammar check history and quiz history.
- The OpenAI API key is stored in the `.env` file and should not be committed to GitHub.
- Passwords are handled securely through Laravel’s authentication system.

---

## Current Limitations

- The AI grammar checker depends on the availability of the OpenAI API.
- AI-generated feedback may not always be perfect and should be used as learning support.
- Uploaded document grammar checking works best with text-based files.
- Scanned image documents are outside the current project scope.
- Quiz content depends on the available categories, topics, and questions added by the admin.

---

## Future Improvements

Possible future enhancements include:

- Add more grammar topics and question banks
- Add AI-generated quiz questions
- Add charts for progress tracking
- Add teacher or instructor role
- Improve document text extraction accuracy
- Add unit and feature tests

---

## Author

**Cho Lwin Lwin Thant**

Final Year Project  
AI-Assisted English Grammar Learning Web Application