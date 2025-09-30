@extends('layouts.members')

@section('content')
    <form id="formModulo" class="mt-4 mb-4" action="{{ route('dashboard.members.createLesson') }}" method="POST"
        enctype="multipart/form-data">
        @csrf

        <div class="space-y-10">
            <h3>Adicionar conteúdo: Vídeo</h3>

            <input type="hidden" name="moduleId" value="{{ $moduleId }}">
            <input type="hidden" name="courseId" value="{{ $course['id'] }}">
            <input type="hidden" name="draft" id="inputDraft" value="false">

            <div class="space-y-8">
                <div class="rounded-2xl bg-white">
                    <div class="px-6 pb-6 pt-5">
                        <div class="grid grid-cols-12 gap-6">

                            <div class="col-span-12">
                                <label id="name" class="mb-1">Nome da aula <span
                                        class="text-danger-400">*</span></label>
                                <input value="{{ old('name') }}" class="mt-2" type="text" id="name"
                                    name="name" placeholder="Digite o nome da sua aula" required />
                            </div>

                            <div class="col-span-12">
                                <div class="mb-4">
                                    <label class="mb-3 block text-sm font-medium">Tipo de vídeo</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="videoType" value="url" class="mr-2"
                                                onchange="toggleVideoInput()"
                                                {{ old('videoType', 'url') === 'url' ? 'checked' : '' }}>
                                            URL Externa (YouTube, Panda Video, Vimeo)
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="videoType" value="upload" class="mr-2"
                                                onchange="toggleVideoInput()"
                                                {{ old('videoType') === 'upload' ? 'checked' : '' }}>
                                            Upload de Arquivo
                                        </label>
                                    </div>
                                </div>

                                <div id="urlInput" class="video-input">
                                    <label id="videoUrl" class="mb-1">URL da aula</label>
                                    <input class="mt-2" type="url" value="{{ old('videoUrl') }}" id="videoUrl"
                                        name="videoUrl" placeholder="Digite a url sua aula" />
                                </div>

                                <div id="fileInput" class="video-input" style="display: none;">
                                    <label class="mb-1">Arquivo de vídeo <span class="text-sm text-gray-500">(máx.
                                            2GB)</span></label>
                                    <div class="mt-2 pb-2">
                                        <input type="file" id="videoFile"
                                            accept="video/mp4,video/mov,video/avi,video/wmv,video/flv,video/webm,video/mkv"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                            onchange="validateVideoFile(this)" />
                                    </div>
                                    <div class="pt-2">
                                        <h5 class="font-medium pt-2">Foto de Capa da Aula (Opcional)</h5>
                                        <p class="text-sm text-gray-600 mt-1 pt-2 mb-3">
                                            Dimensões recomendadas: 1280x720 pixels (16:9) | Formatos aceitos: JPG, PNG,
                                            WEBP | Máximo: 2MB
                                        </p>
                                    </div>

                                    <div class="pt-3 pb-6">
                                        <div class="space-y-4">
                                            <input type="file" name="coverImage" id="coverImage" accept="image/*"
                                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                                onchange="previewCoverImage(this)" />
                                            <div id="coverImagePreview" class="hidden">
                                                <div class="relative inline-block">
                                                    <img id="coverImageImg" src="" alt="Preview da capa"
                                                        class="max-w-sm h-auto rounded-lg border border-gray-200" />
                                                    <button type="button" onclick="removeCoverImagePreview()"
                                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600">
                                                        ×
                                                    </button>
                                                </div>
                                                <p class="text-sm text-gray-600 mt-2">
                                                    <span id="imageInfo"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl bg-white">

                    <div class="p-6">
                        <h5 class="font-medium">Descrição da aula (Opcional)</h5>
                    </div>

                    <div class="px-6 pb-6">
                        <textarea rows="6" id="description" name="description" maxlength="5000"
                            placeholder="Explique a sua aula no máximo 5000 characters" oninput="setCharacterLimit(this)">{{ old('description') }}</textarea>
                    </div>

                </div>



                <div class="rounded-2xl bg-white">

                    <div class="p-6">
                        <h5 class="font-medium">Anexos</h5>
                    </div>

                    <div class="px-6 pb-6">
                        @include('components.dropzone', [
                            'id' => 'attachments[]',
                            'name' => 'attachments[]',
                            'accept' => 'image/*,application/pdf',
                            'isMultiple' => true,
                        ])
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 mt-5">
                    <button
                        class="button h-12 rounded-full border border-neutral-200 hover:bg-neutral-200 active:bg-neutral-300"
                        type="submit" onclick="submitForm(true)">
                        Salvar como rascunho
                    </button>

                    <button class="button button-primary h-12 rounded-full" type="submit" onclick="submitForm(false)">
                        Salvar Alterações
                    </button>
                </div>
            </div>
        </div>
    </form>
    <div id="uploadVideoModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
        <div class="relative max-w-2xl w-full bg-white rounded-3xl p-6 shadow-xl" style="transform: translateX(100px);">
            <div class="flex items-center gap-6 justify-center">
                <h3>Fazendo o upload do vídeo</h3>
            </div>
            <div class="pb-8">
                <div class="flex items-center justify-center mb-10" id="spinQrCode">
                    <h2>Não feche ou mude a tela</h2>
                </div>
                <div class="flex items-center justify-center mb-10" id="spinQrCode">
                    <img class="h-10 w-10 animate-spin" src="/images/dashboard/spin-gray.svg">
                    Aguarde...
                </div>
                <div id="uploadProgress" class="mt-2" style="display: none;">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div id="progressBar" class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                    <p id="progressText" class="text-sm text-gray-600 mt-1">Preparando upload...</p>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
    <script src="{{ asset('js/dashboard/dropzone-config.js') }}"></script>
    <script src="{{ asset('js/members/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('js/members/tinymce/langs/pt_BR.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/tus-js-client@2/dist/tus.min.js"></script>

    <script>
        tinymce.init({
            selector: '#description',
            language: 'pt_BR',
            toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | table',
            plugins: 'lists, table',
            menubar: false,
            height: 300,
            branding: false,
        });
    </script>

    <script>
        const formHandler = {
            currentVideoId: null,

            submitForm(isDraft) {
                document.getElementById('inputDraft').value = isDraft ? 'true' : 'false';
                const videoType = document.querySelector('input[name="videoType"]:checked')?.value;

                if (videoType === 'upload' && this.hasVideoFile()) {
                    this.handleVideoUpload(isDraft);
                } else {
                    document.getElementById('formModulo').submit();
                }
            },

            hasVideoFile() {
                const fileInput = document.getElementById('videoFile');
                return fileInput.files.length > 0;
            },

            async handleVideoUpload(isDraft) {
                try {
                    this.showUploadProgress();

                    const fileInput = document.getElementById('videoFile');
                    const file = fileInput.files[0];
                    const lessonName = document.getElementById('name').value || "";

                    const maxSize = 2 * 1024 * 1024 * 1024;
                    if (file.size > maxSize) {
                        this.hideUploadProgress();
                        notyf.error("O arquivo excede o limite de 2GB.");
                        throw new Error("O arquivo excede o limite de 2GB.");
                    }

                    const metadata = {
                        name: file.name.replace(/[\u00A0-\u9999<>\&]/g, ""),
                        filename: file.name.replace(/[\u00A0-\u9999<>\&]/g, ""),
                        filetype: file.type,
                        size: file.size
                    };

                    this.updateProgress(5, 'Obtendo URL de upload...');
                    const uploadUrlData = await this.getUploadUrl(metadata);

                    if (!uploadUrlData.success) {
                        throw new Error(uploadUrlData.message);
                    }

                    this.currentVideoId = uploadUrlData.video_id;
                    const videoIdInput = document.createElement('input');
                    videoIdInput.type = 'hidden';
                    videoIdInput.name = 'videoId';
                    videoIdInput.value = this.currentVideoId;


                    openUploadModal()
                    this.updateProgress(0, 'Iniciando upload direto...');
                    await this.uploadWithTus(uploadUrlData.upload_url, file, metadata);
                    this.updateProgress(100, 'Upload concluído, processando vídeo...');
                    closeUploadModal()
                    document.getElementById('formModulo').appendChild(videoIdInput);

                    fileInput.removeAttribute('name');
                    document.getElementById('formModulo').submit();

                } catch (error) {
                    this.hideUploadProgress();
                    console.log(error.message);
                    notyf.error("Erro ao fazer upload");
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                }
            },

            async getUploadUrl(metadata) {
                const response = await fetch('{{ route('dashboard.cloudflare.getUploadTusUrl') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        metadata
                    })
                });

                return await response.json();
            },

            uploadWithTus(upload_url, file, metadata) {
                return new Promise((resolve, reject) => {
                    const upload = new tus.Upload(file, {
                        uploadUrl: upload_url,
                        chunkSize: 35 * 1024 * 1024,
                        retryDelays: [0, 3000, 5000, 10000, 20000, 40000, 60000, 90000, 120000, 180000],
                        headers: {
                            'Authorization': `Bearer {{ $apiToken }}`
                        },
                        metadata: {
                            name: metadata.name,
                            filename: metadata.filename,
                            filetype: metadata.filetype
                        },
                        onError: (error) => {
                            console.error('Erro no upload TUS:', error);
                            reject(error);
                        },
                        onProgress: (bytesUploaded, bytesTotal) => {
                            const percentage = ((bytesUploaded / bytesTotal) * 100).toFixed(2);
                            formHandler.updateProgress(percentage, `Enviando: ${percentage}%`);
                        },
                        onSuccess: () => {
                            console.log('Upload concluído!');
                            resolve();
                        }
                    });

                    upload.start();
                });
            },

            showUploadProgress() {
                document.getElementById('uploadProgress').style.display = 'block';
                this.updateProgress(0, 'Iniciando...');

                document.querySelectorAll('button[type="submit"]').forEach(btn => {
                    btn.disabled = true;
                    btn.style.opacity = '0.6';
                });
            },

            hideUploadProgress() {
                document.getElementById('uploadProgress').style.display = 'none';
                document.querySelectorAll('button[type="submit"]').forEach(btn => {
                    btn.disabled = false;
                    btn.style.opacity = '1';
                });
            },

            updateProgress(percent, message) {
                const progressBar = document.getElementById('progressBar');
                const progressText = document.getElementById('progressText');

                progressBar.style.transition = 'width 0.3s ease';
                progressBar.style.width = Math.min(percent, 100) + '%';
                progressText.textContent = message;
            },

            toggleVideoInput() {
                const videoType = document.querySelector('input[name="videoType"]:checked')?.value;
                const urlInput = document.getElementById('urlInput');
                const fileInput = document.getElementById('fileInput');
                const videoUrl = document.getElementById('videoUrl');
                const videoFile = document.getElementById('videoFile');
                const imagePreview = document.getElementById('image-preview');


                const isUrl = videoType === 'url';
                urlInput.style.display = isUrl ? 'block' : 'none';
                fileInput.style.display = isUrl ? 'none' : 'block';
                imagePreview.style.display = isUrl ? 'none' : 'block';
                videoUrl.required = isUrl;
                videoFile.required = !isUrl;
            }
        };

        function submitForm(isDraft) {
            formHandler.submitForm(isDraft);
        }

        function toggleVideoInput() {
            formHandler.toggleVideoInput();
        }

        if (!document.querySelector('meta[name="csrf-token"]')) {
            const meta = document.createElement('meta');
            meta.name = 'csrf-token';
            meta.content = '{{ csrf_token() }}';
            document.head.appendChild(meta);
        }

        document.addEventListener('DOMContentLoaded', function() {
            formHandler.toggleVideoInput();
        });

        function previewCoverImage(input) {
            const file = input.files[0];
            const preview = document.getElementById('coverImagePreview');
            const img = document.getElementById('coverImageImg');
            const imageInfo = document.getElementById('imageInfo');

            if (!file) return;

            const maxSizeMB = 2;
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

            if (file.size > maxSizeMB * 1024 * 1024) {
                notyf.error(`O arquivo deve ter no máximo ${maxSizeMB}MB`);
                removeCoverImagePreview()
                throw new Error("O arquivo excede o limite de 2MB.");
            }

            if (!allowedTypes.includes(file.type)) {
                notyf.error('Formato não permitido. Use JPG, PNG ou WEBP');
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.classList.remove('hidden');

                const tempImg = new Image();
                tempImg.onload = function() {
                    const width = this.width;
                    const height = this.height;
                    const aspectRatio = (width / height).toFixed(2);
                    const isRecommendedRatio = Math.abs(aspectRatio - 1.78) < 0.1;

                    imageInfo.innerHTML = `
                Dimensões: ${width}x${height} pixels |
                Proporção: ${aspectRatio}:1 |
                Tamanho: ${(file.size / 1024 / 1024).toFixed(2)}MB
                ${isRecommendedRatio ? '<span class="text-green-600">✓ Proporção recomendada</span>' : '<span class="text-yellow-600">⚠ Proporção diferente da recomendada (16:9)</span>'}
            `;
                };
                tempImg.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        function removeCoverImagePreview() {
            const input = document.getElementById('coverImage');
            const preview = document.getElementById('coverImagePreview');
            const img = document.getElementById('coverImageImg');

            input.value = '';
            img.src = '';
            preview.classList.add('hidden');
        }
        const uploadModal = document.getElementById('uploadVideoModal');

        function openUploadModal() {
            if (uploadModal) {
                uploadModal.classList.add('block');
                uploadModal.classList.remove('hidden');
            }
        }

        function closeUploadModal() {
            if (uploadModal) {
                uploadModal.classList.add('hidden');
                uploadModal.classList.remove('block');
            }
        }

        function validateVideoFile(input) {
            const file = input.files[0];
            const maxSize = 2 * 1024 * 1024 * 1024;

            if (file && file.size > maxSize) {
                notyf.error("O arquivo excede o limite de 2GB.");
                input.value = ''
                return false;
            }
            return true;
        }
    </script>
@endpush
