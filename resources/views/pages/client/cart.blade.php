@extends("layouts.master-layout")

@section("main")
@empty($cartItems)
<section class="min-h-[calc(100vh-4rem-3.5rem)] flex items-center justify-center">
    <h1 class="font-bold text-5xl text-gray-500">Your cart is empty...</h1>
</section>
@else
<div class="flex gap-0">
    <section class="flex-1 space-y-8 px-6 py-4 bg-slate-100">
        <h1 class="font-semibold text-3xl">Books in Cart</h1>
        <ul class="mx-auto space-y-6">
            @foreach ($cartItems as $item)
                <li class="px-3 py-3 rounded-md {{ $item->book->quantity->available ? 'bg-white' : 'bg-slate-300/80' }}">
                    <x-client.cart-item :book="$item->book" />
                </li>
            @endforeach
        </ul>
    </section>
    <section class="flex-1 space-y-8 px-6 py-4">
        <h2 class="font-semibold text-3xl">Payment</h2>
        <form action="/formHandler" method="post" class="space-y-10 max-w-lg mx-auto">
            <div>
                <x-shared.form.input name="cardNumber" placeholder="Card Number" />
                <x-shared.form.error name="cardNumber" />
            </div>
            <div>
                <x-shared.form.input name="cardName" placeholder="Name on card" />
                <x-shared.form.error name="cardName" />
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <x-shared.form.input name="expiryDate" placeholder="Expiration Date (MM/YY)" />
                    <x-shared.form.error name="expiryDate" />
                </div>
                <div>
                    <x-shared.form.input type="password" name="cvv" placeholder="CVV" />
                    <x-shared.form.error name="cvv" />
                </div>
            </div>
            @foreach ($cartItems as $item)
                @if ($item->book->quantity->available)
                    <div class="flex items-center gap-7">
                        <label for="returnDate-{{ $item->book->title }}" class="font-medium cursor-pointer w-1/2">Enter
                            Rent Period of {{ $item->book->title }}:</label>
                        <div class="flex-1">
                            <x-shared.form.input type="date"
                                name="returnDate-{{ str_replace(' ', '_', $item->book->title) }}" />
                            <x-shared.form.error name="returnDate-{{ str_replace(' ', '_', $item->book->title) }}" />
                        </div>
                    </div>
                @endif
            @endforeach
            <x-shared.form.submit-button>Get All Books</x-shared.form.submit-button>
        </form>
    </section>
</div>
@endempty
@endsection