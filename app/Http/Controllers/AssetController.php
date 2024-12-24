<?php

namespace App\Http\Controllers;

use App\Actions\Asset\DeleteAction;
use App\Actions\Asset\IndexAction;
use App\Actions\Asset\StoreAction;
use App\Actions\Asset\UpdateAction;
use App\Http\Requests\Asset\StoreRequest;
use App\Http\Requests\Asset\UpdateRequest;
use App\Models\Asset;
use Illuminate\Http\JsonResponse;

class AssetController extends Controller
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
     * @param Asset $asset
     * @param UpdateAction $action
     *
     * @return SuccessResponseBuilder|JsonResponse
     */
    public function update(UpdateRequest $request, Asset $asset, UpdateAction $action)
    {
        return ($action)($asset->id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteAction $action
     *
     * @return SuccessResponseBuilder|JsonResponse
     */
    public function destroy($asset, DeleteAction $action)
    {
        return ($action)($asset);
    }
}
