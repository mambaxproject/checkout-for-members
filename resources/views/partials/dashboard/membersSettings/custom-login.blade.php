<div id="customLogin">
    <form action="{{ route('dashboard.members.customization', ['courseId' => $course['id']]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="imgBannerOld" value="{{ $customization['login']['banner'] ?? '' }}">
        <input type="hidden" name="imgLogoOld" value="{{ $customization['login']['logo'] ?? '' }}">
        @component('components.card', ['custom' => 'p-6 md:p-8'])
            <div class="space-y-4 md:space-y-6">
                <div class="">
                    <h3>Personalização da Página de Login</h3>
                    <p class="text-sm text-neutral-400">Personalize a aparência da página de login para o seu domínio
                        próprio
                    </p>
                </div>
                <div class="space-y-4">
                    <ul class="flex items-center gap-1 border-b border-neutral-100"
                        data-tabs-toggle="#custom-login-tab-content">
                        <li>
                            <button
                                class="border-b border-transparent px-3 py-2 hover:border-transparent aria-selected:border-primary aria-selected:font-semibold aria-selected:text-primary aria-selected:hover:text-primary"
                                data-tabs-target="#tab-content" aria-selected="true" role="tab" type="button">
                                Conteúdo
                            </button>
                        </li>
                        <li>
                            <button
                                class="border-b border-transparent px-3 py-2 hover:border-transparent aria-selected:border-primary aria-selected:font-semibold aria-selected:text-primary aria-selected:hover:text-primary"
                                data-tabs-target="#tab-apparence" aria-selected="false" role="tab" type="button">
                                Aparencia
                            </button>
                        </li>
                        <li>
                            <button
                                class="border-b border-transparent px-3 py-2 hover:border-transparent aria-selected:border-primary aria-selected:font-semibold aria-selected:text-primary aria-selected:hover:text-primary"
                                data-tabs-target="#tab-banner" aria-selected="false" role="tab" type="button">
                                Banner
                            </button>
                        </li>
                    </ul>
                    <div class="rounded-xl bg-neutral-50 p-8" id="custom-login-tab-content">
                        <div class="space-y-4" id="tab-content" role="tabpanel">
                            <div class="">
                                <label for="logo-upload">Logo</label>
                                @include('components.dropzone', [
                                    'id' => 'logo-upload',
                                    'name' => 'login[logo]',
                                ])
                            </div>
                            <div>
                                <label for="title">Título</label>
                                <input id="title" name="login[firstText]" type="text"
                                    value="{{ $customization['login']['firstText'] ?? 'Olá, bem-vindo de volta!' }}">
                            </div>
                            <div class="">
                                <label for="subtitle">Subtítulo</label>
                                <input id="subtitle" name="login[secondText]" type="text"
                                    value="{{ $customization['login']['secondText'] ?? 'Insira suas credenciais abaixo para acessar sua conta:' }}">
                            </div>

                        </div>

                        <div class="hidden space-y-4" id="tab-apparence" role="tabpanel">

                            <div class="">
                                @include('components.toggle', [
                                    'id' => 'mode-dark',
                                    'name' => 'login[darkMode]',
                                    'label' => 'Modo escuro',
                                    'contentEmpty' => true,
                                    'value' => $customization['login']['darkMode'] ?? false,
                                    'isChecked' => $customization['login']['darkMode'] ?? false,
                                ])
                            </div>
                        </div>
                        <div class="hidden space-y-4" id="tab-banner" role="tabpanel">
                            <div class="">
                                <label class="mb-3" for="banner-position">
                                    Posição do Banner
                                </label>
                                <div class="flex items-center gap-6">
                                    <label class="radio" for="banner-position-right">
                                        <input id="banner-position-right" name="login[positionBanner]" type="radio"
                                            value="right"
                                            {{ ($customization['login']['positionBanner'] ?? 'right') === 'right' ? 'checked' : '' }}>
                                        Direita
                                    </label>
                                    <label class="radio" for="banner-position-left">
                                        <input id="banner-position-left" name="login[positionBanner]" type="radio"
                                            value="left"
                                            {{ ($customization['login']['positionBanner'] ?? 'right') === 'left' ? 'checked' : '' }}>
                                        Esquerda
                                    </label>
                                </div>
                            </div>

                            <div class="">
                                <label for="banner-upload">Imagem do Banner</label>
                                @include('components.dropzone', [
                                    'id' => 'banner-upload',
                                    'name' => 'login[banner]',
                                ])
                                <p class="mt-2 text-sm text-neutral-400">Envie uma imagem de 1000x1000px, com no máximo 1
                                    MB.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcomponent

        <button class="button button-primary ml-auto h-12 rounded-full" type="submit">
            Salvar personalização
        </button>

    </form>

    @component('components.card', ['custom' => 'p-6 md:p-8'])
        <div class="space-y-4 md:space-y-6">

            <div class="">

                <h3>Preview</h3>
                <p class="text-sm text-neutral-400">Visualize como ficará sua página de login personalizada</p>

            </div>

            <div class="overflow-hidden rounded-xl border border-neutral-200">

                <div class="flex" id="page-content">

                    <div class="w-full max-w-xl space-y-8 bg-white px-16 py-20 dark:bg-neutral-800">

                        <div class="space-y-3">

                            <div id="logo-preview">
                                <img class="h-full w-full object-cover"
                                    src="{{ $customization['login']['logo'] ?? asset('images/members/brand-suitmembers.svg') }}"
                                    alt="Imagem default" loading="lazy" />
                            </div>
                            <h1 class="dark:text-white" id="title-preview">
                                'Olá, bem-vindo de volta!'
                            </h1>
                            <p class="dark:text-neutral-400" id="subtitle-preview">
                                Insira suas credenciais abaixo para acessar sua conta:
                            </p>

                        </div>

                        <div class="space-y-10">

                            <div class="space-y-4">

                                <div class="">

                                    <label class="dark:text-white" for="">
                                        E-mail
                                    </label>

                                    <input class="placeholder:text-neutral-500 dark:bg-neutral-800 dark:ring-neutral-700"
                                        placeholder="email@dominio.com" type="text" readonly>

                                </div>

                                <div class="">

                                    <label class="dark:text-white" for="">
                                        Senha
                                    </label>

                                    <input class="placeholder:text-neutral-500 dark:bg-neutral-800 dark:ring-neutral-700"
                                        placeholder="***********" type="password" readonly>

                                </div>

                            </div>

                            <div class="space-y-4">

                                <button
                                    class="animate h-12 w-full rounded-full bg-neutral-900 text-white dark:hover:bg-neutral-950"
                                    type="button">
                                    Entrar
                                </button>

                                <div class="flex items-center gap-4">

                                    <span class="h-px w-full bg-neutral-200 dark:bg-neutral-700"></span>
                                    <span class="dark:text-neutral-500">ou</span>
                                    <span class="h-px w-full bg-neutral-200 dark:bg-neutral-700"></span>

                                </div>

                                <button
                                    class="animate h-12 w-full rounded-full border border-neutral-900 text-neutral-700 dark:border-neutral-500 dark:text-neutral-400 dark:hover:bg-neutral-700"
                                    type="button">
                                    Cadastre-se
                                </button>

                            </div>

                        </div>

                    </div>

                    <div class="flex-1 bg-yellow-200" id="banner-preview">
                        <img class="h-full w-full object-cover"
                            src="{{ $customization['login']['banner'] ?? asset('images/members/login-banner.png') }}"
                            alt="Imagem default" loading="lazy" />
                    </div>
                </div>
            </div>
        </div>
    @endcomponent

    @push('script')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modeDark = document.getElementById('mode-dark');
                const previewTitle = document.querySelector('#title-preview');
                const pageContent = document.getElementById('page-content');
                const positionLeft = document.getElementById('banner-position-left');
                const positionRight = document.getElementById('banner-position-right');

                const titleInput = document.getElementById('title');
                const subtitleInput = document.getElementById('subtitle');

                const previewSubtitle = document.querySelector('#subtitle-preview');


                const logoUpload = document.getElementById('logo-upload');
                const previewLogoContainer = document.getElementById('logo-preview');


                const bannerUpload = document.getElementById('banner-upload');
                const previewContainer = document.getElementById('banner-preview');

                const bindInputPreview = (input, preview) => {
                    if (input && preview) {
                        preview.textContent = input.value;
                        input.addEventListener('input', () => {
                            preview.textContent = input.value;
                        })
                    }
                }

                const toggleDarkMode = () => {
                    if (!modeDark) return;

                    document.body.classList.toggle('dark', modeDark.checked);
                    modeDark.addEventListener('change', toggleDarkMode);
                }

                const toggleBannerPosition = () => {
                    if (!pageContent || !positionLeft || !positionRight) return;

                    const updatePosition = () => {
                        pageContent.classList.toggle('flex-row-reverse', positionLeft.checked);
                    }

                    updatePosition();
                    positionLeft.addEventListener('change', updatePosition);
                    positionRight.addEventListener('change', updatePosition);
                }

                if (bannerUpload && previewContainer) {
                    bannerUpload.addEventListener('input', () => {
                        const file = bannerUpload.files[0];
                        if (file && file.type.startsWith('image/')) {
                            const reader = new FileReader();

                            reader.onload = (e) => {
                                previewContainer.innerHTML = '';

                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.alt = 'Preview do banner';
                                img.className = 'w-full h-full object-cover';

                                previewContainer.appendChild(img);
                            };

                            reader.readAsDataURL(file);
                        } else {
                            previewContainer.innerHTML = '<p class="text-sm text-red-500">Arquivo inválido</p>';
                        }
                    });
                }

                if (logoUpload && previewLogoContainer) {
                    logoUpload.addEventListener('input', () => {

                        const file = logoUpload.files[0];
                        if (file && file.type.startsWith('image/')) {
                            const reader = new FileReader();

                            reader.onload = (e) => {
                                previewLogoContainer.innerHTML = '';

                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.alt = 'Preview da logo';
                                img.className = 'w-full h-full object-cover';

                                previewLogoContainer.appendChild(img);
                            };

                            reader.readAsDataURL(file);
                        } else {
                            previewLogoContainer.innerHTML =
                                '<p class="text-sm text-red-500">Arquivo inválido</p>';
                        }
                    });
                }

                bindInputPreview(titleInput, previewTitle);
                bindInputPreview(subtitleInput, previewSubtitle);

                toggleDarkMode();

                toggleBannerPosition();
            })
        </script>
    @endpush
</div>
