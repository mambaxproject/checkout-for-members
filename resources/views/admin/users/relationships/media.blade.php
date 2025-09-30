<div class="m-3">
    <div class="card">
        <div class="card-header">
            Listagem de arquivos do usuário
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Tamanho</th>
                            <th>Arquivo</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($media as $key => $item)
                            <tr data-entry-id="{{ $item->id }}">
                                <td>
                                    {{ $item->name }}
                                </td>
                                <td>
                                    {{ $item->collection_name == 'frontDocument' ? 'Documento Frente' : 'Documento Verso' }}
                                </td>
                                <td>
                                    {{ \Illuminate\Support\Number::fileSize($item->size) }}
                                </td>
                                <td>
                                    <a href="{{ $item->getUrl() }}" title="{{ $item->file_name }}" target="_blank">
                                        @if($item->mime_type == 'image/jpeg' || $item->mime_type == 'image/png')
                                            <img src="{{ $item->getUrl() }}" width="50px" height="50px" loading="lazy">
                                        @else
                                            [VER ARQUIVO]
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    @can('order_show')
                                        <a class="btn btn-xs btn-primary" href="">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
