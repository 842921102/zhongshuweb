<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductConsultation;
use App\Models\ProductPageSetting;
use App\Services\ProductPageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $locale = current_lang();

        return view('products.index', (new ProductPageService($locale))->indexData(
            $request->query('category')
        ));
    }

    public function show(Request $request, string $product): View
    {
        $locale = current_lang();
        $data = (new ProductPageService($locale))->showData($product);

        if ($data === null) {
            abort(404);
        }

        return view('products.show', $data);
    }

    public function consult(Request $request, string $product): JsonResponse
    {
        $locale = current_lang();
        $data = (new ProductPageService($locale))->showData($product);

        if ($data === null) {
            return response()->json(['message' => '产品不存在'], 404);
        }

        /** @var Product $productModel */
        $productModel = $data['product'];

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:80'],
            'phone' => ['required', 'string', 'regex:/^1\d{10}$/'],
            'email' => ['nullable', 'email', 'max:120'],
            'city' => ['nullable', 'string', 'max:120'],
            'topic' => ['nullable', 'string', 'max:80'],
            'remark' => ['nullable', 'string', 'max:2000'],
        ], [
            'name.required' => '请输入姓名',
            'phone.required' => '请输入联系电话',
            'phone.regex' => '请输入正确的 11 位手机号码',
            'email.email' => '电子邮箱格式不正确',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        ProductConsultation::query()->create([
            ...$validator->validated(),
            'product_id' => $productModel->id,
            'product_name' => $productModel->name,
            'ip' => $request->ip(),
            'status' => 'pending',
            'locale' => $locale,
        ]);

        $labels = ProductPageSetting::forLocale($locale)->mergedDetailLabels();

        return response()->json([
            'message' => $labels['form_success'] ?? '咨询信息已提交，我们会尽快与您联系。',
        ]);
    }
}
