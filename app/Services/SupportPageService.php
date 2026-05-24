<?php

namespace App\Services;

use App\Models\SupportDocument;
use App\Models\SupportPageSetting;
use App\Models\SupportVideo;
use App\Support\ChinaRegions;
use Illuminate\Support\Collection;

class SupportPageService
{
    public function __construct(
        public string $locale = 'zh-cn',
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function indexData(?string $docType = null): array
    {
        $settings = SupportPageSetting::forLocale($this->locale);

        $documents = SupportDocument::query()
            ->forLocale($this->locale)
            ->active()
            ->when(filled($docType) && $docType !== 'all', fn ($q) => $q->where('category', $docType))
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        $categories = $settings->categoryFilters();
        $activeFilter = filled($docType) ? $docType : 'all';

        return array_merge((new SiteLayoutService($this->locale))->shared(), [
            'settings' => $settings,
            'documents' => $documents,
            'categories' => $categories,
            'activeDocFilter' => $activeFilter,
            'videos' => SupportVideo::query()
                ->forLocale($this->locale)
                ->active()
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->get(),
            'provinces' => ChinaRegions::provinces(),
            'pageData' => static::pageData($this->locale),
        ]);
    }

    /** @return array<string, string> */
    public static function pageData(string $locale = 'zh-cn'): array
    {
        if ($locale === 'en-us') {
            return [
                'docTitleFallback' => 'Document',
                'videoTitleFallback' => 'Video Tutorial',
                'validationNameRequired' => 'Please enter your name',
                'validationPhoneRequired' => 'Please enter your phone number',
                'validationRegionRequired' => 'Please select your region',
                'validationTopicRequired' => 'Please select a topic',
                'validationPhoneInvalid' => 'Please enter a valid 11-digit mobile number',
                'validationEmailInvalid' => 'Please enter a valid email address',
                'submitting' => 'Submitting...',
                'submitError' => 'Submission failed. Please try again later.',
                'submitSuccess' => 'Your request has been submitted. Our support team will contact you soon.',
                'regionLoadError' => 'Failed to load region data',
                'regionCityLoadError' => 'Failed to load city data. Please try again later.',
                'regionDistrictLoadError' => 'Failed to load district data. Please try again later.',
            ];
        }

        return [
            'docTitleFallback' => '文档',
            'videoTitleFallback' => '教学视频',
            'validationNameRequired' => '请输入姓名',
            'validationPhoneRequired' => '请输入联系电话',
            'validationRegionRequired' => '请选择所在地区',
            'validationTopicRequired' => '请选择咨询主题',
            'validationPhoneInvalid' => '请输入正确的 11 位手机号码',
            'validationEmailInvalid' => '电子邮箱格式不正确，请重新输入',
            'submitting' => '正在提交，请稍候...',
            'submitError' => '提交失败，请稍后重试',
            'submitSuccess' => '申请已提交，售后专员将尽快与您联系。',
            'regionLoadError' => '地区数据加载失败',
            'regionCityLoadError' => '城市数据加载失败，请稍后重试',
            'regionDistrictLoadError' => '区县数据加载失败，请稍后重试',
        ];
    }
}
