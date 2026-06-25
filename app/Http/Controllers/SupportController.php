<?php

namespace App\Http\Controllers;

use App\Models\SupportDocument;
use App\Models\SupportServiceRequest;
use App\Models\SupportVideo;
use App\Services\SupportPageService;
use App\Support\ChinaRegions;
use App\Support\MediaUrl;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SupportController extends Controller
{
    public function index(Request $request): View
    {
        $locale = current_lang();
        $docType = $request->query('doc_type');

        return view('support.index', (new SupportPageService($locale))->indexData($docType));
    }

    public function region(Request $request): JsonResponse
    {
        $parent = (string) $request->query('parent', '');

        try {
            $items = $parent === ''
                ? ChinaRegions::provinces()
                : ChinaRegions::children($parent);

            return response()->json(['data' => $items]);
        } catch (\Throwable) {
            return response()->json(['message' => '地区数据加载失败'], 500);
        }
    }

    public function submit(Request $request): JsonResponse
    {
        $locale = current_lang();
        $pageData = SupportPageService::pageData($locale);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:80'],
            'phone' => ['required', 'string', 'regex:/^1\d{10}$/'],
            'email' => ['nullable', 'email', 'max:120'],
            'region' => ['required', 'string', 'max:200'],
            'topic' => ['required', 'string', 'max:80'],
            'province_code' => ['nullable', 'string', 'max:20'],
            'city_code' => ['nullable', 'string', 'max:20'],
            'district_code' => ['nullable', 'string', 'max:20'],
        ], [
            'name.required' => $pageData['validationNameRequired'],
            'phone.required' => $pageData['validationPhoneRequired'],
            'phone.regex' => $pageData['validationPhoneInvalid'],
            'email.email' => $pageData['validationEmailInvalid'],
            'region.required' => $pageData['validationRegionRequired'],
            'topic.required' => $pageData['validationTopicRequired'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 422);
        }

        SupportServiceRequest::query()->create([
            ...$validator->validated(),
            'ip' => $request->ip(),
            'status' => 'pending',
            'locale' => $locale,
        ]);

        return response()->json([
            'message' => $pageData['submitSuccess'],
        ]);
    }

    public function videoPlay(Request $request, SupportVideo $video): JsonResponse
    {
        abort_unless(
            $video->is_active && $video->locale === current_lang(),
            404
        );

        $video->increment('play_count');

        return response()->json([
            'play_count' => $video->fresh()->play_count,
        ]);
    }

    public function downloadDocument(SupportDocument $document): StreamedResponse|RedirectResponse
    {
        abort_unless(
            $document->is_active
            && filled($document->file_path)
            && $document->locale === current_lang(),
            404
        );

        $path = MediaUrl::normalizeStoredPath($document->file_path);

        if ($path === null) {
            abort(404);
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return redirect()->away($path);
        }

        $disk = MediaUrl::resolveStorageDisk($path);

        if ($disk === null) {
            abort(404);
        }

        $filename = self::documentDownloadFilename($document);

        return Storage::disk($disk)->download($path, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    private static function documentDownloadFilename(SupportDocument $document): string
    {
        $title = Str::of($document->title)->replaceMatches('/[\\\\\\/:*?"<>|]/', '')->trim();
        $ascii = Str::ascii((string) $title);
        $base = filled($ascii) ? $ascii : 'document-'.$document->id;

        return Str::endsWith(strtolower($base), '.pdf') ? $base : $base.'.pdf';
    }
}
