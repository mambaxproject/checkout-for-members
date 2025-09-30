@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.user.title') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.users.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.id') }}
                            </th>
                            <td>
                                {{ $user->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.name') }}
                            </th>
                            <td>
                                {{ $user->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.email') }}
                            </th>
                            <td>
                                {{ $user->email }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.phone_number') }}
                            </th>
                            <td>
                                {{ $user->phone_number }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.typer_person') }}
                            </th>
                            <td>
                                {{ $user->typePerson }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.document_number') }}
                            </th>
                            <td>
                                {{ $user->document_number }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.birthday') }}
                            </th>
                            <td>
                                {{ $user->birthday }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.email_verified_at') }}
                            </th>
                            <td>
                                {{ $user->email_verified_at }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.verified') }}
                            </th>
                            <td>
                                <input type="checkbox" disabled="disabled" {{ $user->verified ? 'checked' : '' }}>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.two_factor') }}
                            </th>
                            <td>
                                <input type="checkbox" disabled="disabled" {{ $user->two_factor ? 'checked' : '' }}>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.roles') }}
                            </th>
                            <td>
                                @foreach($user->roles as $key => $roles)
                                    <span class="label label-info">{{ $roles->title }}</span>
                                @endforeach
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.users.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            {{ trans('global.relatedData') }}
        </div>
        <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
            <li class="nav-item">
                <a class="nav-link" href="#company" role="tab" data-toggle="tab">
                    Pessoa Jurídica
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#user_orders" role="tab" data-toggle="tab">
                    {{ trans('cruds.order.title') }}
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#media" role="tab" data-toggle="tab">
                    Arquivos
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane" role="tabpanel" id="company">
                <div class="p-3">
                    <p> <b>Razão social:</b> {{ $user->attributes['company']['corporate_name'] ?? "" }} </p>
                    <p> <b>Nome fantasia:</b> {{ $user->attributes['company']['fantasy_name'] ?? "" }} </p>
                    <p> <b>CNPJ:</b> {{ $user->attributes['company']['document_number'] ?? "" }} </p>
                    <p> <b>Número de inscrição municipal:</b> {{ $user->attributes['company']['municipal_registration'] ?? "" }} </p>
                </div>
            </div>

            <div class="tab-pane" role="tabpanel" id="user_orders">
                @includeIf('admin.users.relationships.userOrders', ['orders' => $user->orders])
            </div>

            <div class="tab-pane" role="tabpanel" id="media">
                @includeIf('admin.users.relationships.media', ['media' => $user->media])
            </div>
        </div>
    </div>
@endsection
