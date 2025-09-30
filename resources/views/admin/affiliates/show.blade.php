@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.affiliate.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.affiliates.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.affiliate.fields.id') }}
                        </th>
                        <td>
                            {{ $affiliate->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.affiliate.fields.name') }}
                        </th>
                        <td>
                            {{ $affiliate->user->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.affiliate.fields.email') }}
                        </th>
                        <td>
                            {{ $affiliate->user->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.affiliate.fields.document_number') }}
                        </th>
                        <td>
                            {{ $affiliate->user->document_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.affiliate.fields.percentage') }}
                        </th>
                        <td>
                            {{ Number::percentage($affiliate->percentage) }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.affiliate.fields.description') }}
                        </th>
                        <td>
                            {{ $affiliate->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.affiliate.fields.start_at') }}
                        </th>
                        <td>
                            {{ $affiliate->start_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.affiliate.fields.end_at') }}
                        </th>
                        <td>
                            {{ $affiliate->end_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.affiliate.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Affiliate::STATUS_RADIO[$affiliate->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.affiliates.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
