@extends('layouts.master-layout')

@section('main')

@if (!count($books))

<section class="flex items-center justify-around gap-8 min-h-[calc(100vh-4rem-3.5rem)]">
    <h1 class="font-bold text-5xl text-gray-500">Coming Soon...</h1>
</section>

@else
<section
    class="bg-home h-[calc(100vh-4rem)] bg-no-repeat bg-cover flex flex-col items-center gap-28">
    <header class="text-center text-2xl font-semibold text-white space-y-8 mt-40">
        <h1 class="text-4xl">Welcome to <a href="/libgen" class="text-red-600 text-5xl">LibGen</a>
        </h1>
        <p>Unlocking Worlds, One Page at a Time: Your Gateway to Knowledge and Imagination.</p>
    </header>
    <form action="#" method="post" class="text-gray-800 divide-x divide-gray-500 relative w-[800px]">
        <div class="absolute left-0 inset-y-0 px-2 divide-x divide-gray-500 rounded-l-lg">
            <select name="category" id="category" class="px-2 py-2 outline-none border-r border-gray-500 text-lg"
                aria-label="Select category">
                <option value=0>All Categories</option>
                @foreach ($books as $category => $categorizedBooks)
                <option value="{{ $categorizedBooks[0]->category_id }}">{{ ucwords($category) }}</option>
                @endforeach
            </select>
        </div>
        <input type="text" name="bookName" id="bookName" placeholder="Search Books by Title or Author" value=""
            class="pl-44 pr-4 py-2 outline-none w-full rounded-lg text-lg">
        <button name="searchBooks"
            class="absolute inset-y-0 right-0 px-3 rounded-r-lg text-lg bg-indigo-600 hover:bg-indigo-800 text-white font-medium"
            aria-label="Search">
            Search Book
        </button>
    </form>
</section>
<div id="search">
    @foreach ($books as $categoryName => $books)
    <Section>
        <h2 class="font-medium text-2xl pl-16">{{ $categoryName }}</h2>
        <x-client.book-list :$books/>
    </Section>
    @endforeach
</div>
@endif

@endsection