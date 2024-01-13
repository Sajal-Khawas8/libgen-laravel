<ul class="grid grid-cols-5 items-center gap-x-20 gap-y-12 flex-wrap px-16 py-8">
    @foreach ($books as $book)
    <li class="border rounded-lg divide-y hover:shadow-lg">
        <x-client.book-card :$book />
    </li>
    @endforeach
</ul>