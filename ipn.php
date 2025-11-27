<?php 
// Include the main.php file 
require 'main.php'; 
// Validate header 
if (!isset($_SERVER['HTTP_STRIPE_SIGNATURE'])) { 
    http_response_code(400); 
    exit; 
} 
// Declare variables 
$payload = @file_get_contents('php://input'); 
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE']; 
$event = null; 
// Verify the signature 
function verify_stripe_signature($payload, $sig_header, $endpoint_secret) { 
    $timestamp = explode(',', $sig_header)[0]; 
    $signature = explode(',', $sig_header)[1]; 
    $signed_payload = $timestamp . '.' . $payload; 
    $expected_signature = hash_hmac('sha256', $signed_payload, $endpoint_secret); 
    return hash_equals($expected_signature, $signature); 
} 
// If the signature is not valid, return a 400 error 
if (!verify_stripe_signature($payload, $sig_header, $stripe_webhook_secret)) { 
    http_response_code(400); 
    exit; 
} 
// Decode the payload 
$event = json_decode($payload, true); 
// Handle the checkout.session.completed event 
if ($event['type'] == 'checkout.session.completed') { 
    // Update the invoice 
    $session = $event['data']['object']; 
    // Retrieve the invoice ID from the session metadata (if you set it) 
    $invoice_id = $session['metadata']['client_reference_id']; 
    // Update the invoice status to 'Paid' in your database 
    $stmt = $pdo->prepare('UPDATE invoices SET payment_status = ? WHERE id = ?'); 
    $stmt->execute([ 'Paid', $invoice_id ]); 
} 
http_response_code(200); 
?>