<div class="tab-content hidden" id="tab-related-courses" data-tab="tab-related-courses">
    @component('components.card', ['custom' => 'p-6 md:p-8'])
        <div class="space-y-8 pb-5">
            <div class="flex items-center justify-between">
                <h3>Cursos Recomendados</h3>
                <button class="button button-primary h-12 rounded-full" data-modal-target="addRelatedCourseModal"
                    data-modal-toggle="addRelatedCourseModal" type="button">
                    Adicionar recomendação
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4 pt-5">
            @foreach ($relatedCourses['data'] as $course)
                <div class="rounded-xl flex flex-col h-full" style="background-color: rgb(229 229 229 / var(--tw-bg-opacity))">
                    <figure
                        class="relative flex h-[240px] w-full items-center justify-center overflow-hidden rounded-t-xl bg-white">
                        <div
                            class="bg-success-200 absolute left-3 top-3 rounded-full px-2 py-1 text-[10px] font-semibold uppercase">
                            Formação
                        </div>
                        <img class="h-full w-full object-cover" src="{{ $course['thumbnailUrl'] }}"
                            alt="{{ $course['name'] }}" loading="lazy" />
                    </figure>

                    <div class="flex flex-col flex-1 p-4">
                        <div class="flex-1 space-y-2">
                            <p class="font-bold">Produto : {{ $course['productName'] }}</p>
                            <p class="font-bold">
                                {{ $course['typeCourse'] === 'course' ? 'Curso' : 'Formação' }} :
                                {{ $course['name'] }}
                            </p>
                            <p class="font-bold">Oferta : {{ $course['offerName'] }}</p>
                        </div>

                        <div class="flex justify-between w-full mt-auto pt-4">
                            <button data-modal-target="editOfferModal" data-modal-toggle="editOfferModal"
                                class="button button-primary h-10 rounded-full px-4 flex items-center justify-center font-medium"
                                onclick="populateEditOfferModal({
                    relationId: {{ $course['relationId'] }},
                    productName: '{{ addslashes($course['productName']) }}',
                    productRef: '{{ $course['productRef'] }}',
                    offerId: {{ $course['offerId'] }}
                })">
                                Editar
                            </button>

                            <form id="formDeleteRelation{{ $course['relationId'] }}"
                                action="{{ route('dashboard.members.deleteCourseRelation', ['courseRelationId' => $course['relationId']]) }}"
                                method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 text-white hover:bg-red-700 h-10 rounded-full px-4 flex items-center justify-center font-medium"
                                    onclick="return confirm('Tem certeza que deseja remover esta relação de curso?');">
                                    Remover
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $relatedCourses['pagination']->appends(request()->query())->fragment('tab=tab-related-courses')->links() }}
    @endcomponent
    @component('components.modal', ['id' => 'addRelatedCourseModal', 'title' => 'Adicionar um curso relacionado'])
        <form id="formCreateRelated"
            action="{{ route('dashboard.members.createCourseRelation', ['courseId' => $course['id']]) }}" method="POST">
            @csrf
            @component('components.card')
                <input type="hidden" name="courseId" value="{{ $course['id'] }}">
                <div class="space-y-6 px-6 py-6">
                    <label>Produto</label>
                    <select id="productSelect" name="productUuid" disabled>
                        <option value="">Selecione um produto</option>
                    </select>

                    <div id="offerContainer" class="mt-4 hidden">
                        <select id="offerSelect" name="offerId" disabled>
                            <option value="">Selecione a oferta</option>
                        </select>
                    </div>
                </div>
            @endcomponent

            <div class="flex items-center justify-end">
                <button id="submitCreateRelated" class="button button-primary h-12 rounded-full" type="submit"
                    onclick="this.disabled = true; this.innerText = 'Salvando...'; this.form.submit();">
                    Salvar
                </button>
            </div>
        </form>
    @endcomponent
    @component('components.modal', ['id' => 'editOfferModal', 'title' => 'Editar oferta do curso relacionado'])
        <form id="formEditOffer"
            action="{{ route('dashboard.members.updateCourseRelation', ['courseRelationId' => '__PLACEHOLDER__']) }}"
            method="POST">
            @method('PUT')
            @csrf
            @component('components.card')
                <input type="hidden" name="relationId" id="editRelationId" value="">
                <input type="hidden" name="productRef" id="editProductRef" value="">
                <div class="space-y-6 px-6 py-6">
                    <label for="editProductName">Produto</label>
                    <input type="text" id="editProductName" class="w-full rounded border-gray-300" disabled>

                    <div class="mt-4">
                        <label for="editOfferSelect">Oferta</label>
                        <select id="editOfferSelect" name="offerId" class="w-full rounded border-gray-300" required>
                            <option value="">Carregando ofertas...</option>
                        </select>
                    </div>
                </div>
            @endcomponent

            <div class="flex items-center justify-end">
                <button id="submitEditOffer" class="button button-primary h-12 rounded-full" type="submit"
                    onclick="this.disabled = true; this.innerText = 'Salvando...'; this.form.submit();">
                    Salvar
                </button>
            </div>
        </form>
    @endcomponent

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const openModalBtn = document.querySelector('[data-modal-target="addRelatedCourseModal"]');
        const productSelect = document.getElementById('productSelect');
        const offerSelect = document.getElementById('offerSelect');
        const offerContainer = document.getElementById('offerContainer');

        let productsLoaded = false;
        offerContainer.classList.add('hidden');

        openModalBtn.addEventListener('click', async () => {
            if (productsLoaded) return;

            productSelect.innerHTML = '<option value="">Carregando...</option>';
            productSelect.disabled = true;

            try {
                const res = await fetch(
                    '{{ route('dashboard.members.getProductsAvailableCreateRelation', ['courseId' => $course['id']]) }}', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    });

                if (!res.ok) throw new Error('Falha ao carregar produtos');

                const products = await res.json();
                productSelect.innerHTML = '<option value="">Selecione um produto</option>';

                products.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.client_product_uuid;
                    opt.textContent = p.name + (
                        Number(p.affiliate_id) ?
                        ' - (afiliado)' :
                        (Number(p.coproducer_id) ? ' - (co-produtor)' : '')
                    );
                    opt.dataset.productRef = p.client_product_uuid;
                    productSelect.appendChild(opt);
                });

                productSelect.disabled = false;
                productsLoaded = true;
            } catch (err) {
                console.error(err);
                productSelect.innerHTML = '<option value="">Erro ao carregar produtos</option>';
            }
        });

        productSelect.addEventListener('change', async () => {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const productRef = selectedOption.dataset.productRef;

            offerSelect.innerHTML = '<option value="">Selecione a oferta</option>';

            if (productRef) {
                offerSelect.disabled = true;
                offerContainer.classList.remove('hidden');

                try {
                    const res = await fetch(
                        "{{ route('dashboard.members.getProductsOffer', ['productRef' => 'PRODUCT_REF_PLACEHOLDER']) }}"
                        .replace('PRODUCT_REF_PLACEHOLDER', productRef), {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin'
                        });

                    if (!res.ok) throw new Error('Falha ao carregar ofertas');

                    const offers = await res.json();
                    offers.forEach(offer => {
                        const opt = document.createElement('option');
                        opt.value = offer.id;
                        opt.textContent = offer.name;
                        offerSelect.appendChild(opt);
                    });

                    offerSelect.disabled = false;
                } catch (err) {
                    console.error(err);
                    offerSelect.innerHTML = '<option value="">Erro ao carregar ofertas</option>';
                }
            } else {
                offerSelect.disabled = true;
                offerContainer.classList.add('hidden');
            }
        });
    });

    function populateEditOfferModal(courseRelation) {
        const editRelationId = document.getElementById('editRelationId');
        const editProductName = document.getElementById('editProductName');
        const editProductRef = document.getElementById('editProductRef');
        const editOfferSelect = document.getElementById('editOfferSelect');
        const formEditOffer = document.getElementById('formEditOffer');

        editRelationId.value = courseRelation.relationId;
        editProductName.value = courseRelation.productName;
        editProductRef.value = courseRelation.productRef;

        formEditOffer.action = formEditOffer.action.replace('__PLACEHOLDER__', courseRelation.relationId);

        editOfferSelect.innerHTML = '<option value="">Carregando ofertas...</option>';
        editOfferSelect.disabled = true;

        fetch("{{ route('dashboard.members.getProductsOffer', ['productRef' => 'PRODUCT_REF_PLACEHOLDER']) }}".replace(
                'PRODUCT_REF_PLACEHOLDER', courseRelation.productRef), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(offers => {
                editOfferSelect.innerHTML = '';
                offers.forEach(offer => {
                    const opt = document.createElement('option');
                    opt.value = offer.id;
                    opt.textContent = offer.name;
                    if (offer.id === courseRelation.offerId) opt.selected = true;
                    editOfferSelect.appendChild(opt);
                });
                editOfferSelect.disabled = false;
            })
            .catch(err => {
                console.error(err);
                editOfferSelect.innerHTML = '<option value="">Erro ao carregar ofertas</option>';
                editOfferSelect.disabled = false;
            });
    }
</script>
