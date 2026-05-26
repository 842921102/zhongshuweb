<?php

namespace App\Support;

class IndustrySolutionDetailData
{
    /**
     * @param  array<string, mixed>|null  $detailData
     * @return array<string, mixed>|null
     */
    public static function prepareForForm(?array $detailData): ?array
    {
        if (! is_array($detailData)) {
            return $detailData;
        }

        $detailData['hero'] = self::prepareHeroForForm($detailData['hero'] ?? []);
        $detailData['scenes'] = self::prepareScenesForForm($detailData['scenes'] ?? []);

        return $detailData;
    }

    /**
     * @param  array<string, mixed>|null  $detailData
     * @return array<string, mixed>|null
     */
    /**
     * @param  array<string, mixed>|null  $detailData
     * @param  array<string, mixed>|null  $existingDetailData  编辑时库内已有数据，用于在未提交 slides_gallery 时保留轮播图
     * @return array<string, mixed>|null
     */
    public static function normalizeForSave(?array $detailData, ?array $existingDetailData = null): ?array
    {
        if (! is_array($detailData)) {
            return $detailData;
        }

        $existingDetailData = is_array($existingDetailData) ? $existingDetailData : [];

        $detailData['hero'] = self::normalizeHeroForSave($detailData['hero'] ?? []);
        $detailData['scenes'] = self::normalizeScenesForSave(
            $detailData['scenes'] ?? [],
            is_array($existingDetailData['scenes'] ?? null) ? $existingDetailData['scenes'] : [],
        );

        return $detailData;
    }

    /**
     * @param  array<string, mixed>  $hero
     * @return array<string, mixed>
     */
    protected static function prepareHeroForForm(array $hero): array
    {
        $slides = is_array($hero['slides'] ?? null) ? $hero['slides'] : [];
        $gallery = [];

        foreach ($slides as $slide) {
            if (! is_array($slide)) {
                continue;
            }
            $image = $slide['image_pc'] ?? $slide['image'] ?? null;
            if (filled($image)) {
                $gallery[] = $image;
            }
        }

        $hero['slides_gallery'] = $gallery;

        return $hero;
    }

    /**
     * @param  array<string, mixed>  $hero
     * @return array<string, mixed>
     */
    protected static function normalizeHeroForSave(array $hero): array
    {
        $gallery = self::normalizeGalleryPaths($hero['slides_gallery'] ?? null);
        $legacy = is_array($hero['slides'] ?? null) ? $hero['slides'] : [];
        $labelsByImage = self::labelsIndexedByImage($legacy, 'image_pc', 'image');

        if ($gallery !== []) {
            $hero['slides'] = array_map(
                fn (string $image): array => [
                    'image_pc' => $image,
                    'image_mobile' => null,
                    'video_url' => null,
                    'label' => $labelsByImage[$image] ?? '',
                ],
                $gallery
            );
        }

        unset($hero['slides_gallery']);

        return $hero;
    }

    /**
     * @param  list<mixed>  $scenes
     * @return list<mixed>
     */
    protected static function prepareScenesForForm(array $scenes): array
    {
        foreach ($scenes as $index => $scene) {
            if (! is_array($scene)) {
                continue;
            }

            $slides = is_array($scene['slides'] ?? null) ? $scene['slides'] : [];
            $gallery = [];

            foreach ($slides as $slide) {
                if (! is_array($slide)) {
                    continue;
                }
                $image = $slide['image'] ?? null;
                if (filled($image)) {
                    $gallery[] = $image;
                }
            }

            $scene['slides_gallery'] = $gallery;
            $scene['slide_labels_lines'] = implode(
                "\n",
                array_map(
                    fn (array $slide): string => trim((string) ($slide['label'] ?? '')),
                    array_filter($slides, fn ($slide): bool => is_array($slide))
                )
            );

            $scenes[$index] = $scene;
        }

        return $scenes;
    }

    /**
     * @param  list<mixed>  $scenes
     * @return list<mixed>
     */
    /**
     * @param  list<mixed>  $scenes
     * @param  list<mixed>  $existingScenes
     * @return list<mixed>
     */
    protected static function normalizeScenesForSave(array $scenes, array $existingScenes = []): array
    {
        foreach ($scenes as $index => $scene) {
            if (! is_array($scene)) {
                continue;
            }

            $gallery = self::normalizeGalleryPaths($scene['slides_gallery'] ?? null);
            $legacy = is_array($scene['slides'] ?? null) ? $scene['slides'] : [];
            $existingScene = is_array($existingScenes[$index] ?? null) ? $existingScenes[$index] : [];
            $existingSlides = is_array($existingScene['slides'] ?? null) ? $existingScene['slides'] : [];
            $labelsByImage = self::labelsIndexedByImage(
                array_merge($legacy, $existingSlides),
                'image'
            );
            $labelLines = self::parseLabelLines($scene['slide_labels_lines'] ?? null);

            if ($gallery !== []) {
                $slides = [];
                foreach ($gallery as $i => $image) {
                    $slides[] = [
                        'image' => $image,
                        'label' => $labelLines[$i] ?? $labelsByImage[$image] ?? '',
                    ];
                }
                $scene['slides'] = $slides;
            } elseif ($legacy !== []) {
                $scene['slides'] = self::applySlideLabelLines($legacy, $labelLines);
            } elseif ($existingSlides !== []) {
                $scene['slides'] = self::applySlideLabelLines($existingSlides, $labelLines);
            } else {
                $scene['slides'] = [];
            }

            if (is_array($scene['products'] ?? null)) {
                foreach ($scene['products'] as $pi => $product) {
                    if (! is_array($product)) {
                        continue;
                    }
                    $product['image'] = MediaUrl::normalizeStoredPath($product['image'] ?? null);
                    $scene['products'][$pi] = $product;
                }
            }

            unset($scene['slides_gallery'], $scene['slide_labels_lines']);
            $scenes[$index] = $scene;
        }

        return $scenes;
    }

    /**
     * @param  list<mixed>  $slides
     * @param  list<string>  $labelLines
     * @return list<array{image: string, label: string}>
     */
    protected static function applySlideLabelLines(array $slides, array $labelLines): array
    {
        $normalized = [];

        foreach ($slides as $i => $slide) {
            if (! is_array($slide)) {
                continue;
            }
            $image = MediaUrl::normalizeStoredPath($slide['image'] ?? null);
            if (blank($image)) {
                continue;
            }
            $normalized[] = [
                'image' => $image,
                'label' => $labelLines[$i] ?? trim((string) ($slide['label'] ?? '')),
            ];
        }

        return $normalized;
    }

    /**
     * @return list<string>
     */
    protected static function normalizeGalleryPaths(mixed $gallery): array
    {
        if (! is_array($gallery)) {
            $single = MediaUrl::normalizeStoredPath($gallery);

            return $single !== null ? [$single] : [];
        }

        $paths = [];
        foreach ($gallery as $path) {
            $normalized = MediaUrl::normalizeStoredPath($path);
            if ($normalized !== null) {
                $paths[] = $normalized;
            }
        }

        return array_values($paths);
    }

    /**
     * @param  list<mixed>  $slides
     * @return array<string, string>
     */
    protected static function labelsIndexedByImage(array $slides, string ...$imageKeys): array
    {
        $labels = [];

        foreach ($slides as $slide) {
            if (! is_array($slide)) {
                continue;
            }
            $image = null;
            foreach ($imageKeys as $key) {
                if (filled($slide[$key] ?? null)) {
                    $image = $slide[$key];
                    break;
                }
            }
            if (filled($image)) {
                $labels[$image] = trim((string) ($slide['label'] ?? ''));
            }
        }

        return $labels;
    }

    /**
     * @return list<string>
     */
    protected static function parseLabelLines(mixed $text): array
    {
        if (! is_string($text) || trim($text) === '') {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n/', $text) ?: [];

        return array_values(array_map(
            fn (string $line): string => trim(preg_replace('/^[•\-\*]\s*/u', '', trim($line)) ?? trim($line)),
            array_filter($lines, fn (string $line): bool => trim($line) !== '')
        ));
    }
}
