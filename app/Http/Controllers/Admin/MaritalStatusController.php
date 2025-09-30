<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyMaritalStatusRequest;
use App\Http\Requests\StoreMaritalStatusRequest;
use App\Http\Requests\UpdateMaritalStatusRequest;
use App\Models\MaritalStatus;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaritalStatusController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('marital_status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $maritalStatuses = MaritalStatus::all();

        return view('admin.maritalStatuses.index', compact('maritalStatuses'));
    }

    public function create()
    {
        abort_if(Gate::denies('marital_status_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.maritalStatuses.create');
    }

    public function store(StoreMaritalStatusRequest $request)
    {
        $maritalStatus = MaritalStatus::create($request->all());

        return redirect()->route('admin.marital-statuses.index');
    }

    public function edit(MaritalStatus $maritalStatus)
    {
        abort_if(Gate::denies('marital_status_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.maritalStatuses.edit', compact('maritalStatus'));
    }

    public function update(UpdateMaritalStatusRequest $request, MaritalStatus $maritalStatus)
    {
        $maritalStatus->update($request->all());

        return redirect()->route('admin.marital-statuses.index');
    }

    public function show(MaritalStatus $maritalStatus)
    {
        abort_if(Gate::denies('marital_status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.maritalStatuses.show', compact('maritalStatus'));
    }

    public function destroy(MaritalStatus $maritalStatus)
    {
        abort_if(Gate::denies('marital_status_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $maritalStatus->delete();

        return back();
    }

    public function massDestroy(MassDestroyMaritalStatusRequest $request)
    {
        $maritalStatuses = MaritalStatus::find(request('ids'));

        foreach ($maritalStatuses as $maritalStatus) {
            $maritalStatus->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
