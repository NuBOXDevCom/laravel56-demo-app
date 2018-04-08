<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image;
use App\Models\User;
use App\Repositories\ImageRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ImageController extends Controller
{
    /**
     * @var ImageRepository
     */
    protected $repository;

    /**
     * @param ImageRepository $repository
     */
    public function __construct(ImageRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('images.create');
    }

    /**
     * @param  Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'image' => 'required|image|max:2000',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:255',
        ]);
        $this->repository->store($request);
        return back()->with('ok', __("L'image a bien été enregistrée"));
    }

    /**
     * @param Image $image
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Image $image): RedirectResponse
    {
        $this->authorize('delete', $image);
        $image->delete();
        return back();
    }

    /**
     * @param string $slug
     * @return Factory|View
     */
    public function category(string $slug)
    {
        $category = Category::whereSlug($slug)->firstorFail();
        $images = $this->repository->getImagesForCategory($slug);
        return view('home', compact('category', 'images'));
    }

    /**
     * @param User $user
     * @return Factory|View
     */
    public function user(User $user)
    {
        $images = $this->repository->getImagesForUser($user->id);
        return view('home', compact('user', 'images'));
    }
}
