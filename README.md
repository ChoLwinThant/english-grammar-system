# AI-Assisted English Grammar Learning Web Application

A web-based English grammar learning system developed using **Laravel**, **Blade**, **MySQL**, **Bootstrap**, and **OpenAI**.  
The system helps users improve their English grammar through **AI-powered grammar checking**, **topic-based quizzes**, and **progress tracking**.

---

## Project Overview

This project is designed to support English learners by providing:

- AI-powered grammar correction with simple explanations
- Topic-based grammar quizzes
- User authentication and personal history tracking
- Admin management for categories, topics, and questions

The system aims to create an interactive and accessible environment for self-learning.

---

## Features

### User Features
- Register and log in
- Use AI grammar checker
- View personal grammar check history
- Attempt quizzes by category and topic
- View quiz results with explanations
- View personal quiz history
- Access dashboard with learning summary

### Admin Features
- Manage categories
- Manage topics
- Manage questions

---

## Technologies Used

- **Backend:** Laravel
- **Frontend:** Blade, Bootstrap
- **Database:** MySQL
- **AI Integration:** OpenAI API
- **Version Control:** Git, GitHub
- **Development Tool:** Visual Studio Code

---

## System Modules

### 1. AI Grammar Checker
Users can enter a sentence or paragraph and receive:
- corrected text
- explanation of grammar mistakes

### 2. Grammar Quiz Module
Users can:
- choose a category
- choose a topic
- answer multiple-choice grammar questions
- receive scores and answer explanations

### 3. User History and Dashboard
The system stores:
- grammar check history
- quiz attempts
- dashboard summary such as total checks and total quiz attempts

### 4. Admin Management
The admin can manage:
- categories
- topics
- questions

---

## Installation Guide

### 1. Clone the repository
```bash
git clone https://github.com/your-username/english-grammar-system.git
cd english-grammar-system
```
### 2. Install dependencies
```bash
composer install
```
### 3. Create environment file
```bash
cp .env.example .env
```
### 4. Generate app key
```bash
php artisan key:generate
```
### 5. Configure database
Edit .env:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=english_grammar_system
DB_USERNAME=root
DB_PASSWORD=
```
### 6. Add OpenAI API key
```env
OPENAI_API_KEY=your_openai_api_key
OPENAI_MODEL=gpt-4.1-mini
```
### 7. Run migrations
```bash
php artisan migrate
```
### 8. Start server
```bash
php artisan serve
```
Open in browser:
```bash
http://127.0.0.1:8000
```

---

## Admin Setup
By default, registered users are normal users.
To make a user an admin, update the role field in the users table:
```sql
UPDATE users
SET role = 'admin'
WHERE email = 'your-email@example.com';
```

---

## Sample User Flow
1. Register or log in
2. Use the grammar checker to check a sentence
3. View saved grammar history
4. Go to Quiz
5. Choose a category and topic
6. Attempt the quiz
7. View score and explanations
8. Review quiz history on the dashboard

---

## Project Structure
app/

database/

resources/views/

routes/

public/

### Important areas:

app/Http/Controllers → controllers

app/Models → database models

resources/views → Blade templates

routes/web.php → web routes

---
