<?php

namespace App\Http\Controllers;

use App\Actions\Template\DeleteAction;
use App\Actions\Template\ImportAction;
use App\Actions\Template\IndexAction;
use App\Actions\Template\JobsAction;
use App\Actions\Template\ShowAction;
use App\Actions\Template\StoreAction;
use App\Actions\Template\UpdateAction;
use App\Http\Requests\Template\ImportRequest;
use App\Http\Requests\Template\StoreRequest;
use App\Http\Requests\Template\UpdateRequest;
use App\Models\Template;
use Illuminate\Http\JsonResponse;

class TemplateController extends Controller
{
    /**
     * @param IndexAction $action
     *
     * @throws RepositoryException
     *
     * @return JsonResponse
     */
    public function index(IndexAction $action): JsonResponse
    {
        return ($action)();
    }

    /**
     * @param ShowAction $action
     *
     * @return JsonResponse
     */
    public function show(ShowAction $action, int $id): JsonResponse
    {
        return ($action)($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @param StoreAction $action
     *
     * @return SuccessResponseBuilder|JsonResponse
     */
    public function store(StoreRequest $request, StoreAction $action)
    {
        return ($action)($request->validated());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Template $template
     * @param UpdateAction $action
     *
     * @return SuccessResponseBuilder|JsonResponse
     */
    public function update(UpdateRequest $request, Template $template, UpdateAction $action)
    {
        return ($action)($template->id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Template $template
     * @param DeleteAction $action
     *
     * @return SuccessResponseBuilder|JsonResponse
     */
    public function destroy($template, DeleteAction $action)
    {
        return ($action)($template);
    }

    /**
     * Import the specified resource from storage.
     *
     * @param ImportRequest $request
     * @param ImportAction $action
     *
     * @return SuccessResponseBuilder|JsonResponse
     */
    public function import(ImportRequest $request, ImportAction $action)
    {
        return ($action)($request->validated());
    }

    public function jobs(JobsAction $action)
    {
        return ($action)();
    }

    public function downloadCsv($filename)
    {
        $path = storage_path('app/' . $filename);
        if (file_exists($path)) {
            return response()->download($path);
        }
        
        return response()->json(['error' => 'File not found'], 404);
    }
}
