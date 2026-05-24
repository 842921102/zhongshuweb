<x-filament-widgets::widget>
    <x-filament::section
        heading="最近提交"
        description="简历投递、产品咨询、售后申请的最新记录"
    >
        <div class="admin-submission-channels">
            @foreach ($this->getChannels() as $channel)
                <a href="{{ $channel['url'] }}" class="admin-submission-channel">
                    <span class="admin-submission-channel__label">{{ $channel['label'] }}</span>
                    <span class="admin-submission-channel__pending">{{ $channel['pending'] }}</span>
                    <span class="admin-submission-channel__meta">待处理 / 共 {{ $channel['total'] }} 条</span>
                </a>
            @endforeach
        </div>

        <div class="admin-submission-table-wrap">
            <table class="admin-submission-table">
                <thead>
                    <tr>
                        <th>类型</th>
                        <th>姓名</th>
                        <th>电话</th>
                        <th>摘要</th>
                        <th>状态</th>
                        <th>提交时间</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->getSubmissions() as $row)
                        <tr>
                            <td>
                                <span class="admin-submission-type admin-submission-type--{{ $row['type'] }}">
                                    {{ $row['type_label'] }}
                                </span>
                            </td>
                            <td>{{ $row['name'] }}</td>
                            <td>{{ $row['phone'] }}</td>
                            <td class="admin-submission-summary" title="{{ $row['summary'] }}">{{ $row['summary'] }}</td>
                            <td>
                                <span @class([
                                    'admin-submission-status',
                                    'is-pending' => ($row['status'] ?? '') === 'pending',
                                ])>{{ $row['status_label'] }}</span>
                            </td>
                            <td>{{ $row['created_at']?->format('Y-m-d H:i') }}</td>
                            <td>
                                @if($row['url'])
                                    <a href="{{ $row['url'] }}" class="admin-submission-link">查看</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="admin-submission-empty">暂无前台提交记录</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
