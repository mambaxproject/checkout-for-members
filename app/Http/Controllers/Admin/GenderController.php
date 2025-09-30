<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyGenderRequest;
use App\Http\Requests\StoreGenderRequest;
use App\Http\Requests\UpdateGenderRequest;
use App\Models\Gender;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GenderController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('gender_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $genders = Gender::all();

        return view('admin.genders.index', compact('genders'));
    }

    public function create()
    {
        abort_if(Gate::denies('gender_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.genders.create');
    }

    public function store(StoreGenderRequest $request)
    {
        $gender = Gender::create($request->all());

        return redirect()->route('admin.genders.index');
    }

    public function edit(Gender $gender)
    {
        abort_if(Gate::denies('gender_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.genders.edit', compact('gender'));
    }

    public function update(UpdateGenderRequest $request, Gender $gender)
    {
        $gender->update($request->all());

        return redirect()->route('admin.genders.index');
    }

    public function show(Gender $gender)
    {
        abort_if(Gate::denies('gender_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.genders.show', compact('gender'));
    }

    public function destroy(Gender $gender)
    {
        abort_if(Gate::denies('gender_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $gender->delete();

        return back();
    }

    public function massDestroy(MassDestroyGenderRequest $request)
    {
        $genders = Gender::find(request('ids'));

        foreach ($genders as $gender) {
            $gender->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
