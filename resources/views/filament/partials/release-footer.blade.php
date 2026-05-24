@php
    use App\Support\ReleaseInfo;

    $items = ReleaseInfo::items();
@endphp
<footer class="admin-release-footer {{ ReleaseInfo::environmentClass() }}" aria-label="系统版本信息">
    <div class="admin-release-footer__inner">
        <span class="admin-release-footer__badge">CMS {{ ReleaseInfo::version() }}</span>
        <span class="admin-release-footer__env">{{ ReleaseInfo::environmentLabel() }}</span>
        @if(ReleaseInfo::label())
            <span class="admin-release-footer__sep" aria-hidden="true">·</span>
            <span class="admin-release-footer__label" title="{{ ReleaseInfo::label() }}">{{ ReleaseInfo::label() }}</span>
        @endif
        @if(ReleaseInfo::publishedAtDisplay())
            <span class="admin-release-footer__sep" aria-hidden="true">·</span>
            <span class="admin-release-footer__time">发布 {{ ReleaseInfo::publishedAtDisplay() }}</span>
        @endif
    </div>
    <details class="admin-release-footer__details">
        <summary>版本详情</summary>
        <dl class="admin-release-footer__dl">
            @foreach($items as $item)
                <div>
                    <dt>{{ $item['key'] }}</dt>
                    <dd>{{ $item['value'] }}</dd>
                </div>
            @endforeach
        </dl>
    </details>
</footer>
