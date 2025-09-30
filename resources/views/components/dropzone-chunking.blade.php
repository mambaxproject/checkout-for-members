<div class="upload-full-zone">

    <div
        class="dropzone flex !min-h-[auto] flex-col items-center justify-center rounded-xl !border !border-neutral-200 hover:border-primary"
        id="chunkUploadDropzone"
    >

        <input
            type="hidden"
            name="dzUuid"
            value="{{ \Illuminate\Support\Str::uuid() }}"
        />

        <input
            type="hidden"
            id="media[attachmentFromChuncking]"
            name="media[attachmentFromChuncking]"
        />

    </div>

</div>

@push('custom-style')
    <link
        rel="stylesheet"
        href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css"
        type="text/css"
    />
@endpush

@push('custom-script')
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script src="{{ asset('js/dashboard/dropzone-chunking-config.js') }}"></script>
@endpush
