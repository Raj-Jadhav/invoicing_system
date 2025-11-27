<?php 
// Include the main.php file 
require 'main.php'; 
// Check if get ID is set 
if (isset($_GET['id'])) { 
    // Get the invoice from the database 
    $stmt = $pdo->prepare('SELECT i.*, (SELECT SUM(ii.item_quantity * ii.item_price) FROM invoice_items ii WHERE ii.invoice_id = i.id) AS payment_amount FROM invoices i WHERE i.id = ?'); 
    $stmt->execute([ $_GET['id'] ]); 
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC); 
    // If the invoice does not exist, output error 
    if (!$invoice) { 
        exit('Invoice does not exist!'); 
    } 
    // Process payment with Stripe checkout session using cURL 
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/checkout/sessions'); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([ 
        'payment_method_types[]' => 'card', 
        'line_items[][price_data][currency]' => $stripe_currency, 
        'line_items[][price_data][product_data][name]' => 'Invoice #' . $invoice['id'], 
        'line_items[][price_data][unit_amount]' => $invoice['payment_amount'] * 100, 
        'line_items[][quantity]' => 1, 
        'metadata[client_reference_id]' => $invoice['id'], 
        'mode' => 'payment', 
        'success_url' => $stripe_success_url . '?id=' . $invoice['id'], 
        'cancel_url' => $stripe_cancel_url . '?id=' . $invoice['id'] 
    ])); 
    curl_setopt($ch, CURLOPT_USERPWD, $stripe_secret_key . ':'); 
    $result = curl_exec($ch); 
    curl_close($ch); 
    // Decode the result 
    $result = json_decode($result, true); 
    // If the session ID was returned 
    if (isset($result['id'])) { 
        // Redirect to the checkout page 
        header('Location: ' . $result['url']); 
        exit; 
    } else if (isset($result['error']) && isset($result['error']['message'])) { 
        // If the session ID was not returned, output error 
        exit('Error creating checkout session: ' . $result['error']['message']); 
    } else { 
        // If the session ID was not returned, output error 
        exit('Error creating checkout session!'); 
    } 
} 
?>