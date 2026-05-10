<?php

$product = \App\Models\Product::first();

if (!$product) {
    echo "No products found.\n";
    exit;
}

$service = app(\App\Services\TelegramService::class);
$result = $service->sendProductToChannel($product);

if ($result) {
    echo "Message sent successfully!\n";
} else {
    echo "Failed to send message.\n";
}
