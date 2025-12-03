📱 Job Application Platform – job-app

job-app is the user-facing application of the Job Platform ecosystem.
It allows job seekers to browse vacancies, apply for jobs, upload resumes, manage their profile, and track application status.

This project works together with:

job-backoffice – Admin & Company Dashboard

job-shared – Shared Eloquent Models Package

🚀 Features
👤 User Account Management

Register / Login / Logout

Email verification

Update profile information

Change password

Upload resume (PDF)

🔍 Job Browsing

View all job vacancies

Search by keyword

Filter by category, job type, and location

View detailed job information

📝 Job Applications

Apply to any job vacancy

Attach a resume for each application

Track application status (pending, reviewing, accepted, rejected)

🤖 AI-Powered Resume Analysis

Integrated resume analysis using:

OpenAI API

Spatie PDF-to-Text

Custom ResumeAnalysisService

🗂 Shared Models Integration

All Eloquent models (JobVacancy, JobApplication, Company, User, Resume…) come from the shared package:

job-shared

Ensuring consistent data structures across the entire platform.

📦 Tech Stack

Laravel 12

Blade / TailwindCSS

Laravel Breeze

Vite

OpenAI API

AWS S3 (optional)

Spatie PDF-to-Text

job-shared – shared models package

📁 Project Structure Overview
app/
├── Http/
│ ├── Controllers/
│ ├── Middleware/
│ └── Requests/
├── Services/
│ └── ResumeAnalysisService.php
resources/
├── views/
│ ├── job-vacancies/
│ ├── job-applications/
│ ├── profile/
│ └── layouts/
routes/
├── web.php
└── auth.php

🔗 Using the Shared Package (job-shared)

Configured in composer.json:

"repositories": [
{
"type": "vcs",
"url": "https://github.com/NabilAlsaidaly/job-shared.git"
}
]

Install:

composer require job/shared:@dev

Use models directly:

use App\Models\JobVacancy;

$jobs = JobVacancy::with('company')->latest()->paginate(10);

⚙️ Installation Guide

1. Clone the repository
   git clone https://github.com/NabilAlsaidaly/job-app.git
   cd job-app

2. Install dependencies
   composer install
   npm install

3. Environment setup
   cp .env.example .env
   php artisan key:generate

Configure your database.

4. Migrate the database
   php artisan migrate --seed

5. Start the development servers
   php artisan serve
   npm run dev

🧪 Testing
php artisan test

🎯 Purpose of This Application

Provide a clean, intuitive job search experience

Enable seamless application submission

Offer AI-powered resume insights

Integrate tightly with company owner dashboards

Maintain shared core data through job-shared

📄 License

MIT License.

✔ Notes

Fully compatible with job-backoffice and job-shared.

Resume analysis requires valid OpenAI credentials.

All models are shared across the entire ecosystem for maximum consistency.
