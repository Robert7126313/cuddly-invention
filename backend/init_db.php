<?php
$db = new PDO('sqlite:' . __DIR__ . '/data/app.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec('CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT UNIQUE,
  password TEXT,
  created_at TEXT DEFAULT CURRENT_TIMESTAMP
)');

$db->exec('CREATE TABLE IF NOT EXISTS notes (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER,
  title TEXT,
  content TEXT,
  x REAL,
  y REAL,
  color TEXT,
  location TEXT,
  cost REAL,
  timeline_at TEXT,
  created_at TEXT DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(user_id) REFERENCES users(id)
)');

$db->exec('CREATE TABLE IF NOT EXISTS connections (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  note_a_id INTEGER,
  note_b_id INTEGER,
  FOREIGN KEY(note_a_id) REFERENCES notes(id),
  FOREIGN KEY(note_b_id) REFERENCES notes(id)
)');

echo "Database initialized\n";
