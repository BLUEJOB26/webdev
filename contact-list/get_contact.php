<?php
require 'functions.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $contact = getContactById($_GET['id']);
    echo json_encode($contact);
}
?>