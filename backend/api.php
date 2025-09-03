<?php
header('Content-Type: application/json');
$db = new PDO('sqlite:' . __DIR__ . '/data/app.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($action) {
  case 'register':
    if ($method !== 'POST') { http_response_code(405); exit; }
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
    $stmt->execute([$data['username'], password_hash($data['password'], PASSWORD_DEFAULT)]);
    echo json_encode(['status' => 'ok']);
    break;
  case 'login':
    if ($method !== 'POST') { http_response_code(405); exit; }
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$data['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($data['password'], $user['password'])) {
      echo json_encode(['status' => 'ok', 'user_id' => $user['id']]);
    } else {
      http_response_code(401);
      echo json_encode(['error' => 'Invalid credentials']);
    }
    break;
  case 'notes':
    if ($method === 'GET') {
      $user_id = $_GET['user_id'] ?? null;
      $stmt = $db->prepare('SELECT * FROM notes WHERE user_id = ?');
      $stmt->execute([$user_id]);
      echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } elseif ($method === 'POST') {
      $data = json_decode(file_get_contents('php://input'), true);
      $stmt = $db->prepare('INSERT INTO notes (user_id, title, content, x, y, color, location, cost, timeline_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
      $stmt->execute([
        $data['user_id'],
        $data['title'],
        $data['content'],
        $data['x'],
        $data['y'],
        $data['color'],
        $data['location'],
        $data['cost'],
        $data['timeline_at']
      ]);
      echo json_encode(['status' => 'ok', 'id' => $db->lastInsertId()]);
    } elseif ($method === 'PUT') {
      parse_str($_SERVER['QUERY_STRING'], $query);
      $id = $query['id'] ?? null;
      $data = json_decode(file_get_contents('php://input'), true);
      $stmt = $db->prepare('UPDATE notes SET title = ?, content = ?, x = ?, y = ?, color = ?, location = ?, cost = ?, timeline_at = ? WHERE id = ?');
      $stmt->execute([
        $data['title'],
        $data['content'],
        $data['x'],
        $data['y'],
        $data['color'],
        $data['location'],
        $data['cost'],
        $data['timeline_at'],
        $id
      ]);
      echo json_encode(['status' => 'ok']);
    } elseif ($method === 'DELETE') {
      $id = $_GET['id'] ?? null;
      $stmt = $db->prepare('DELETE FROM notes WHERE id = ?');
      $stmt->execute([$id]);
      echo json_encode(['status' => 'ok']);
    } else {
      http_response_code(405);
    }
    break;
  case 'costs':
    if ($method !== 'GET') { http_response_code(405); exit; }
    $user_id = $_GET['user_id'] ?? null;
    $stmt = $db->prepare('SELECT SUM(cost) as total_cost FROM notes WHERE user_id = ?');
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['total_cost' => $row['total_cost'] ?? 0]);
    break;
  default:
    echo json_encode(['error' => 'Unknown action']);
}
