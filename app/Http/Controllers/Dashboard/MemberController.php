<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ProductTypesEnum;
use App\Enums\SituationProductEnum;
use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Member\{AddConfigCommentRequest, AddDomainRequest, AddManyStudentsRequest, AddStudentRequest};
use App\Http\Requests\Member\{ChangeMemberModeratorRequest, ChangeMemberStatusRequest, CreateClassRequest, CreateCourseRelationRequest};
use App\Http\Requests\Member\{CreateCourseRequest, CreateLessonRequest, CreateModuleRequest, CreateQuizRequest};
use App\Http\Requests\Member\{CreateTrackRequest, CustomizationRequest, GetStudentsRequest, ToggleClassRequest};
use App\Http\Requests\Member\{UpdateClassRequest, UpdateCourseRequest, UpdateCourseTrackRequest, UpdateLessonRequest, UpdateModuleRequest, UpdateQuizRequest};
use App\Models\Product;
use App\Models\User;
use App\Services\Members\SuitMembersApiService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public $suitMembersApiService;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $secretMember = auth()->user()->shop()->client_secret_members;

            if (is_null($secretMember)) {
                return response()->view('dashboard.members.enable');
            }

            $this->suitMembersApiService = new SuitMembersApiService(30, $secretMember);
            return $next($request);
        })->except(['enable', 'checkCanAccessMembers']);
    }

    public function index(Request $request): View
    {
        $filters = request()->only(['name', 'status', 'categoryId', 'createdAt', 'page']);
        $route = 'producer/courses';
        $courses = $this->suitMembersApiService->get($route, $filters);

        $pagination = $this->getPaginator($courses['pagination'], $request->fullUrl());

        if (!array_key_exists('data', $courses)) {
            $courses['data'] = [];
        }

        $courses = $this->appendProductsByRef($courses);
        $route = 'categories';
        $categories = $this->suitMembersApiService->get($route)['data'];

        return view('dashboard.members.index', compact('courses', 'categories', 'pagination'));
    }

    private function appendProductsByRef(array &$courses): array
    {
        $productUuid = array_unique(array_column($courses['data'], 'productRef'));
        $products = Product::whereIn('client_product_uuid', $productUuid)->get()->keyBy('client_product_uuid');

        foreach ($courses['data'] as &$course) {
            $productUuid = $course['productRef'];
            $course['productName'] = $products[$productUuid]->name ?? null;
        }

        return $courses;
    }

    public function show(): View
    {
        return view('dashboard.members.show');
    }

    public function create(CreateCourseRequest $request): RedirectResponse
    {
        try {
            $route = 'courses';
            $response = $this->suitMembersApiService->post($route, $request->toArray());
            $product = Product::where('client_product_uuid', $request->productUuid)->firstOrFail();
            $this->checkCanEnableCourse($product);
            $this->checkNeedChangeProductType($product);
            $redirect = (bool) $request->input('redirect');
            return $redirect ?
                redirect()->route('dashboard.members.edit', ['courseId' => $response['data']['id']]) :
                redirect()->back()->withInput()->withFragment('tab=tab-affiliations')
                ->with('success', 'Curso criado com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th, 'tab=tab-area-members');
        }
    }

    private function checkCanEnableCourse(Product $product): void
    {
        if (!$product->isPublished) {
            return;
        }

        $route = 'courses/' . $product->client_product_uuid . '/enable';
        $tokenAdmin = config('services.members.token');
        $suitMembersApiService = new SuitMembersApiService(30, $tokenAdmin, 'admin');
        $suitMembersApiService->put($route);
    }

    private function checkNeedChangeProductType(Product $product): void
    {
        if ($product->isTypeSuitMembers) {
            return;
        }

        $product->update(['type_id' => ProductTypesEnum::SUIT_MEMBERS]);
        Product::where('parent_id', $product->id)
            ->update(['type_id' => ProductTypesEnum::SUIT_MEMBERS]);
    }

    public function edit(int $courseId): View
    {
        $course = $this->getCourseData($courseId)['data'];
        $product = Product::where('client_product_uuid', $course['productRef'])->firstOrFail();
        $this->checkCanEnableCourse($product);
        $route = 'categories';
        $categories = $this->suitMembersApiService->get($route)['data'];
        return view('dashboard.members.edit', compact('course', 'product', 'categories'));
    }

    public function content(int $courseId, Request $request): View
    {
        $openModuleId = $request->query('openModuleId');
        $openTrackId = $request->query('openTrackId');
        $course = $this->getCourseData($courseId)['data'];
        $courseTracks = $this->getCourseTracks($course);
        $product = Product::where('client_product_uuid', $course['productRef'])->firstOrFail();
        return view('dashboard.members.content', compact('course', 'product', 'courseTracks', 'openModuleId', 'openTrackId'));
    }

    private function getCourseTracks(array $course): array
    {
        if (!$course['hasTrack']) {
            return [];
        }

        $route = 'producer/courses/' . $course['id'] . '/tracks';
        return $this->suitMembersApiService->get($route)['data'] ?? [];
    }

    public function addLesson(int $courseId, int $moduleId): View
    {
        $route = 'producer/courses/' . $courseId;
        $course = $this->suitMembersApiService->get($route)['data'];
        $product = Product::where('client_product_uuid', $course['productRef'])->firstOrFail();
        $accountId = config('services.cloudflare.stream.account_id');
        $apiToken = config('services.cloudflare.stream.api_token');
        return view('dashboard.members.content.add-lesson', compact('course', 'moduleId', 'product', 'accountId', 'apiToken'));
    }

    public function createLesson(CreateLessonRequest $request): RedirectResponse
    {
        try {
            $route = 'lessons';
            $response = $this->suitMembersApiService->post($route, body: $request->toArray())['data'];
            $courseId = $request->input('courseId');
            return $this->redirectLesson($courseId, $response)
                ->with('success', 'Aula criada com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    private function redirectLesson(int $courseId, array $response): RedirectResponse
    {
        $route = 'producer/courses/' . $courseId;
        $course = $this->suitMembersApiService->get($route)['data'];

        if (is_null($course['parentId'])) {
            return redirect()->route('dashboard.members.content',  ['courseId' => $course['id'], 'openModuleId' => $response['moduleId']]);
        }

        return redirect()->route('dashboard.members.addModuleTrack',  ['courseId' => $course['id'], 'openModuleId' => $response['moduleId']]);
    }

    public function addQuiz(int $courseId, int $moduleId): View
    {
        $route = 'producer/courses/' . $courseId;
        $course = $this->suitMembersApiService->get($route)['data'];
        $product = Product::where('client_product_uuid', $course['productRef'])->firstOrFail();
        return view('dashboard.members.content.add-quiz', compact('course', 'moduleId', 'product'));
    }

    public function createQuiz(CreateQuizRequest $request): RedirectResponse
    {
        try {
            $route = 'lessons';
            $response = $this->suitMembersApiService->post($route, $request->toArray())['data'];
            $courseId = $request->input('courseId');
            return $this->redirectLesson($courseId, $response)
                ->with('success', 'Quiz criado com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function addModule(int $courseId): View
    {
        $route = 'producer/courses/' . $courseId;
        $course = $this->suitMembersApiService->get($route)['data'];
        $product = Product::where('client_product_uuid', $course['productRef'])->firstOrFail();
        return view('dashboard.members.content.add-module', compact('course', 'product'));
    }

    public function createModule(CreateModuleRequest $request): View | RedirectResponse
    {
        try {
            $route = 'modules';
            $response = $this->suitMembersApiService->post($route, $request->toArray())['data'];
            return $this->redirectModule($request->all()['courseId'], $response)
                ->with('success', 'Módulo cadastrado com sucesso');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    private function redirectModule(int $courseId, array $response = []): RedirectResponse
    {
        $route = 'producer/courses/' . $courseId;
        $course = $this->suitMembersApiService->get($route)['data'];
        if (is_null($course['parentId'])) {
            return redirect()->route('dashboard.members.content',  ['courseId' => $course['id'], 'openModuleId' => $response['id']]);
        }

        return redirect()->route('dashboard.members.addModuleTrack',  ['courseId' => $course['id'], 'openModuleId' => $response['id']]);
    }

    public function students(GetStudentsRequest $request, int $courseId): View
    {
        $tab = $request->input('tab') ?? 'active';
        $course = $this->getCourseData($courseId)['data'];
        $product = Product::where('client_product_uuid', $course['productRef'])->firstOrFail();
        $routeClass = 'producer/courses/' . $course['id'] . '/class';
        $response = $this->suitMembersApiService->get($routeClass, $request->toArray());
        $classes = $response['data']['Classes'] ?? [];
        $route = 'producer/courses/' . $course['id'] . '/member';
        $students = [];
        $data = $this->suitMembersApiService->get($route, $request->toArray());
        $pagination = $this->getPaginator($data['pagination'], $request->fullUrl());
        $members = $data['data'];

        if (array_key_exists('members', $members)) {
            $students = $members['members'];
        }

        foreach ($classes as &$class) {
            $class['offer'] = isset($class['offerIds'][0]) ? $class['offerIds'][0] : null;
            unset($class['offerIds']);
        }

        return view('dashboard.members.students', compact('course', 'product', 'students', 'members', 'classes', 'pagination', 'tab'));
    }

    public function getPaginator(array $pagination, string $path): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $pagination['items'],
            $pagination['total'],
            $pagination['per_page'],
            $pagination['current_page'],
            [
                'path' => $path,
                'pageName' => 'page',
            ]
        );
    }

    public function classes(Request $request, int $courseId): View
    {
        $course = $this->getCourseData($courseId)['data'];
        $product = Product::where('client_product_uuid', $course['productRef'])->firstOrFail();
        $route = 'producer/courses/' . $course['id'] . '/class';
        $response = $this->suitMembersApiService->get($route, $request->query());
        $classes = $response['data']['Classes'] ?? [];
        $dashboard = $response['data'] ?? [];

        $offers = collect($classes)
            ->pluck('offerIds')
            ->flatten()
            ->map(fn($id) => (int) $id)
            ->unique()
            ->toArray();


        $productsUsed = Product::whereIn('id', $offers)
            ->pluck('name', 'id')
            ->toArray();

        foreach ($classes as &$class) {
            $offerIds = array_map('intval', $class['offerIds'] ?? []);
            $class['products'] = [];

            foreach ($offerIds as $offerId) {
                $class['products'][] = [
                    'id' => $offerId,
                    'name' => $productsUsed[$offerId] ?? 'Nome não encontrado'
                ];
            }
        }

        $pagination = $this->getPaginator($response['pagination'], $request->fullUrl());

        return view('dashboard.members.classes.index', compact(
            'course',
            'product',
            'classes',
            'pagination',
            'dashboard'
        ));
    }

    private function getCourseData(int $courseId): array
    {
        $route = 'producer/courses/' . $courseId;
        $course = $this->suitMembersApiService->get($route);

        if (is_null($course['data']['parentId'])) {
            return $course;
        }

        $route = 'producer/courses/' . $course['data']['parentId'];
        return $this->suitMembersApiService->get($route);
    }

    public function enable(): RedirectResponse
    {
        $user = auth()->user();
        $this->createProduceSuitMembers($user);
        return redirect()->route('dashboard.members.index');
    }

    public function createProduceSuitMembers(User $user): void
    {
        $body = $this->getBodyEnable($user);
        $tokenAdmin = config('services.members.token');
        $suitMembersApiService = new SuitMembersApiService(30, $tokenAdmin, 'admin');
        $route = 'producer/user';
        $response = $suitMembersApiService->post($route, $body)['data'];
        $user->shop()->update(['client_secret_members' => $response['hash']]);
    }

    public function getBodyEnable(User $user): array
    {
        return [
            'name' => $user->name,
            'email' => $user->email,
            'document' => $user->document_number
        ];
    }

    private function treatErrorsToShow(Throwable $th, string $fragments = ''): RedirectResponse
    {
        $message = $th->getMessage();
        $data = json_decode($message, true);
        $errorMessage['errors'] = $data['message'] ?? 'Erro desconhecido';
        $errors = $data['errors'] ?? [];

        $redirect = back()
            ->withErrors(!empty($errors) ? $errors : $errorMessage)
            ->withInput();

        if (!empty($fragments)) {
            $redirect = $redirect->withFragment($fragments);
        }

        return $redirect;
    }

    public function updateCourse(UpdateCourseRequest $request, int $courseId): RedirectResponse
    {
        try {
            $route = 'courses/' . $courseId;
            $response = $this->suitMembersApiService->put($route, $request->toArray());
            return redirect()->route('dashboard.members.edit', ['courseId' => $response['data']['id']])
                ->with('success', 'curso editado com sucesso');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function editModule(int $courseId, int $moduleId): View | RedirectResponse
    {
        try {
            $course = $this->getCourseData($courseId)['data'];
            $product = Product::where('client_product_uuid', $course['productRef'])->firstOrFail();
            $route = 'modules/' . $moduleId;
            $module = $this->suitMembersApiService->get($route)['data'];
            return view('dashboard.members.content.edit-module', compact('course', 'product', 'module'));
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function updateModule(UpdateModuleRequest $request, int $moduleId): RedirectResponse
    {
        try {
            $route = 'modules/' . $moduleId;
            $response = $this->suitMembersApiService->put($route, $request->toArray())['data'];
            return $this->redirectModule($response['courseId'], $response)
                ->with('success', 'Módulo editado com sucesso');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function editLesson(int $courseId, int $lessonId): View | RedirectResponse
    {
        try {
            $routeCourse = 'producer/courses/' . $courseId;
            $course = $this->suitMembersApiService->get($routeCourse)['data'];
            $route = 'producer/lessons/' . $lessonId;
            $lesson = $this->suitMembersApiService->get($route)['data'];
            return view('dashboard.members.content.edit-lesson', compact('course',  'lesson'));
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function updateLesson(UpdateLessonRequest $request, int $lessonId): RedirectResponse
    {
        try {
            $routeUpdate = 'lessons/' . $lessonId;
            $response = $this->suitMembersApiService->put($routeUpdate, $request->toArray())['data'];
            $this->checkAddAttachments($request->toArray(), $lessonId);
            $courseId = $request->input('courseId');
            return $this->redirectLesson($courseId, $response)
                ->with('success', 'Aula editada com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    private function checkAddAttachments(array $requestData, int $lessonId): void
    {
        if (empty($requestData['Attachments'])) {
            return;
        }

        $route = 'lessons/' . $lessonId . '/complement';
        $this->suitMembersApiService->post($route, $requestData);
    }

    public function deleteLessonComplement(int $complementId): void
    {
        try {
            $route = 'lesson/complement/' . $complementId;
            $this->suitMembersApiService->delete($route);
        } catch (\Throwable $th) {
            Log::channel('members')->error(
                'Erro ao deletar na anexo.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'MemberController.deleteLessonComplement',
                    'route' => $route,
                ]
            );
            throw $th;
        }
    }

    public function editLessonQuiz(int $courseId, int $lessonId): View | RedirectResponse
    {
        try {
            $route = 'producer/courses/' . $courseId;
            $course = $this->suitMembersApiService->get($route)['data'];
            $route = 'lessons/quiz/' . $lessonId;
            $lesson = $this->suitMembersApiService->get($route)['data'];
            return view('dashboard.members.content.edit-quiz', compact('course', 'lesson'));
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function updateQuiz(UpdateQuizRequest $request, int  $lessonId): RedirectResponse
    {
        try {
            $route = 'lessons/' . $lessonId;
            $response = $this->suitMembersApiService->put($route, $request->toArray())['data'];
            $this->checkUpdateQuiz($request->toArray());
            $this->checkAddQuiz($request->toArray());
            $this->checkAddAttachments($request->toArray(), $lessonId);
            $courseId = $request->input('courseId');
            return $this->redirectLesson($courseId, $response)
                ->with('success', 'Quiz editado com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    private function checkUpdateQuiz(array $requestData): void
    {
        $quizUpdate = $requestData['Quizzes']['update'];

        foreach ($quizUpdate as $quiz) {
            $route = 'quiz/' . $quiz['quizId'];
            $this->suitMembersApiService->put($route, $quiz);
        }
    }

    private function checkAddQuiz(array $requestData): void
    {
        $quizAdd = $requestData['Quizzes']['add'];

        if (empty($quizAdd)) {
            return;
        }

        $route = 'quiz';

        foreach ($quizAdd as $quiz) {
            $this->suitMembersApiService->post($route, $quiz);
        }
    }

    public function deleteQuiz(int $quizId): void
    {
        try {
            $route = 'quiz/' . $quizId;
            $this->suitMembersApiService->delete($route);
        } catch (\Throwable $th) {
            Log::channel('members')->error(
                'Erro ao deletar quiz na api de membros.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'MemberController.deleteQuiz',
                    'route' => $route,
                ]
            );
            throw $th;
        }
    }

    public function redirectMembers(): Redirector|RedirectResponse
    {
        $token = $this->getTokenProducer();
        $routeFront = config('services.members.urlFront') . '/login?token=' . $token;
        return redirect($routeFront);
    }

    private function getTokenProducer(): string
    {
        $secretMember = auth()->user()->shop()->client_secret_members;
        return $this->suitMembersApiService->authApi($secretMember, 'producer');
    }

    public function redirectMembersCourse(int $courseId): Redirector|RedirectResponse
    {
        $course = $this->getCourseData($courseId)['data'];
        $linkCourse = $this->suitMembersApiService->get('courses/' . $course['id'] . '/link')['data']['link'] . '/login';
        $token = $this->getTokenProducer();
        $urlParam = "?token=" . $token . "&redirect=courses/{$course['id']}/{$course['slug']}";
        $routeFront = $linkCourse . $urlParam;
        return redirect($routeFront);
    }

    public function settingsCourse(Request $request, int $courseId): View | RedirectResponse
    {
        try {
            $course = $this->getCourseData($courseId)['data'];
            $routeDomain = 'courses/' . $courseId . '/domain';
            $domains = $this->suitMembersApiService->get($routeDomain)['data'] ?? [];
            $routeComment = 'producer/courses/' . $courseId . '/comments/config';
            $comments = $this->suitMembersApiService->get($routeComment)['data'] ?? [];
            $linkDomain = $this->suitMembersApiService->get('courses/' . $courseId . '/link')['data']['link'];
            $linkCourse = $linkDomain . "courses/{$courseId}/{$course['slug']}";
            $routeCustomization = 'courses/' . $courseId . '/customization';
            $customization = $this->suitMembersApiService->get($routeCustomization)['data'] ?? [];
            $relatedCourses = $this->getRelatedCourses($request, $courseId);
            return view(
                'dashboard.members.setting',
                compact(
                    'course',
                    'domains',
                    'comments',
                    'linkCourse',
                    'customization',
                    'relatedCourses'
                )
            );
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function getProductsAvailableCreateRelation(int $courseId): Collection
    {
        $course = $this->getCourseData($courseId)['data'];
        $routeProductsAlreadyCreated = 'relation/productRef/courses/' . $courseId;
        $products = $this->suitMembersApiService->get($routeProductsAlreadyCreated)['data'] ?? [];
        $ownerId = Product::where('client_product_uuid', $course['productRef'])
            ->join('shops', 'shop_id', 'shops.id')
            ->whereNull('parent_id')
            ->select('owner_id')
            ->first()->owner_id;

        $products[] = $course['productRef'];
        $shopId = auth::user()->shop()->id;

        return Product::query()
            ->leftJoin('affiliates', function ($join) use ($ownerId) {
                $join->on('affiliates.product_id', '=', 'products.id')
                    ->where('affiliates.user_id', '=', $ownerId)
                    ->whereNull('affiliates.deleted_at');
            })
            ->leftJoin('productables', function ($join) {
                $join->on('productables.product_id', '=', 'products.id')
                    ->where('productables.productable_type', '=', 'App\\Models\\Coproducer');
            })
            ->leftJoin('coproducers', function ($join) use ($ownerId, $shopId) {
                $join->on('coproducers.id', '=', 'productables.productable_id')
                    ->where(function ($q) use ($ownerId, $shopId) {
                        $q->where('coproducers.user_id', '=', $ownerId)
                            ->orWhere('coproducers.user_id', '=', $shopId);
                    })
                    ->whereNull('coproducers.deleted_at');
            })
            ->where(function ($query) use ($ownerId, $shopId) {
                $query->whereHas('shop', function ($q) {
                    $q->where('owner_id', Auth::id());
                })
                    ->orWhere(function ($q) {
                        $q->where('affiliates.situation', 'ACTIVE');
                    })
                    ->orWhere(function ($q) use ($shopId) {
                        $q->where('coproducers.user_id', $shopId);
                    });
            })
            ->where('products.situation', 'PUBLISHED')
            ->whereNull('products.parent_id')
            ->whereNotIn('client_product_uuid', $products)
            ->where('type_id', ProductTypesEnum::SUIT_MEMBERS->value)
            ->groupBy('products.id')
            ->select([
                'products.id as product_id',
                'client_product_uuid',
                'products.name as name',
                'affiliates.id as affiliate_id',
                'coproducers.id as coproducer_id',
                'coproducers.user_id as coproducer_user'
            ])
            ->get();
    }

    public function addDomainSuitMembers(AddDomainRequest $request, int $courseId): RedirectResponse
    {
        try {
            $route = 'courses/' . $courseId . '/domain';
            $this->suitMembersApiService->post($route, $request->toArray());
            return redirect()->route('dashboard.members.settingsCourse', ['courseId' => $courseId]);
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function deleteDomainSuitMembers(int $courseId): RedirectResponse
    {
        try {
            $route = 'courses/' . $courseId . '/domain';
            $this->suitMembersApiService->delete($route);
            return redirect()->route('dashboard.members.settingsCourse', ['courseId' => $courseId]);
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function addStudent(AddStudentRequest $request, int $courseId): RedirectResponse
    {
        try {
            $course = $this->getCourseData($courseId)['data'];
            $route = 'courses/' . $course['productRef'] . '/member';
            $tokenAdmin = config('services.members.token');
            $suitMembersApiService = new SuitMembersApiService(30, $tokenAdmin, 'admin');
            $suitMembersApiService->post($route, $request->toArray());
            return redirect()->route('dashboard.members.students', ['courseId' => $courseId])
                ->with('success',  'Convite enviado para o aluno');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function addManyStudents(AddManyStudentsRequest $request, int $courseId): RedirectResponse
    {
        $course = $this->getCourseData($courseId)['data'];
        $route = 'courses/' . $course['productRef'] . '/many/member';
        $tokenAdmin = config('services.members.token');
        $suitMembersApiService = new SuitMembersApiService(30, $tokenAdmin, 'admin');
        $suitMembersApiService->post($route, $request->toArray());
        return redirect()->route('dashboard.members.students', ['courseId' => $courseId])
            ->with('success',  'Processando lista');
    }

    public function addConfigComment(AddConfigCommentRequest $request, int $courseId): RedirectResponse
    {
        try {
            $route = 'producer/courses/' . $courseId . '/comments/config';
            $this->suitMembersApiService->post($route, $request->toArray());
            return redirect()->route('dashboard.members.settingsCourse', ['courseId' => $courseId])
                ->with('success',  'Configuração de comentários adicionada com sucesso')
                ->withFragment('tab=tab-comments');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function changeMemberStatus(ChangeMemberStatusRequest $request, int $courseId): RedirectResponse
    {
        try {
            $tokenAdmin = config('services.members.token');
            $suitMembersApiService = new SuitMembersApiService(30, $tokenAdmin, 'admin');
            $route = 'courses/' . $courseId . '/member/status';
            $suitMembersApiService->put($route, $request->toArray());
            return redirect()->route('dashboard.members.students', ['courseId' => $courseId])
                ->with('success', 'Status do usuário alterado com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function changeMemberModerator(ChangeMemberModeratorRequest $request, int $courseId): RedirectResponse
    {
        try {
            $route = 'courses/' . $courseId . '/member/moderators';
            $this->suitMembersApiService->put($route, $request->toArray());
            return redirect()->route('dashboard.members.students', ['courseId' => $courseId])
                ->with('success', 'Status de moderação alterado com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function customization(CustomizationRequest $request, int $courseId): RedirectResponse
    {
        try {
            $route = 'courses/' . $courseId . '/customization';
            $this->suitMembersApiService->post($route, $request->toArray());
            return redirect()->route('dashboard.members.settingsCourse', ['courseId' => $courseId])
                ->with('success', 'Personalização alterada com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function deleteModule(int $moduleId): RedirectResponse
    {
        try {
            $route = 'modules/' . $moduleId;
            $this->suitMembersApiService->delete($route);
            return redirect()->back()
                ->with('success', 'Módulo desativado com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function reactivateModule(int $moduleId): RedirectResponse
    {
        try {
            $route = 'modules/' . $moduleId . '/activate';
            $this->suitMembersApiService->put($route);
            return redirect()->back()
                ->with('success', 'Módulo reativado com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function deleteLesson(int $lessonId): RedirectResponse
    {
        try {
            $route = 'lessons/' . $lessonId;
            $this->suitMembersApiService->delete($route);
            return redirect()->back()
                ->with('success', 'Aula desativada com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function reactivateLesson(int $lessonId): RedirectResponse
    {
        try {
            $route = 'lessons/' . $lessonId . '/activate';
            $this->suitMembersApiService->put($route);
            return redirect()->back()
                ->with('success', 'Aula reativada com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function createClass(CreateClassRequest $request, int $courseId): RedirectResponse
    {
        try {
            $route = 'producer/courses/' . $courseId . '/class';
            $this->suitMembersApiService->post($route, $request->toArray());
            return redirect()->route('dashboard.members.classes', ['courseId' => $courseId])
                ->with('success', 'Turma criada com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function updateClass(UpdateClassRequest $request, int $courseId, int $classId): RedirectResponse
    {
        try {
            $route = 'producer/class/' . $classId;
            $this->suitMembersApiService->put($route, $request->toArray());
            return redirect()->route('dashboard.members.classes', ['courseId' => $courseId])
                ->with('success', 'Turma editada com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function checkCanAccessMembers(Request $request): array
    {
        $user = auth()->user();
        $tokenAdmin = config('services.members.token');
        $suitMembersApiService = new SuitMembersApiService(30, $tokenAdmin, 'admin');
        $route = 'has-course';
        $response = $suitMembersApiService->get($route, ['email' => $user->email])['data'];

        if (!$response['hasCourse']) {
            return ['hasAccess' => false];
        }

        if (is_null($user->shop()->client_secret_members)) {
            $this->createProduceSuitMembers($user);
        }

        return ['hasAccess' => true];
    }

    public function createTrack(CreateTrackRequest $request): RedirectResponse
    {
        try {
            $data = $request->toArray();
            $route = 'tracks';
            $track = $this->suitMembersApiService->post($route, $data)['data'];
            return redirect()->back()
                ->with('openTrackId', $track['id'])
                ->with('success', 'Trilha criada com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function addTrackContent(int $trackId, Request $request): View
    {
        $openCourseId = $request->query('openCourseId');
        $route = 'producer/tracks/' . $trackId;
        $track = $this->suitMembersApiService->get($route)['data'];
        $course = $this->getCourseData($track['courseId'])['data'];
        return view('dashboard.members.tracks.add-track-content', compact('track', 'course', 'openCourseId'));
    }

    public function editTrack(Request $request)
    {
        try {
            $route = 'tracks/' . $request->input('trackId');
            $body = ['name' => $request->input('nameTrack')];
            $this->suitMembersApiService->put($route, $body)['data'];
            return redirect()->back()
                ->with('openTrackId', $request->input('trackId'))
                ->with('success', 'Trilha atualizada com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function addCourseTrack(int $trackId): View
    {
        $route = 'producer/tracks/' . $trackId;
        $track = $this->suitMembersApiService->get($route)['data'];
        $course = $this->getCourseData($track['courseId'])['data'];
        return view('dashboard.members.tracks.add-course-track', compact('track', 'course'));
    }

    public function createCourseTrack(CreateCourseRequest $request): RedirectResponse
    {
        try {
            $route = 'courses';
            $course = $this->suitMembersApiService->post($route, $request->toArray())['data'];
            return redirect()->route(
                'dashboard.members.addTrackContent',
                [
                    'trackId' => $course['trackId'],
                    'openCourseId' => $course['id']
                ]
            )
                ->with('success', 'Curso criado com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function editCourseTrack(int $courseId): RedirectResponse | View
    {
        try {
            $route = 'producer/courses/' . $courseId;
            $course = $this->suitMembersApiService->get($route)['data'];
            return view('dashboard.members.tracks.edit-course-track', compact('course'));
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function updateCourseTrack(UpdateCourseTrackRequest $request, int $courseId): RedirectResponse
    {
        try {
            $route = 'courses/' . $courseId;
            $response = $this->suitMembersApiService->put($route, $request->toArray())['data'];
            return redirect()->route(
                'dashboard.members.addTrackContent',
                [
                    'trackId' => $response['trackId'],
                    'openCourseId' => $response['id']
                ]
            )
                ->with('success', 'Curso editado com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function addModuleTrack(int $courseId, Request $request): View
    {
        $openModuleId = $request->query('openModuleId');
        $route = 'producer/courses/' . $courseId;
        $course = $this->suitMembersApiService->get($route)['data'];
        return view('dashboard.members.tracks.add-module-content', compact('course', 'openModuleId'));
    }

    public function deleteCourseTrack(int $courseId): RedirectResponse
    {
        try {
            $route = 'courses/' . $courseId;
            $this->suitMembersApiService->delete($route);
            return redirect()->back()
                ->with('success', 'Curso desativado com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function activateCourseTrack(int $courseId): RedirectResponse
    {
        try {
            $route = 'courses/' . $courseId . '/activate';
            $this->suitMembersApiService->put($route);
            return redirect()->back()
                ->with('success', 'Curso ativado com sucesso.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function addClass(int $courseId): View|RedirectResponse
    {
        try {
            $course = $this->getCourseData($courseId)['data'];
            $route = 'courses/ ' . $courseId . '/offers';
            $product = Product::where('client_product_uuid', $course['productRef'])->firstOrFail();
            $offers = $this->suitMembersApiService->get($route)['data'] ?? [];
            $offersIds = array_column($offers, 'offerId');
            $offers = Product::where('parent_id', $product->id)
                ->whereNotIn('id', $offersIds)
                ->get();
            $routeContent = $course['hasTrack'] ? 'courses/' . $courseId . '/tracks' : 'course/' . $courseId . '/modules';
            $contents = $this->suitMembersApiService->get($routeContent)['data'];
            return view('dashboard.members.classes.create', compact('course', 'offers', 'contents'));
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function editClass(int $courseId, int $classId): View|RedirectResponse
    {
        try {
            $course = $this->getCourseData($courseId)['data'];
            $route = 'courses/ ' . $courseId . '/offers';
            $product = Product::where('client_product_uuid', $course['productRef'])->firstOrFail();
            $offers = $this->suitMembersApiService->get($route)['data'] ?? [];
            $offersIds = array_column($offers, 'offerId');
            $offers = Product::where('parent_id', $product->id)
                ->whereNotIn('id', $offersIds)
                ->get();
            $routeContent = $course['hasTrack'] ? 'courses/' . $courseId . '/tracks' : 'course/' . $courseId . '/modules';
            $contents = $this->suitMembersApiService->get($routeContent)['data'];
            $routeClass = 'producer/class/' . $classId;
            $class = $this->suitMembersApiService->get($routeClass)['data'] ?? [];
            $offerIdsUsed = !empty($class['Offers'])
                ? array_column($class['Offers'], 'offerId')
                : [];
            $offersUsed = Product::where('parent_id', $product->id)
                ->whereIn('id', $offerIdsUsed)
                ->get();
            return view('dashboard.members.classes.edit', compact('course', 'offers', 'contents', 'class', 'offersUsed'));
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function toggleStatusClass(ToggleClassRequest $request, int $classId): RedirectResponse
    {
        try {
            $route = 'producer/class/' . $classId . '/status';
            $this->suitMembersApiService->put($route, $request->toArray());
            $statusMessage = $request->status ? 'Ativada' : 'Desativada';
            return redirect()->back()
                ->with('success', 'Turma ' . $statusMessage . ' com sucesso');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function getOffersCreateRelation(string $productRef): Collection
    {
        $parentProduct = Product::where('client_product_uuid', $productRef)
            ->whereNull('parent_id')
            ->select('id')->first();

        return Product::where('parent_id', $parentProduct->id)
            ->where('situation', SituationProductEnum::PUBLISHED->value)
            ->where('status', StatusEnum::ACTIVE->name)
            ->select('id', 'name')
            ->get();
    }

    public function createCourseRelation(CreateCourseRelationRequest $request, int $courseId): RedirectResponse
    {
        try {
            $route = 'producer/relation';
            $body = $this->getExtraDataCreateRelation($request->toArray(), $courseId);
            $this->suitMembersApiService->post($route, $body);
            return redirect()->back()
                ->withFragment('tab=tab-related-courses')
                ->with('success', 'Produto adicionado as recomendações');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    private function getExtraDataCreateRelation(array $body, int $courseId): array
    {
        $routeCourseParent = 'courses/ref/' . $body['productRef'];
        $courseParent = $this->suitMembersApiService->get($routeCourseParent)['data'];
        $course = $this->getCourseData($courseId)['data'];
        $body['code'] = $this->getCodeData($body, $courseId);
        $body['producerId'] = $course['producerId'];
        $body['courseId'] = $courseParent['id'];
        return $body;
    }

    private function getCodeData(array $body, int $courseId): string
    {
        $routeUserDetail = 'courses/' . $courseId . '/user';
        $response = $this->suitMembersApiService->get($routeUserDetail)['data'];
        $userId = User::where('email', $response['email'])
            ->select('id')
            ->firstOrFail()->id;

        $productData = Product::query()
            ->leftJoin('affiliates', function ($join) use ($userId) {
                $join->on('affiliates.product_id', '=', 'products.parent_id')
                    ->where('affiliates.user_id', '=', $userId);
            })
            ->where('products.id', $body['offerId'])
            ->select(
                'products.code',
                'affiliates.code AS affiliateCode'
            )->first();

        if (is_null($productData->affiliateCode)) {
            return $productData->code;
        }

        return $productData->code . '?afflt=' . $productData->affiliateCode;
    }

    public function getRelatedCourses(Request $request, int $courseId): array
    {
        $route = 'producer/relation/courses/' . $courseId;
        $courses = $this->suitMembersApiService->get($route, $request->query());

        if (!array_key_exists('data', $courses)) {
            $courses['data'] = [];
        }

        $productUuid = array_unique(array_column($courses['data'], 'productRef'));
        $productOfferId = array_unique(array_column($courses['data'], 'offerId'));
        $products = Product::whereIn('client_product_uuid', $productUuid)->get()->keyBy('client_product_uuid');
        $productsOffers = Product::whereIn('id', $productOfferId)->get()->keyBy('id');
        foreach ($courses['data'] as &$course) {
            $productUuid = $course['productRef'];
            $offerId = $course['offerId'];
            $course['productName'] = $products[$productUuid]->name ?? null;
            $course['offerName'] = $productsOffers[$offerId]->name ?? null;
        }

        $courses['pagination'] = $this->getPaginator($courses['pagination'], $request->fullUrl());
        return $courses;
    }

    public function updateCourseRelation(Request $request, int $courseRelationId): RedirectResponse
    {
        try {
            $route = 'producer/relation/' . $courseRelationId;
            $body = ['offerId' => $request->toArray()['offerId']];
            $this->suitMembersApiService->put($route, $body);
            return redirect()->back()
                ->withFragment('tab=tab-related-courses')
                ->with('success', 'Oferta atualizada.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }

    public function deleteCourseRelation(int $courseRelationId): RedirectResponse
    {
        try {
            $route = 'producer/relation/' . $courseRelationId;
            $this->suitMembersApiService->delete($route);
            return redirect()->back()
                ->withFragment('tab=tab-related-courses')
                ->with('success', 'Recomendação deletada.');
        } catch (\Throwable $th) {
            return $this->treatErrorsToShow($th);
        }
    }
}
