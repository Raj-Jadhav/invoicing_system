<?php 
// Include the main.php file 
require 'main.php'; 
// Fetch all invoices from the database ordered by the invoice date 
$stmt = $pdo->query('SELECT i.*, (SELECT SUM(ii.item_quantity * ii.item_price) FROM invoice_items ii WHERE ii.invoice_id = i.id) AS payment_amount FROM invoices i ORDER BY i.created DESC'); 
$invoices = $stmt->fetchAll(); 
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
        <div class="invoices"> 
            <div class="header"> 
                <h1>Invoices</h1> 
                <a href="create.php" class="btn"><svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" /></svg>Create</a> 
            </div> 
            <div class="content"> 
                <table class="invoices-table"> 
                    <thead> 
                        <tr> 
                            <th>#</th> 
                            <th>Client</th> 
                            <th>Amount</th> 
                            <th>Status</th> 
                            <th>Date</th> 
                            <th>Actions</th> 
                        </tr> 
                    </thead> 
                    <tbody> 
                        <?php foreach ($invoices as $invoice): ?> 
                        <tr> 
                            <td class="alt"><?=$invoice['id']?></td> 
                            <td><?=$invoice['client_name']?></td> 
                            <td><strong><?=$currency_code?><?=number_format($invoice['payment_amount'], 2)?></strong></td> 
                            <td> 
                                <?php if ($invoice['payment_status'] == 'Paid'): ?> 
                                <span class="green">Paid</span> 
                                <?php else: ?> 
                                <span class="red">Unpaid</span> 
                                <?php endif; ?> 
                            </td> 
                            <td class="alt"><?=date('F d, Y', strtotime($invoice['created']))?></td> 
                            <td> 
                                <a href="invoice.php?id=<?=$invoice['id']?>" class="action">View</a> 
                            </td> 
                        </tr> 
                        <?php endforeach; ?> 
                    </tbody> 
                </table> 
            </div> 
        </div> 
    </body> 
</html>