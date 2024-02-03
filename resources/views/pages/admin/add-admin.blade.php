@extends("layouts.admin-panel-layout")

@section("admin")
<x-shared.form.add-user-form class="overflow-y-auto" title="New Admin Registration" action="/admin/team/addAdmin" />
@endsection