<?php

namespace App\Http\Controllers;

use App\Models\JoinApplication;
use App\Models\JoinPageSetting;
use App\Models\JoinPosition;
use App\Services\JoinUsPageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class JoinUsController extends Controller
{
    public function index(Request $request): View
    {
        $locale = $request->query('lang', 'zh-cn');

        return view('join-us.index', (new JoinUsPageService($locale))->indexData(
            categorySlug: $request->query('category'),
        ));
    }

    public function apply(Request $request): JsonResponse
    {
        $locale = $request->query('lang', 'zh-cn');
        $settings = JoinPageSetting::forLocale($locale);

        $validator = Validator::make($request->all(), [
            'position_id' => ['nullable', 'integer', 'exists:join_positions,id'],
            'name' => ['required', 'string', 'max:80'],
            'phone' => ['required', 'string', 'regex:/^1\d{10}$/'],
            'email' => ['nullable', 'email', 'max:120'],
            'city' => ['nullable', 'string', 'max:120'],
            'message' => ['nullable', 'string', 'max:2000'],
            'resume' => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx'],
        ], [
            'name.required' => '请输入姓名',
            'phone.required' => '请输入联系电话',
            'phone.regex' => '请输入正确的 11 位手机号码',
            'email.email' => '电子邮箱格式不正确',
            'resume.required' => '请上传简历文件',
            'resume.mimes' => '简历仅支持 PDF、Word 格式',
            'resume.max' => '简历文件不能超过 10MB',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $data = $validator->validated();
        $position = null;
        if (! empty($data['position_id'])) {
            $position = JoinPosition::query()
                ->forLocale($locale)
                ->active()
                ->find($data['position_id']);
        }

        $file = $request->file('resume');
        $path = $file->store('join-us/resumes/'.date('Ym'), 'public');

        JoinApplication::query()->create([
            'position_id' => $position?->id,
            'position_title' => $position?->title ?? $request->input('position_title'),
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'city' => $data['city'] ?? null,
            'message' => $data['message'] ?? null,
            'resume_path' => $path,
            'resume_original_name' => $file->getClientOriginalName(),
            'status' => JoinApplication::STATUS_PENDING,
            'ip' => $request->ip(),
            'locale' => $locale,
        ]);

        return response()->json([
            'message' => $settings->form_success_message ?: '简历已提交，我们会尽快与您联系。',
        ]);
    }
}
