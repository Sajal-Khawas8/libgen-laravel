@extends("layouts.admin-panel-layout")

@section("admin")

<x-shared.form.update-user-form title="Update Your Info" action="/admin/settings/update" />

@endsection