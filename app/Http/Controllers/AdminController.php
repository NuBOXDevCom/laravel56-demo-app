<?php

namespace App\Http\Controllers;

use App\Repositories\ImageRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * @var ImageRepository
     */
    protected $repository;

    /**
     * AdminController constructor.
     * @param ImageRepository $repository
     */
    public function __construct(ImageRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        $orphans = $this->repository->getOrphans();
        $countOrphans = \count($orphans);
        return view('maintenance.index', compact('orphans', 'countOrphans'));
    }

    /**
     * @return JsonResponse
     */
    public function destroy(): JsonResponse
    {
        $this->repository->destroyOrphans();
        return response()->json();
    }
}