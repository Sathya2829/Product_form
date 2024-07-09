<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
 
class ProductCreated extends Mailable
{
    use Queueable, SerializesModels;
 
    public $product;
 
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
 
    public function build()
    {
        return $this->markdown('mails.product_created')
                    ->subject('New Product Created');
    }
}