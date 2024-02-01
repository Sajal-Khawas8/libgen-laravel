@extends("layouts.master-layout")

@section("main")
<div class="flex gap-0 ">
    <x-client.book-details-panel :$book />

    <div class="flex-1 space-y-6 px-6 py-8">
        <article class="space-y-4">
            <div class="flex justify-between">
                <h2 class="font-semibold text-3xl">Summary</h2>
            </div>
            <div class="space-y-2 divide-y px-8">
                <dl class="flex justify-between py-4">
                    <div class="flex gap-2 text-lg">
                        <dt class="font-medium">Issue Date:</dt>
                        <dd>{{ $rentData->issue_date }}</dd>
                    </div>
                    <div class="flex gap-2 text-lg">
                        <dt class="font-medium">Due Date:</dt>
                        <dd>{{ $rentData->due_date }}</dd>
                    </div>
                </dl>
                <dl class="space-y-4 py-4">
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Rent Period</dt>
                        <dd>{{ $rentData->duration }} days</dd>
                    </div>
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Rent (&#x20B9;{{ $book->rent }}/day)</dt>
                        <dd>&#x20B9;{{ $rentData->rent }}</dd>
                    </div>
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Overdue Days</dt>
                        <dd>{{ $rentData->overdueDays }} days</dd>
                    </div>
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Fine (&#x20B9;{{ $book->fine }}/day)</dt>
                        <dd>&#x20B9;{{ $rentData->fine }}</dd>
                    </div>
                </dl>
                <dl class="space-y-4 py-4">
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Total Rent</dt>
                        <dd>&#x20B9;{{ $rentData->rent + $rentData->fine }}</dd>
                    </div>
                    <div class="flex justify-between text-lg">
                        <dt class="font-medium">Amount Paid</dt>
                        <dd>&#x20B9;{{ $rentData->rent }}</dd>
                    </div>
                </dl>
                <dl class="flex justify-between text-xl font-medium py-4">
                    <dt>Total Payable</dt>
                    <dd>&#x20B9;{{ $rentData->fine }}</dd>
                </dl>
            </div>
        </article>
        <article class="space-y-8">
            <h2 class="font-semibold text-3xl">Payment</h2>
            @if ($rentData->fine)
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
                        <x-shared.form.input type="password" name="cvv" placeholder="CVV" minlength=3 maxlength=3 />
                        <x-shared.form.error name="cvv" />
                    </div>
                </div>
                <x-shared.form.input type="hidden" name="amount" value=20 />
                <x-shared.form.submit-button>Return</x-shared.form.submit-button>
            </form>
            @else
            <form action="/formHandler" method="post" class="space-y-10 max-w-lg mx-auto">
                <p class="text-lg text-center font-medium text-gray-600">*No Payment Required</p>
                <x-shared.form.submit-button>Return</x-shared.form.submit-button>
            </form>
            @endif
        </article>
    </div>
</div>
@endsection