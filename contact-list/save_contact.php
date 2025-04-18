<?php
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'email' => $_POST['email'] ?? '',
        'address' => $_POST['address'] ?? '',
        'notes' => $_POST['notes'] ?? '',
        'birthday' => $_POST['birthday'] ?? null
    ];
    
    if (!empty($_POST['id'])) {
        // Update existing contact
        updateContact($_POST['id'], $data);
    } else {
        // Add new contact
        addContact($data);
    }
}

header('Location: index.php');
exit;
?>