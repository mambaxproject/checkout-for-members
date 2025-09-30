@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.gender.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.genders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.gender.fields.id') }}
                        </th>
                        <td>
                            {{ $gender->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.gender.fields.name') }}
                        </th>
                        <td>
                            {{ $gender->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.gender.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Gender::STATUS_RADIO[$gender->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.genders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection