<div class="upload-full-zone">

    @if ($isMultiple ?? false)
        <input
            class="fileInput peer hidden"
            id="{{ $id ?? '' }}"
            type="file"
            name="{{ $name ?? '' }}"
            accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            placeholder="Envie um arquivo"
            @required($required ?? false)
            multiple
        />
    @else
        <input
            class="fileInput peer hidden"
            id="{{ $id ?? '' }}"
            type="file"
            name="{{ $name ?? '' }}"
            accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            placeholder="Envie um arquivo"
            @required($required ?? false)
        />
    @endif

    <div class="rounded-lg border border-neutral-200 hover:border-solid hover:border-primary peer-[&:user-invalid]:border-danger-500 peer-[&:user-valid]:border-success-500">

        <label
            class="w-full cursor-pointer pb-6 pt-5 text-center"
            for="{{ $id ?? '' }}"
        >
            @include('components.icon', [
                'icon' => 'upload_file', 
                'custom' => 'text-2xl text-primary',
            ])
            <p class="px-8 text-sm">
                Arraste aqui um arquivo <strong>.csv</strong> ou <strong>.xls/.xlsx</strong><br>
                <span class="text-primary">ou clique para procurar</span>
            </p>
        </label>

        <div
            class="fileListContent"
            id="{{ ($id ?? '') . 'fileListContent' }}"
        >
            <ul class="fileList w-full space-y-1 px-4 pb-4"></ul>
        </div>

    </div>

</div>
