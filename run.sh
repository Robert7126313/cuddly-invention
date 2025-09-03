#!/usr/bin/env bash
set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# Initialize the SQLite database
php backend/init_db.php

# Start the PHP development server
php -S localhost:8000 -t backend &
SERVER_PID=$!

# Ensure the server is stopped when this script exits
cleanup() {
  kill $SERVER_PID 2>/dev/null
}
trap cleanup EXIT INT TERM

# Try to open the frontend in the default browser
if command -v xdg-open >/dev/null 2>&1; then
  xdg-open "$SCRIPT_DIR/frontend/index.html" >/dev/null 2>&1 &
elif command -v open >/dev/null 2>&1; then
  open "$SCRIPT_DIR/frontend/index.html" >/dev/null 2>&1 &
fi

echo "Backend running at http://localhost:8000"
echo "Press Ctrl+C to stop the server."

wait $SERVER_PID
