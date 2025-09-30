@extends('layouts.admin')
@section('content')
@can('affiliate_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.affiliates.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.affiliate.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.affiliate.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Affiliate">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.affiliate.fields.name') }}</th>
                        <th>{{ trans('cruds.affiliate.fields.email') }}</th>
                        <th>{{ trans('cruds.affiliate.fields.document_number') }}</th>
                        <th>{{ trans('cruds.affiliate.fields.percentage') }}</th>
                        <th>{{ trans('cruds.affiliate.fields.start_at') }}</th>
                        <th>{{ trans('cruds.affiliate.fields.end_at') }}</th>
                        <th>{{ trans('cruds.affiliate.fields.status') }}</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input class="search" type="text" placeholder="{{ trans('global.search') }}"></td>
                        <td><input class="search" type="text" placeholder="{{ trans('global.search') }}"></td>
                        <td><input class="search" type="text" placeholder="{{ trans('global.search') }}"></td>
                        <td><input class="search" type="text" placeholder="{{ trans('global.search') }}"></td>
                        <td><input class="search" type="text" placeholder="{{ trans('global.search') }}"></td>
                        <td><input class="search" type="text" placeholder="{{ trans('global.search') }}"></td>
                        <td><input class="search" type="text" placeholder="{{ trans('global.search') }}"></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($affiliates as $key => $affiliate)
                        <tr data-entry-id="{{ $affiliate->id }}">
                            <td></td>
                            <td>{{ $affiliate->user->name ?? '' }}</td>
                            <td>{{ $affiliate->user->email ?? '' }}</td>
                            <td>{{ $affiliate->user->document_number ?? '' }}</td>
                            <td>{{ !empty($affiliate->percentage) ? Number::percentage($affiliate->percentage) : '' }}</td>
                            <td>{{ $affiliate->start_at ? $affiliate->start_at->format('d/m/y H:i') : '' }}</td>
                            <td>{{ $affiliate->end_at ? $affiliate->end_at->format('d/m/y H:i') : '' }}</td>
                            <td>{{ App\Models\Affiliate::STATUS_RADIO[$affiliate->status] ?? '' }}</td>
                            <td>
                                @can('affiliate_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.affiliates.show', $affiliate->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('affiliate_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.affiliates.edit', $affiliate->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('affiliate_delete')
                                    <form action="{{ route('admin.affiliates.destroy', $affiliate->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('affiliate_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.affiliates.massDestroy') }}",
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
    pageLength: 100,
  });
  let table = $('.datatable-Affiliate:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
})

</script>
@endsection
