<?php

namespace App\Repositories;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;

class ImageRepository
{
    /**
     * @param Request $request
     */
    public function store(Request $request): void
    {
        $path = Storage::disk('images')->put('', $request->file('image'));
        $image = InterventionImage::make($request->file('image'))->widen(500);
        Storage::disk('thumbs')->put($path, $image->encode());
        $image = new Image;
        $image->description = $request->description;
        $image->category_id = $request->category_id;
        $image->name = $path;
        $image->user_id = auth()->id();
        $image->save();
    }

    /**
     * @param string $slug
     * @return mixed
     */
    public function getImagesForCategory(string $slug)
    {
        return Image::latestWithUser()->whereHas('category', function ($query) use ($slug) {
            $query->whereSlug($slug);
        })->paginate(config('app.pagination'));
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getImagesForUser(int $id)
    {
        return Image::latestWithUser()->whereHas('user', function ($query) use ($id) {
            $query->whereId($id);
        })->paginate(config('app.pagination'));
    }

    /**
     * @return static
     */
    public function getOrphans()
    {
        $files = collect(Storage::disk('images')->files());
        $images = Image::select('name')->get()->pluck('name');
        return $files->diff($images);
    }

    public function destroyOrphans(): void
    {
        $orphans = $this->getOrphans();
        /** @var array $orphans */
        foreach ($orphans as $orphan) {
            Storage::disk('images')->delete($orphan);
            Storage::disk('thumbs')->delete($orphan);
        }
    }
}