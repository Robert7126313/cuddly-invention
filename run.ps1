# Initializes the database, starts the PHP server, and opens the frontend.

$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $scriptDir

# Initialize the SQLite database
php backend/init_db.php

# Start the PHP server
$server = Start-Process -FilePath "php" -ArgumentList "-S localhost:8000 -t backend" -PassThru

# Open the frontend in the default browser
Start-Process (Join-Path $scriptDir "frontend/index.html")

Write-Host "Backend running at http://localhost:8000"
Write-Host "Press Enter to stop the server..."
[void][System.Console]::ReadLine()

Stop-Process -Id $server.Id
