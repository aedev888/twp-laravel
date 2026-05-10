<x-mail::message>
# A New Asset Just Dropped! 🚀

Hello from Lumina Marketplace! We are excited to announce that a new premium asset has just been added to our collection.

<x-mail::panel>
## {{ $productName }}
**Price:** {{ $productPrice }}  
**Category:** {{ $product->taxonomies->first()->name ?? 'General' }}
</x-mail::panel>

![Product Preview]({{ $productPreview }})

<x-mail::button :url="$productUrl">
View Asset Details
</x-mail::button>

Don't forget, as a member, you get instant access to all our updates.

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
