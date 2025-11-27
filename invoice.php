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
    // Get invoice items 
    $stmt = $pdo->prepare('SELECT * FROM invoice_items WHERE invoice_id = ?'); 
    $stmt->execute([ $_GET['id'] ]); 
    $invoice_items = $stmt->fetchAll(PDO::FETCH_ASSOC); 
} else { 
    // If the ID was not set, output error 
    exit('No ID specified!'); 
} 
?> 
<!DOCTYPE html> 
<html> 
  <head> 
    <title>Invoices</title> 
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width,minimum-scale=1"> 
        <link href="style.css" rel="stylesheet" type="text/css"> 
  </head> 
  <body> 
        <div class="invoice"> 
            <div class="header"> 
                <div> 
                    <h1>Invoice</h1> 
                    <p class="due-date">Due <?=date('F d, Y', strtotime($invoice['created']))?></p> 
                </div> 
                <?php if ($invoice['payment_status'] == 'Unpaid'): ?> 
                <a href="pay.php?id=<?=$invoice['id']?>" class="btn"><svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20,8H4V6H20M20,18H4V12H20M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z" /></svg>Pay Now</a> 
                <?php else: ?> 
                <p class="paid"><?=$invoice['payment_status']?></p> 
                <?php endif; ?> 
            </div> 
            <div class="content"> 
                <div class="from-details"> 
                    <h3>From</h3> 
                    <p><?=nl2br($company_name)?></p> 
                    <p><?=nl2br($company_address)?></p> 
                </div> 
                <div class="to-details"> 
                    <h3>To</h3> 
                    <p><?=$invoice['client_name']?></p> 
                    <p><?=nl2br($invoice['client_address'])?></p> 
                </div> 
                <div class="invoice-items"> 
                    <table class="invoice-items-table"> 
                        <thead> 
                            <tr> 
                                <th>Name</th> 
                                <th>Quantity</th> 
                                <th>Price</th> 
                                <th class="pull-right">Total</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php foreach ($invoice_items as $item): ?> 
                            <tr class="item"> 
                                <td><?=$item['item_name']?></td> 
                                <td><?=$item['item_quantity']?></td> 
                                <td><?=$currency_code?><?=number_format($item['item_price'], 2)?></td> 
                                <td class="pull-right"><?=$currency_code?><?=number_format($item['item_quantity'] * $item['item_price'], 2)?></td> 
                            </tr> 
                            <?php endforeach; ?> 
                            <tr class="total"> 
                                <td colspan="3" class="pull-right"><strong>Total</strong></td> 
                                <td class="pull-right"><?=$currency_code?><?=number_format($invoice['payment_amount'], 2)?></td> 
                            </tr> 
                        </tbody> 
                    </table> 
                </div> 
            </div> 
        </div> 
    </body> 
</html>