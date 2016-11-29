<?php
require 'config.php';
$items = false;
if(file_exists($fileName))
{
    $items = json_decode( file_get_contents( $fileName ) );
}

$total = 0; // initialize
setlocale(LC_MONETARY, 'en_US.UTF-8'); // for formatting dollar values
?>
    <div class="row">
        <div class="col-md-2">Product Name</div>
        <div class="col-md-2">Quantity in stock</div>
        <div class="col-md-2">Price per item</div>
        <div class="col-md-2">Datetime submitted</div>
        <div class="col-md-2">Total value</div>
    </div>

<?php if($items): foreach ( $items as $item ): ?>

    <div class="row">
        <div class="col-md-2"><?=$item->productName?></div>
        <div class="col-md-2"><?=$item->qtyInStock?></div>
        <div class="col-md-2"><?=$item->pricePerItem?></div>
        <div class="col-md-2"><?=$item->submitted?></div>
        <div class="col-md-2"><?php $subTotal = $item->qtyInStock * $item->pricePerItem; echo money_format('%.2n', $subTotal)?></div>
    </div>

<?php
    $total = $subTotal + $total;
    endforeach; endif;
?>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-2"></div>
        <div class="col-md-2"></div>
        <div class="col-md-2"></div>
        <div class="col-md-2"><?=money_format('%.2n', $total)?></div>
    </div>
