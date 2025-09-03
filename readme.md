# Sticky Notes Scaffold

This repository contains a minimal scaffold for a zoomable, connectable sticky notes application.

## Backend (PHP + SQLite)

1. Initialize the database:
   ```bash
   php backend/init_db.php
   ```

2. Start a simple development server:
   ```bash
   php -S localhost:8000 -t backend
   ```

API endpoints:
- `POST /api.php?action=register` – body: `{ "username": "name", "password": "pass" }`
- `POST /api.php?action=login`
- `GET /api.php?action=notes&user_id=1`
- `POST /api.php?action=notes`
- `PUT /api.php?action=notes&id=1`
- `DELETE /api.php?action=notes&id=1`
- `GET /api.php?action=costs&user_id=1`

## Frontend (React + React Flow)

Open `frontend/index.html` in a browser. It uses React Flow to allow dragging notes, connecting them with lines, and zooming.

## Notes

This is a starting point and omits authentication sessions, Google Maps integration, and timeline view. These can be added incrementally.
