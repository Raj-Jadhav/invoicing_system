<?php 
// Include the main.php file 
require 'main.php'; 
// If the form is submitted 
if (isset($_POST['client_name'])) { 
    // Insert a new invoice into the database 
    $stmt = $pdo->prepare('INSERT INTO invoices (client_name, client_address, payment_status, notes, created) VALUES (?, ?, ?, ?, ?)'); 
    $stmt->execute([ $_POST['client_name'], $_POST['client_address'], 'Unpaid', $_POST['notes'], date('Y-m-d H:i:s') ]); 
    // Get the ID of the new invoice 
    $invoice_id = $pdo->lastInsertId(); 
    // Insert each item into the database 
    if (isset($_POST['item_name']) && is_array($_POST['item_name']) && count($_POST['item_name']) > 0) { 
        $stmt = $pdo->prepare('INSERT INTO invoice_items (invoice_id, item_name, item_quantity, item_price) VALUES (?, ?, ?, ?)'); 
        for ($i = 0; $i < count($_POST['item_name']); $i++) { 
            $stmt->execute([ $invoice_id, $_POST['item_name'][$i], $_POST['item_quantity'][$i], $_POST['item_price'][$i] ]); 
        } 
    } 
    // Redirect to the invoices page 
    header('Location: index.php'); 
    exit; 
} 
?> 
<!DOCTYPE html> 
<html> 
  <head> 
    <title>Create Invoice</title> 
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width,minimum-scale=1"> 
        <link href="style.css" rel="stylesheet" type="text/css"> 
  </head> 
  <body> 
        <div class="create-invoice"> 
            <div class="header"> 
                <h1>Create Invoice</h1> 
            </div> 
            <form action="" method="post" class="content"> 
 
                <label for="client_name">Client Name</label> 
                <input type="text" name="client_name" id="client_name" placeholder="Joe Bloggs" required> 
 
                <label for="client_address">Client Address</label> 
                <textarea name="client_address" id="client_address" placeholder="Enter the client's address..."></textarea> 
 
                <div class="invoice-items"> 
                    <table class="invoice-items-table"> 
                        <thead> 
                            <tr> 
                                <th>Name</th> 
                                <th>Quantity</th> 
                                <th>Price</th> 
                                <th></th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <tr class="item"> 
                                <td><input type="text" name="item_name[]" placeholder="Item 1" required></td> 
                                <td><input type="number" name="item_quantity[]" placeholder="1" required></td> 
                                <td><input type="number" name="item_price[]" placeholder="0.00" required></td> 
                                <td><a href="#" class="remove-item"><svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19 L19,17.59L13.41,12L19,6.41Z" /></svg></a></td> 
                            </tr> 
                        </tbody> 
                    </table> 
                    <a href="#" class="add-item"><svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" /></svg>Add Item</a> 
                </div> 
 
                <label for="notes">Notes</label> 
                <textarea name="notes" id="notes" placeholder="Enter any notes for the invoice..."></textarea> 
 
                <button type="submit" class="btn">Create Invoice</button> 
 
            </form> 
        </div> 
        <script> 
        document.querySelector('.add-item').onclick = event => { 
            event.preventDefault(); 
            const table = document.querySelector('.invoice-items-table'); 
            const row = table.insertRow(table.rows.length); 
            row.className = 'item'; 
            row.innerHTML = ` 
                <td><input type="text" name="item_name[]" placeholder="Item ${table.rows.length-1}" required></td> 
                <td><input type="number" name="item_quantity[]" placeholder="1" required></td> 
                <td><input type="number" name="item_price[]" placeholder="0.00" required></td> 
                <td><a href="#" class="remove-item"><svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19 L19,17.59L13.41,12L19,6.41Z" /></svg></a></td> 
            `; 
            row.querySelector('.remove-item').onclick = event => { 
                event.preventDefault(); 
                table.deleteRow(row.rowIndex); 
            }; 
        }; 
        document.querySelectorAll('.remove-item').forEach(element => { 
            element.onclick = event => { 
                event.preventDefault(); 
                element.closest('tr').remove(); 
            }; 
        }); 
        </script> 
    </body> 
</html>