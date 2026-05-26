@php
    use App\Support\GeoMapPosition;

    $layout = $settings->normalizedGlobalLayout();
    $stats = $layout['stats'] ?? [];
    $markers = $layout['markers'] ?? [];
    $facilities = $layout['facilities'] ?? [];
    $customMap = filled($layout['map_image'] ?? null) ? media_url($layout['map_image']) : null;
    $revealIndex = 0;
@endphp

@if(!$customMap)
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/d3@7/dist/d3.min.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/topojson-client@3/dist/topojson-client.min.js" defer></script>
        <script src="{{ asset('js/company-world-map.js') }}" defer></script>
    @endpush
@endif

<section class="about-section about-section--global about-global" data-about-global aria-label="{{ $layout['title'] }}">
    <div class="site-shell">
        <div data-global-reveal data-global-order="{{ $revealIndex++ }}">
            @include('company.partials.section-title', [
                'title' => $layout['title'],
            ])
        </div>
        @if(count($stats))
            <div class="about-global__stats">
                @foreach($stats as $stat)
                    <div class="about-global__stat" data-global-reveal data-global-order="{{ $revealIndex++ }}">
                        <strong>{{ $stat['value'] ?? '' }}</strong>
                        <span>{{ $stat['label'] ?? '' }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        @if(count($markers) || $customMap)
            <div class="about-global__map-wrap" data-global-reveal data-global-order="{{ $revealIndex++ }}">
                <div class="about-global__map" role="img" aria-label="全球业务分布地图">
                    <div class="about-global__map-canvas">
                        @if($customMap)
                            <img class="about-global__map-bg" src="{{ $customMap }}" alt="" loading="lazy" decoding="async">
                        @else
                            <svg
                                class="about-global__map-svg"
                                data-about-global-map
                                data-topo-url="{{ asset('data/world-countries-110m.json') }}"
                                data-map-width="1100"
                                data-map-height="550"
                                viewBox="0 0 1100 550"
                                preserveAspectRatio="xMidYMid meet"
                                aria-hidden="true"
                            >
                                <defs>
                                    <clipPath id="about-global-map-clip">
                                        <rect width="1100" height="550" rx="0"/>
                                    </clipPath>
                                </defs>
                                <g class="about-global__land" clip-path="url(#about-global-map-clip)"></g>
                            </svg>
                            <img
                                class="about-global__map-fallback about-global__map-bg"
                                src="{{ asset('images/company/world-map-simple.svg') }}"
                                alt=""
                                loading="lazy"
                                decoding="async"
                                hidden
                            >
                        @endif
                    </div>

                    @if(count($markers))
                        <div class="about-global__markers">
                            @foreach($markers as $marker)
                                @php
                                    $pos = GeoMapPosition::resolveMarker($marker);
                                    $side = ($marker['label_side'] ?? 'right') === 'left' ? 'left' : 'right';
                                    $hasGeo = isset($marker['lat'], $marker['lon']) && is_numeric($marker['lat']) && is_numeric($marker['lon']);
                                    $markerStyle = $hasGeo ? '' : '--marker-x: '.$pos['x'].'%; --marker-y: '.$pos['y'].'%;';
                                @endphp
                                <div class="about-global__marker about-global__marker--{{ $side }}"
                                     @if($markerStyle) style="{{ $markerStyle }}" @endif
                                     @if($hasGeo) data-marker-lat="{{ $marker['lat'] }}" data-marker-lon="{{ $marker['lon'] }}" @endif
                                     data-global-reveal
                                     data-global-order="{{ $revealIndex++ }}">
                                    <span class="about-global__dot" aria-hidden="true"></span>
                                    <div class="about-global__marker-label">
                                        <strong>{{ $marker['name'] ?? '' }}</strong>
                                        @if(filled($marker['subtitle'] ?? null))
                                            <span>{{ $marker['subtitle'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if(count($facilities))
            <div class="about-global__facilities" data-global-reveal data-global-order="{{ $revealIndex++ }}">
                <div class="about-global__facilities-inner">
                    @foreach($facilities as $item)
                        <div class="about-global__facility" data-global-reveal data-global-order="{{ $revealIndex++ }}">
                            <strong class="about-global__facility-value">{{ $item['value'] ?? '' }}</strong>
                            <span class="about-global__facility-title">{{ $item['title'] ?? '' }}</span>
                            <p class="about-global__facility-locs">{{ $item['locations'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
