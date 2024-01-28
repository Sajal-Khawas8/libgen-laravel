@extends("layouts.master-layout")

@section("main")
<section class="py-8 space-y-10">
    <h1 class="text-center text-4xl font-semibold">Account Settings</h1>
    <x-shared.settings update="/update" delete="/delete" />
</section>
@endsection