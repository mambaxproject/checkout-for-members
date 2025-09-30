@extends('layouts.admin')
@section('content')
@can('gender_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.genders.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.gender.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.gender.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Gender">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.gender.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.gender.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.gender.fields.status') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($genders as $key => $gender)
                        <tr data-entry-id="{{ $gender->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $gender->id ?? '' }}
                            </td>
                            <td>
                                {{ $gender->name ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Gender::STATUS_RADIO[$gender->status] ?? '' }}
                            </td>
                            <td>
                                @can('gender_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.genders.show', $gender->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('gender_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.genders.edit', $gender->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('gender_delete')
                                    <form action="{{ route('admin.genders.destroy', $gender->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('gender_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.genders.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Gender:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection