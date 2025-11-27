<?php 
// Database connection variables 
$db_host = 'localhost'; 
$db_user = 'root'; 
$db_pass = ''; 
$db_name = 'phpinvoice_tutorial'; 
$db_charset = 'utf8'; 
 
// Other variables 
$currency_code = '&dollar;'; 
 
// Company details 
$company_name = 'Your Company Name'; 
$company_address = "123 Example Street\nExample City\nEX4 MPL\nUnited States"; 
 
// Stripe variables 
$stripe_secret_key = ''; 
$stripe_currency = 'USD'; 
$stripe_success_url = 'https://example.com/invoice.php'; 
$stripe_cancel_url = 'https://example.com/invoice.php'; 
$stripe_webhook_secret = ''; 

// Database connection using PDO 
try { 
    $pdo = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=' . $db_charset, $db_user, $db_pass); 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch (PDOException $exception) { 
    // If there is an error with the connection, stop the script and display the error. 
    exit('Failed to connect to database: ' . $exception->getMessage()); 
} 
?>