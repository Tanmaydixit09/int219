# GIS-Based Farm Management System

A comprehensive Geographic Information System (GIS) based farm management application for tracking crops, fields, and agricultural data.

## Features

- **Dashboard** - Overview of farm statistics and key metrics
- **Field Map** - Interactive GIS-based field mapping
- **Crop Progression** - Track crop growth and development
- **Progress Reports** - Generate and view agricultural reports
- **GIS Integration** - Advanced geographic visualization
- **User Authentication** - Secure login and signup system

## Project Structure

```
GIS/
├── api/                  # API endpoints
├── config/               # Configuration files
├── css/                  # Stylesheets
├── database/             # Database schemas
├── features/             # Feature modules
│   ├── crop-progression/
│   ├── gis-integration/
│   └── progress-reports/
├── models/               # Data models
├── public/               # Public assets
│   ├── css/
│   └── js/
├── sql/                  # SQL scripts
├── src/                  # Source files
├── templates/            # HTML templates
├── dashboard.html
├── field_map.html
├── gis-integration.html
├── index.html
├── login.html
├── my_crops.html
├── settings.html
└── signup.html
```

## Tech Stack

- HTML5, CSS3, JavaScript
- PHP for backend
- GIS mapping libraries
- Tailwind CSS
- MySQL database

## Getting Started

1. Clone the repository
2. Set up the database using `database.sql` or `database/schema.sql`
3. Configure database connection in `config/database.php`
4. Run on a local server (PHP/MySQL)

## License

MIT License
