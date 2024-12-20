# Find-A-Buddy: NYC Fitness Community Web App

**Find-A-Buddy** is a web app designed to connect runners, walkers, and bikers across New York City. The goal is to foster an active lifestyle by building local connections based on interests, schedules, and locations.

---

## About The Project

Find-A-Buddy aims to promote a fitness community by:
- Helping users find workout partners based on shared schedules and fitness preferences.
- Allowing users to connect with others in their borough or nearby locations.
- Simplifying the process of finding compatible fitness partners for running, walking, or biking.

---

## Key Features

- **Schedule Management:** Users can set their availability (days and times) and share it with others.
- **Search Functionality:** Filter workout partners by day, time, and activity type.
- **Matching Feature:** Connect with users in your borough or send messages directly.
- **Personalized Profiles:** Custom profiles allow users to specify fitness preferences and improve matching accuracy.
- **Interactive Profile Cards:** Displays users you've interacted with, along with their details and messaging options.

---

## Built With

- **Frontend:** HTML, Tailwind CSS (inline styling), JavaScript.
- **Backend:** PHP.
- **Database:** MySQL.

---

## Getting Started

To set up and run this app locally, follow these steps:

### Prerequisites
- PHP installed on your machine.
- MySQL server for database setup.

### Installation

1. Clone the repository:
   ```sh
   git clone https://github.com/github_username/repo_name.git
   ```

2. Navigate to the project folder:
   ```sh
   cd team-blue/deploy/frontend
   ```

3. Start a local PHP server:
   ```sh
   php -S localhost:8000
   ```

4. Open your browser and navigate to:
   ```
   http://localhost/team-blue/deploy/frontend/index.html
   ```

5. Import the database schema (found in `/backend`) into your local MySQL server.

6. Update the database connection settings in the configuration file.

---

## Usage

- **Create an Account:** Start at the `index.html` page to create a profile, 'index.html' is available in the front end folder.
- **Manage Your Schedule:** Add days and times you’re available to work out.
- **Search for Partners:** Use the search functionality to find partners based on location and availability.
- **Connect and Match:** Use the messaging feature to connect with workout partners.
