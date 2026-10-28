
<?php
require_once __DIR__ . '/models/Booking.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header('Location: index.php');
    exit;
}

try {
    $model = new Booking();
    $model->delete($id);
    header('Location: index.php?message=deleted');
    exit;
} catch (PDOException $e) {
    header('Location: index.php?message=delete_error');
    exit;
}

