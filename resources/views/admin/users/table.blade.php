@php
$columns = [
    ['field' => 'email', 'label' => 'Email', 'width' => '150px'],
    ['field' => 'name', 'label' => 'Name', 'width' => '150px'],
    ['field' => 'company', 'label' => 'Company', 'width' => '150px'],
    ['field' => 'attempts_used', 'label' => 'Attempts Used', 'width' => '50px'],
    ['field' => 'last_score', 'label' => 'Last Score', 'width' => '50px'],
    ['field' => 'data_status', 'label' => 'Status', 'width' => '100px'],
];

$actions = [
    'show' => 'admin.users.show',
    'edit' => 'admin.users.edit',
    'delete' => 'admin.users.destroy',
];

$badgeFields = ['data_status'];
@endphp

<x-table :columns="$columns" :items="$users" :actions="$actions" :badgeFields="$badgeFields" />

