<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMediaRequest;
use App\Http\Requests\Admin\UpdateMediaRequest;
use App\Models\Media;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {
            $media = Media::with('mediable')->latest()->paginate(10);
            return $this->successResponse($media, 'لیست مدیاها با موفقیت دریافت شد');
        } catch (\Throwable $e) {
            return $this->errorResponse('خطا در دریافت لیست مدیاها', $e->getMessage(), 500);
        }
    }

    public function store(StoreMediaRequest $request)
    {
        try {
            $data = $request->validated();
            $folder = $data['folder'] ?? 'media';

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store("uploads/{$folder}", 'public');
                $data['path'] = str_replace('public/', '', $path);
            }

            $media = Media::create([
                'path' => $data['path'],
                'type' => $data['type'],
                'alt' => $data['alt'] ?? null,
                'is_main' => $data['is_main'] ?? false,
                'mediable_id' => $data['mediable_id'] ?? null,
                'mediable_type' => $data['mediable_type'] ?? null,
            ]);

            return $this->successResponse($media, 'مدیا با موفقیت آپلود شد', 201);

        } catch (\Throwable $e) {
            return $this->errorResponse('خطا در آپلود مدیا', $e->getMessage(), 500);
        }
    }

    public function show(Media $media)
    {
        try {
            $media->load('mediable');
            return $this->successResponse($media, 'جزئیات مدیا با موفقیت دریافت شد');
        } catch (\Throwable $e) {
            return $this->errorResponse('خطا در دریافت جزئیات مدیا', $e->getMessage(), 500);
        }
    }

    public function update(UpdateMediaRequest $request, Media $media)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('file')) {
                if ($media->path) {
                    Storage::disk('public')->delete($media->path);
                }

                $path = $request->file('file')->store('uploads/media', 'public');
                $data['path'] = str_replace('public/', '', $path);
            }

            $media->update($data);

            return $this->successResponse($media, 'مدیا با موفقیت بروزرسانی شد');
        } catch (\Throwable $e) {
            return $this->errorResponse('خطا در بروزرسانی مدیا', $e->getMessage(), 500);
        }
    }

    public function destroy(Media $media)
    {
        try {
            if ($media->path) {
                Storage::disk('public')->delete($media->path);
            }

            $media->delete();

            return $this->successResponse(null, 'مدیا با موفقیت حذف شد');
        } catch (\Throwable $e) {
            return $this->errorResponse('خطا در حذف مدیا', $e->getMessage(), 500);
        }
    }

    public function sync(\Illuminate\Http\Request $request)
    {
        try {
            $validated = $request->validate([
                'mediable_id' => 'required|integer',
                'mediable_type' => 'required|string',
                'media' => 'required|array',
                'media.*.id' => 'required|integer|exists:media,id',
                'media.*.is_main' => 'nullable|boolean',
                'clear_previous' => 'boolean',
            ]);

            $mediableClass = $validated['mediable_type'];

            if (!class_exists($mediableClass)) {
                return $this->errorResponse('مدل مشخص‌شده معتبر نیست.');
            }

            $model = $mediableClass::find($validated['mediable_id']);
            if (!$model) {
                return $this->errorResponse('مدل مورد نظر یافت نشد.');
            }

            if (!empty($validated['clear_previous']) && $validated['clear_previous'] === true) {
                $model->media()->update(['mediable_id' => null, 'mediable_type' => null]);
            }

            foreach ($validated['media'] as $item) {
                Media::where('id', $item['id'])->update([
                    'mediable_id' => $validated['mediable_id'],
                    'mediable_type' => $validated['mediable_type'],
                    'is_main' => $item['is_main'] ?? false,
                ]);
            }

            $media = $model->media()->get();

            return $this->successResponse($media, 'مدیاها با موفقیت به مدل متصل شدند.');
        } catch (\Throwable $e) {
            return $this->errorResponse('خطا در سینک مدیاها', $e->getMessage(), 500);
        }
    }
}
