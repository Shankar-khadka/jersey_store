<?php
// Database + app configuration. Defaults match a fresh XAMPP install.
const DB_HOST = 'localhost';
const DB_NAME = 'jersey_store_crud';
const DB_USER = 'root';
const DB_PASS = '';

// Folder name under htdocs (no trailing slash). Use '' if served from web root.
const BASE_URL = '/jersey-store-crud';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    die('Database connection failed. Check includes/db.php and make sure MySQL is running.');
}
