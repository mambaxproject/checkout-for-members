<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCategoryProductRequest;
use App\Http\Requests\StoreCategoryProductRequest;
use App\Http\Requests\UpdateCategoryProductRequest;
use App\Models\CategoryProduct;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class CategoryProductController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('category_product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categoryProducts = CategoryProduct::with(['media'])->get();

        return view('admin.categoryProducts.index', compact('categoryProducts'));
    }

    public function create()
    {
        abort_if(Gate::denies('category_product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.categoryProducts.create');
    }

    public function store(StoreCategoryProductRequest $request)
    {
        $categoryProduct = CategoryProduct::create($request->all());

        if ($request->input('photo', false)) {
            $categoryProduct->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
        }

        if ($request->input('cover_photo', false)) {
            $categoryProduct->addMedia(storage_path('tmp/uploads/' . basename($request->input('cover_photo'))))->toMediaCollection('cover_photo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $categoryProduct->id]);
        }

        return redirect()->route('admin.category-products.index');
    }

    public function edit(CategoryProduct $categoryProduct)
    {
        abort_if(Gate::denies('category_product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.categoryProducts.edit', compact('categoryProduct'));
    }

    public function update(UpdateCategoryProductRequest $request, CategoryProduct $categoryProduct)
    {
        $categoryProduct->update($request->all());

        if ($request->input('photo', false)) {
            if (! $categoryProduct->photo || $request->input('photo') !== $categoryProduct->photo->file_name) {
                if ($categoryProduct->photo) {
                    $categoryProduct->photo->delete();
                }
                $categoryProduct->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
            }
        } elseif ($categoryProduct->photo) {
            $categoryProduct->photo->delete();
        }

        if ($request->input('cover_photo', false)) {
            if (! $categoryProduct->cover_photo || $request->input('cover_photo') !== $categoryProduct->cover_photo->file_name) {
                if ($categoryProduct->cover_photo) {
                    $categoryProduct->cover_photo->delete();
                }
                $categoryProduct->addMedia(storage_path('tmp/uploads/' . basename($request->input('cover_photo'))))->toMediaCollection('cover_photo');
            }
        } elseif ($categoryProduct->cover_photo) {
            $categoryProduct->cover_photo->delete();
        }

        return redirect()->route('admin.category-products.index');
    }

    public function show(CategoryProduct $categoryProduct)
    {
        abort_if(Gate::denies('category_product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categoryProduct->load('categoryProducts');

        return view('admin.categoryProducts.show', compact('categoryProduct'));
    }

    public function destroy(CategoryProduct $categoryProduct)
    {
        abort_if(Gate::denies('category_product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categoryProduct->delete();

        return back();
    }

    public function massDestroy(MassDestroyCategoryProductRequest $request)
    {
        $categoryProducts = CategoryProduct::find(request('ids'));

        foreach ($categoryProducts as $categoryProduct) {
            $categoryProduct->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('category_product_create') && Gate::denies('category_product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new CategoryProduct();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
