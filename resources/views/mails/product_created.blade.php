@component('mail::message')
# New Product Created
 
A new product has been created:
 
- **Name:** {{ $product->name }}
- **Price:** ${{ $product->price }}
- **SKU:** {{ $product->sku }}
- **Description:** {{ $product->description }}
 
**Images:**
@foreach(json_decode($product->images) as $image)
![Image]({{ asset(Storage::url($image)) }})
@endforeach
 
Thanks,<br>
{{ config('app.name') }}
@endcomponent