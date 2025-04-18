<?php
require 'config.php';

function getContacts() {
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM contacts ORDER BY name');
    return $stmt->fetchAll();
}

function addContact($data) {
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO contacts (name, phone, email, address, notes, birthday) VALUES (?, ?, ?, ?, ?, ?)');
    return $stmt->execute([$data['name'], $data['phone'], $data['email'], $data['address'], $data['notes'], $data['birthday']]);
}

function getContactById($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function updateContact($id, $data) {
    global $pdo;
    $stmt = $pdo->prepare('UPDATE contacts SET name = ?, phone = ?, email = ?, address = ?, notes = ?, birthday = ? WHERE id = ?');
    return $stmt->execute([$data['name'], $data['phone'], $data['email'], $data['address'], $data['notes'], $data['birthday'], $id]);
}

function deleteContact($id) {
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM contacts WHERE id = ?');
    return $stmt->execute([$id]);
}

function searchContacts($search) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE name LIKE ? OR phone LIKE ? OR email LIKE ? ORDER BY name');
    $searchTerm = "%$search%";
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
    return $stmt->fetchAll();
}
?>