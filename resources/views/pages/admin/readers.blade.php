@extends("layouts.admin-panel-layout")

@section("admin")
<header class="py-2.5 px-6">
    <h1 class="my-2.5 text-2xl font-medium text-center xl:text-left">LibGen Readers</h1>
    <div class="flex items-center gap-2">
        <form action="/formHandler" method="post" class="text-gray-800 divide-gray-500 relative w-[500px]">
            <x-shared.form.search name="search" placeholder="Search readers by Name or Email" />
        </form>
        <x-shared.form.error name="search" />
    </div>
</header>

@if ($users->isEmpty())
<section class="flex items-center justify-center h-full">
    <h2 class="text-5xl font-medium text-gray-500">There are currently no readers in LibGen...</h2>
</section>
@else
<div class="flex-1 overflow-y-auto px-6 ">
    <ul class="grid grid-cols-3 gap-8">
        @foreach ($users as $user)
        <x-admin.user-panel :$user />
        @endforeach
    </ul>
    <div class="py-6">
        {{ $users->links() }}
    </div>
</div>
@endif
@endsection