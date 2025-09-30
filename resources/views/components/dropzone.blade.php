<div class="upload-full-zone">

    @if ($isMultiple ?? false)
        <input
            class="fileInput peer hidden"
            id="{{ $id ?? '' }}"
            type="file"
            name="{{ $name ?? '' }}"
            accept="{{ $accept ?? 'image/*' }}"
            placeholder="Insira uma imagem"
            @required($required ?? false)
            multiple
        />
    @else
        <input
            class="fileInput peer hidden"
            id="{{ $id ?? '' }}"
            type="file"
            name="{{ $name ?? '' }}"
            accept="{{ $accept ?? 'image/*' }}"
            placeholder="Insira uma imagem"
            @required($required ?? false)
        />
    @endif

    <div class="rounded-lg border border-neutral-200 hover:border-solid hover:border-primary peer-[&:user-invalid]:border-danger-500 peer-[&:user-valid]:border-success-500">

        <label
            class="w-full cursor-pointer pb-6 pt-5 text-center"
            for="{{ $id ?? '' }}"
        >
            @include('components.icon', [
                'icon' => 'add',
                'custom' => 'text-2xl text-primary',
            ])
            <p class="px-8 text-sm">Arraste aqui um arquivo de imagem ou <span class="text-primary">Procure por um arquivo</span></p>
        </label>

        <div
            class="fileListContent"
            id="{{ ($id ?? '') . 'fileListContent' }}"
        >
            <ul class="fileList w-full space-y-1 px-4 pb-4"></ul>
        </div>

    </div>

</div>
