<img width="1898" height="930" alt="Screenshot 2026-04-18 105939" src="https://github.com/user-attachments/assets/1f5752f0-d595-486a-bba1-625d3960afc7" />

# Climate-Disease Surveillance System

## Project Overview

The **Climate-Disease Surveillance System** is a database-based DBMS project designed to store, manage, and analyze climate and disease-related data. The system helps monitor disease cases, climate conditions, high-risk regions, hospitals, alerts, and preventive actions.

This project is useful for analyzing the relationship between climate factors such as temperature, rainfall, and humidity with disease outbreaks in different regions.

## Objectives

- To store climate and disease data in a structured database
- To track disease cases region-wise
- To analyze climate impact on disease outbreaks
- To identify high-risk regions
- To generate alert notifications
- To support health-related decision-making

## Technologies Used

- HTML
- CSS
- Bootstrap
- PHP
- MySQL
- JavaScript
- Chart.js
- Leaflet.js
- GeoJSON
- XAMPP

## System Architecture

```text
Frontend (HTML, CSS, Bootstrap, JavaScript)
                ↓
Backend (PHP)
                ↓
Database (MySQL)
```

## Main Features
Admin login system
Dashboard summary cards
Region-wise climate data management
Disease case tracking
High-risk region detection
Alert notification system
Hospital information management
Preventive action records
Chart-based data analysis
Bangladesh map visualization
Dynamic data fetching from MySQL database

## Database Connection

The system is connected to the MySQL database using PHP.

## Dashboard Data Flow
```text
MySQL Database
      ↓
PHP config.php
      ↓
SQL Query
      ↓
PHP Data Fetch
      ↓
HTML Dashboard Output
```
The dashboard displays real-time dynamic data from the database whenever the page loads.

## Data Visualization

The project uses:

Chart.js for bar charts, pie charts, and line charts
Leaflet.js and GeoJSON for Bangladesh map visualization
<img width="1310" height="929" alt="Screenshot 2026-04-18 110046" src="https://github.com/user-attachments/assets/c29a3820-690a-405d-8073-06b4240b2634" />

These visualizations help users understand disease trends, climate patterns, and high-risk areas easily.

## How to Run the Project
Install XAMPP.
Start Apache and MySQL from XAMPP Control Panel.
Copy the project folder into the htdocs directory.
Open phpMyAdmin.
Create a database named:
climate_disease_bd
Import the SQL database file.
Open the project in browser:
http://localhost/project_folder_name/

Admin User

This system is mainly used by authorized administrators such as:

Health officers
Researchers
Data analysts
System managers

Administrators can insert, update, analyze, and monitor climate and disease data.

## Project Benefits

Helps monitor disease outbreaks
Supports climate-disease relationship analysis
Identifies high-risk regions
Helps health departments take preventive action
Provides visual dashboard for decision-making
Improves database management skills through real-world DBMS design

## Limitations

The system does not use real-time external data
Prediction system is not included
The dataset is sample-based
Public user access is not included

## Future Improvements

Add AI-based disease prediction
Add real-time climate data API
Add mobile application support
Add advanced search and filtering
Add user role management
Add automated email or SMS alerts
