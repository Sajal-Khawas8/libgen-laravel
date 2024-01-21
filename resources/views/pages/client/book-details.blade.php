@extends("layouts.master-layout")

@section("main")
<div class="flex gap-0 ">
    <x-client.book-details-panel :$book />
    <div class="flex-1 space-y-6 px-6 py-8">
        <article class="space-y-4">
            <div class="flex justify-between">
                <h2 class="font-semibold text-3xl">Summary</h2>
                <form action="/formHandler" method="post">
                    <input type="hidden" name="id" value="">
                    <x-shared.form.submit-button>Add to Cart</x-shared.form.submit-button>
                </form>
            </div>
            <dl class="space-y-4 mx-8">
                <div class="grid grid-cols-3 text-lg">
                    <dt class="font-medium">Availability:</dt>
                    <dd
                        class="col-span-2 px-4 rounded-full font-medium w-fit {{ $book->quantity->available ? "bg-green-400 text-green-700" : "bg-red-400 text-red-700" }}">
                        {{ $book->quantity->available ? "In Stock" : "Out of Stock" }}</dd>
                </div>
                <div class="grid grid-cols-3 text-lg">
                    <dt class="font-medium">Rent:</dt>
                    <dd class="col-span-2 px-4">&#x20B9;{{ $book->rent }}/day</dd>
                </div>
                <div class="grid grid-cols-3 text-lg">
                    <dt class="font-medium">Fine charge:</dt>
                    <dd class="col-span-2 px-4">&#x20B9;{{ $book->fine }}/day</dd>
                </div>
            </dl>
        </article>
        <article class="space-y-8">
            <h2 class="font-semibold text-3xl">Payment</h2>
            <form action="/formHandler" method="post" class="space-y-10 max-w-lg mx-auto">
                <div class="grid grid-cols-3 gap-6">
                    <div class="col-span-2">
                        <x-shared.form.input name="cardNumber" placeholder="Card Number ex.1234123412341234" minLength="16" maxLength="16" />
                        <x-shared.form.error name="cardNumber" />
                    </div>
                    <div class="flex-1">
                        <x-shared.form.input name="returnDate" placeholder="Rent Period" />
                        <x-shared.form.error name="returnDate" />
                    </div>
                </div>
                <div>
                    <x-shared.form.input name="cardName" placeholder="Name on Card" />
                    <x-shared.form.error name="cardName" />
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <x-shared.form.input name="expiryDate" placeholder="Expiration Date (MM/YY)"/>
                        <x-shared.form.error name="expiryDate" />
                    </div>
                    <div>
                        <x-shared.form.input type="password" name="cvv" placeholder="CVV" minLength="3" maxLength="3"/>
                        <x-shared.form.error name="cvv" />
                    </div>
                </div>
                <x-shared.form.submit-button> Get this Book </x-shared.form.submit-button>
            </form>
        </article>
    </div>
</div>
@endsection