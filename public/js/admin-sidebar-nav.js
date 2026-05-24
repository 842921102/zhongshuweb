/**
 * 一级菜单：默认全部折叠；手风琴模式（同时只展开一个）；
 * 工作台与分组一级菜单逻辑一致；点击一级菜单后展开并跳转到该组第一个二级菜单。
 */
(function () {
    function getSidebarStore() {
        if (typeof Alpine === 'undefined') {
            return null;
        }

        return Alpine.store('sidebar');
    }

    function getAllGroupLabels() {
        return Array.from(
            document.querySelectorAll(
                '.fi-main-sidebar .fi-sidebar-group[data-group-label]',
            ),
        )
            .map((group) => group.dataset.groupLabel)
            .filter(Boolean);
    }

    function isTopLevelStandaloneItem(item) {
        const group = item?.closest('.fi-sidebar-group');

        return Boolean(group && !group.querySelector('.fi-sidebar-group-btn'));
    }

    function collapseAllGroups() {
        const store = getSidebarStore();

        if (!store) {
            return;
        }

        getAllGroupLabels().forEach((groupLabel) => {
            if (!store.groupIsCollapsed(groupLabel)) {
                store.collapseGroup(groupLabel);
            }
        });
    }

    function expandGroupExclusive(label) {
        const store = getSidebarStore();

        if (!store || !label) {
            return;
        }

        getAllGroupLabels().forEach((groupLabel) => {
            if (groupLabel === label) {
                if (store.groupIsCollapsed(groupLabel)) {
                    store.toggleCollapsedGroup(groupLabel);
                }

                return;
            }

            if (!store.groupIsCollapsed(groupLabel)) {
                store.collapseGroup(groupLabel);
            }
        });
    }

    function syncActiveMenuState() {
        const activeItem = document.querySelector(
            '.fi-main-sidebar .fi-sidebar-item.fi-active',
        );

        if (!activeItem) {
            return;
        }

        if (isTopLevelStandaloneItem(activeItem)) {
            collapseAllGroups();

            return;
        }

        const group = activeItem.closest('.fi-sidebar-group');

        if (group?.dataset.groupLabel) {
            expandGroupExclusive(group.dataset.groupLabel);
        }
    }

    function handleGroupClick(event) {
        if (event.target.closest('.fi-sidebar-group-collapse-btn')) {
            return;
        }

        const groupBtn = event.target.closest('.fi-sidebar-group-btn');

        if (!groupBtn) {
            return;
        }

        const group = groupBtn.closest('.fi-sidebar-group');

        if (!group || !group.classList.contains('fi-collapsible')) {
            return;
        }

        const label = group.dataset.groupLabel;
        const firstLink = group.querySelector(
            '.fi-sidebar-group-items a.fi-sidebar-item-btn[href]',
        );

        event.preventDefault();
        event.stopPropagation();

        expandGroupExclusive(label);

        if (firstLink) {
            firstLink.click();
        }
    }

    function handleStandaloneItemClick(event) {
        const itemBtn = event.target.closest('.fi-sidebar-item-btn');

        if (!itemBtn) {
            return;
        }

        const item = itemBtn.closest('.fi-sidebar-item');

        if (!item || !isTopLevelStandaloneItem(item)) {
            return;
        }

        collapseAllGroups();
    }

    function handleChevronClick(event) {
        const chevron = event.target.closest('.fi-sidebar-group-collapse-btn');

        if (!chevron) {
            return;
        }

        const group = chevron.closest('.fi-sidebar-group');

        if (!group) {
            return;
        }

        const label = group.dataset.groupLabel;
        const store = getSidebarStore();

        if (!store || store.groupIsCollapsed(label)) {
            event.preventDefault();
            event.stopImmediatePropagation();
            expandGroupExclusive(label);
        }
    }

    document.addEventListener('click', handleGroupClick, true);
    document.addEventListener('click', handleStandaloneItemClick, true);
    document.addEventListener('click', handleChevronClick, true);
    document.addEventListener('DOMContentLoaded', syncActiveMenuState);
    document.addEventListener('livewire:navigated', syncActiveMenuState);
})();
