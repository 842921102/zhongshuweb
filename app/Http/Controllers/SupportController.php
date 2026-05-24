<?php

namespace App\Http\Controllers;

use App\Models\SupportServiceRequest;
use App\Models\SupportVideo;
use App\Services\SupportPageService;
use App\Support\ChinaRegions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

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
        $video->increment('play_count');

        return response()->json([
            'play_count' => $video->fresh()->play_count,
        ]);
    }
}
