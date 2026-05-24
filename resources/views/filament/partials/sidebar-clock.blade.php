<div
    class="admin-sidebar-clock"
    x-data="{
        time: '',
        date: '',
        weekdays: ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'],
        tick() {
            const now = new Date()
            this.time = now.toLocaleTimeString('zh-CN', { hour12: false })
            const y = now.getFullYear()
            const m = String(now.getMonth() + 1).padStart(2, '0')
            const d = String(now.getDate()).padStart(2, '0')
            this.date = `${y}年${m}月${d}日 ${this.weekdays[now.getDay()]}`
        },
    }"
    x-init="tick(); setInterval(() => tick(), 1000)"
>
    <div class="admin-sidebar-clock-time" x-text="time"></div>
    <div class="admin-sidebar-clock-date" x-text="date"></div>
</div>
