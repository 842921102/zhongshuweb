(function () {
  var header = document.querySelector('[data-header]');
  var navToggle = document.querySelector('[data-nav-toggle]');
  var nav = document.querySelector('[data-nav]');
  var navLinks = Array.prototype.slice.call(document.querySelectorAll('.site-header__nav .site-header__nav-link'));
  var productTrigger = document.querySelector('[data-product-trigger]');
  var productDropdown = document.querySelector('[data-product-dropdown]');
  var productWrap = productTrigger ? productTrigger.closest('.site-header__nav-link--product-wrap') : null;
  var productDataElement = document.getElementById('siteHeaderProductData');
  var productData = {};
  try {
    if (productDataElement) {
      productData = JSON.parse(productDataElement.textContent || '{}') || {};
    }
  } catch (e) {
    productData = {};
  }
  var productCategories = Array.isArray(productData.categories) ? productData.categories : [];
  var productChildren = productData && typeof productData.children === 'object' && productData.children ? productData.children : {};
  var productLabels = productData.labels || {};
  var productCardsWrap = productDropdown ? productDropdown.querySelector('[data-product-cards]') : null;
  var productTitle = productDropdown ? productDropdown.querySelector('[data-product-title]') : null;
  var productViewAll = productDropdown ? productDropdown.querySelector('[data-product-viewall]') : null;
  var productCategoryLinks = productDropdown
    ? Array.prototype.slice.call(productDropdown.querySelectorAll('[data-product-category]'))
    : [];
  var searchToggle = document.querySelector('[data-search-toggle]');
  var searchPanel = document.querySelector('[data-search-panel]');
  var searchForm = document.querySelector('[data-search-form]');
  var searchInput = document.querySelector('[data-search-input]');
  var searchStatus = document.querySelector('[data-search-status]');
  var searchResults = document.querySelector('[data-search-results]');
  var langWrap = document.querySelector('[data-lang-wrap]');
  var langToggle = document.querySelector('[data-lang-toggle]');
  var langPanel = document.querySelector('[data-lang-panel]');
  var searchApiUrl = header ? (header.getAttribute('data-search-api') || '') : '';
  var searchEmptyMessage = header ? (header.getAttribute('data-search-empty') || '') : '';
  var searchMissMessage = header ? (header.getAttribute('data-search-miss') || '') : '';
  var socialQrTriggers = Array.prototype.slice.call(document.querySelectorAll('[data-social-qr-trigger]'));
  var socialQrPanel = document.querySelector('[data-social-qr-panel]');
  var socialQrImage = document.querySelector('[data-social-qr-image]');
  var activeSocialTrigger = null;

  function normalizePath(path) {
    return (path || '/').replace(/\/$/, '') || '/';
  }

  function routeKeyMatches(path, key) {
    key = String(key || '').trim();
    if (!key) {
      return false;
    }

    switch (key) {
      case 'home':
      case 'index':
        return path === '/';
      case 'product':
        return path === '/products' || path.indexOf('/products/') === 0;
      case 'case':
      case 'cases':
        return path === '/cases' || path.indexOf('/cases/') === 0;
      case 'about':
      case 'culture':
        return path === '/about';
      case 'news':
        return path === '/news' || path.indexOf('/news/') === 0;
      case 'support':
        return path === '/support';
      case 'joinus':
        return path === '/join-us' || path.indexOf('/join-us') === 0;
      default:
        return path.indexOf('/' + key) === 0;
    }
  }

  function initNavActiveState() {
    var path = normalizePath(window.location.pathname);
    document.querySelectorAll('.site-header__nav-link[data-route]').forEach(function (link) {
      var routes = (link.getAttribute('data-route') || '').split(',');
      var active = routes.some(function (key) {
        return routeKeyMatches(path, key);
      });
      link.classList.toggle('is-active', active);
      if (active) {
        link.setAttribute('aria-current', 'page');
      } else {
        link.removeAttribute('aria-current');
      }
    });
  }

  function setHeaderState() {
    if (!header) {
      return;
    }

    var solidTop = document.body.classList.contains('header-solid-top');
    if (solidTop || window.scrollY > 24) {
      header.classList.add('is-scrolled');
    } else {
      header.classList.remove('is-scrolled');
    }
  }

  function syncHeaderHoverState() {
    if (!header) {
      return;
    }
    if (window.innerWidth < 968) {
      header.classList.remove('is-hovering-nav');
      return;
    }
    var headerHovered = !!(header && header.matches(':hover'));
    var navHovered = !!(nav && nav.matches(':hover'));
    var dropdownHovered = !!(productDropdown && productDropdown.matches(':hover'));
    var navFocused = !!(nav && nav.contains(document.activeElement));
    var dropdownFocused = !!(productDropdown && productDropdown.contains(document.activeElement));
    var productOpen = !!(productWrap && productWrap.classList.contains('is-open'));
    var shouldHover = headerHovered || navHovered || dropdownHovered || navFocused || dropdownFocused || productOpen;
    header.classList.toggle('is-hovering-nav', shouldHover);
  }

  function toggleNav() {
    if (!navToggle || !nav) {
      return;
    }

    var expanded = navToggle.getAttribute('aria-expanded') === 'true';
    navToggle.setAttribute('aria-expanded', String(!expanded));
    nav.classList.toggle('is-open', !expanded);
    if (expanded) {
      closeProductDropdown();
    }
  }

  function closeNav() {
    if (!navToggle || !nav) {
      return;
    }

    navToggle.setAttribute('aria-expanded', 'false');
    nav.classList.remove('is-open');
    closeProductDropdown();
  }

  function shouldUseProductClickToggle() {
    // 移动端使用点击，桌面端使用 hover
    return window.innerWidth < 968;
  }

  function isProductDropdownOpen() {
    return !!(productWrap && productWrap.classList.contains('is-open') && !shouldUseProductClickToggle());
  }

  function getProductDropdownScrollTarget(target) {
    if (!target || !productDropdown || !productDropdown.contains(target)) {
      return null;
    }

    var menu = productDropdown.querySelector('.product-dropdown-menu');
    var content = productDropdown.querySelector('.product-dropdown-content');

    if (menu && menu.contains(target)) {
      return menu;
    }

    if (content && content.contains(target)) {
      return content;
    }

    return null;
  }

  function shouldAllowProductDropdownScroll(event) {
    var scrollTarget = getProductDropdownScrollTarget(event.target);
    if (!scrollTarget) {
      return false;
    }

    if (scrollTarget.scrollHeight <= scrollTarget.clientHeight + 1) {
      return false;
    }

    var deltaY = event.deltaY || 0;
    if (!deltaY) {
      return true;
    }

    var atTop = scrollTarget.scrollTop <= 0;
    var atBottom = scrollTarget.scrollTop + scrollTarget.clientHeight >= scrollTarget.scrollHeight - 1;

    if ((deltaY < 0 && atTop) || (deltaY > 0 && atBottom)) {
      return false;
    }

    return true;
  }

  function isEditableFocusTarget(target) {
    if (!target || !target.closest) {
      return false;
    }

    return !!target.closest('input, textarea, select, [contenteditable=""], [contenteditable="true"]');
  }

  function preventBackgroundScroll(event) {
    if (!isProductDropdownOpen()) {
      return;
    }

    if (shouldAllowProductDropdownScroll(event)) {
      return;
    }

    event.preventDefault();
  }

  function preventBackgroundScrollKeys(event) {
    if (!isProductDropdownOpen() || isEditableFocusTarget(event.target)) {
      return;
    }

    var blockedKeys = {
      ArrowUp: true,
      ArrowDown: true,
      PageUp: true,
      PageDown: true,
      Home: true,
      End: true,
      ' ': true,
      Spacebar: true
    };

    if (!blockedKeys[event.key]) {
      return;
    }

    event.preventDefault();
  }

  function setProductDropdownPageScrollLocked(locked) {
    if (shouldUseProductClickToggle()) {
      return;
    }

    document.documentElement.classList.toggle('is-product-dropdown-open', !!locked);
  }

  function bindProductDropdownScrollLock() {
    if (!productDropdown || !productWrap) {
      return;
    }

    document.addEventListener('wheel', preventBackgroundScroll, { passive: false });
    document.addEventListener('touchmove', preventBackgroundScroll, { passive: false });
    document.addEventListener('keydown', preventBackgroundScrollKeys, false);
  }

  function closeProductDropdown() {
    if (!productWrap || !productDropdown || !productTrigger) {
      return;
    }
    var wasOpen = productWrap.classList.contains('is-open');
    productWrap.classList.remove('is-open');
    productTrigger.setAttribute('aria-expanded', 'false');
    if (wasOpen) {
      setProductDropdownPageScrollLocked(false);
    }
    syncHeaderHoverState();
  }

  function openProductDropdown() {
    if (!productWrap || !productDropdown || !productTrigger) {
      return;
    }
    var wasOpen = productWrap.classList.contains('is-open');
    productWrap.classList.add('is-open');
    productTrigger.setAttribute('aria-expanded', 'true');
    if (!wasOpen) {
      setProductDropdownPageScrollLocked(true);
    }
    syncHeaderHoverState();
  }

  function escapeHtml(input) {
    return String(input || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  function renderProductCards(categoryKey) {
    if (!productCardsWrap) {
      return;
    }

    var items = Array.isArray(productChildren[categoryKey]) ? productChildren[categoryKey] : [];
    items = items.slice(0, 12);

    if (!items.length) {
      productCardsWrap.innerHTML = '<div class="product-dropdown-empty">' + escapeHtml(productLabels.catalog_empty || '') + '</div>';
      return;
    }

    productCardsWrap.innerHTML = items.map(function (item) {
      var hrefRaw = productTrigger ? buildCategoryUrl(productTrigger.getAttribute('href'), item.key || '') : '';
      var href = escapeHtml(hrefRaw || '#');
      var image = escapeHtml(item.cover_image || '/home/images/smart-equipment.jpg');
      var name = escapeHtml(item.label || '');
      var subtitle = escapeHtml(item.subtitle || '');

      return ''
        + '<a class="product-dropdown-card" href="' + href + '">'
        + '<div class="product-dropdown-card-media"><img src="' + image + '" alt="' + name + '" loading="lazy" decoding="async"></div>'
        + '<div class="product-dropdown-card-body">'
        + '<p class="product-dropdown-card-title">' + name + '</p>'
        + (subtitle ? '<p class="product-dropdown-card-subtitle">' + subtitle + '</p>' : '')
        + '</div>'
        + '</a>';
    }).join('');
  }

  function buildCategoryUrl(baseUrl, categoryKey) {
    var url = String(baseUrl || '');
    if (!url) {
      return '';
    }
    if (!categoryKey) {
      return url;
    }
    return url + (url.indexOf('?') !== -1 ? '&' : '?') + 'category=' + encodeURIComponent(categoryKey);
  }

  function setActiveProductCategory(categoryKey) {
    if (!categoryKey) {
      return;
    }
    productCategoryLinks.forEach(function (link) {
      var isActive = link.getAttribute('data-product-category') === categoryKey;
      link.classList.toggle('is-active', isActive);
    });
    if (productTitle) {
      var category = productCategories.find(function (item) {
        return item && item.key === categoryKey;
      });
      productTitle.textContent = category ? (category.label || '') : '';
    }
    if (productViewAll && productTrigger) {
      var baseUrl = productTrigger.getAttribute('href') || '';
      productViewAll.setAttribute('href', buildCategoryUrl(baseUrl, 'all:' + categoryKey));
    }
    renderProductCards(categoryKey);
  }

  function closeSearchPanel() {
    if (!searchToggle || !searchPanel) {
      return;
    }

    searchToggle.setAttribute('aria-expanded', 'false');
    searchPanel.hidden = true;
    clearSearchResults();
  }

  function toggleSearchPanel() {
    if (!searchToggle || !searchPanel) {
      return;
    }

    var expanded = searchToggle.getAttribute('aria-expanded') === 'true';
    searchToggle.setAttribute('aria-expanded', String(!expanded));
    searchPanel.hidden = expanded;

    if (!expanded && searchInput) {
      window.setTimeout(function () {
        searchInput.focus();
      }, 40);
    }
  }

  function closeLangPanel() {
    if (!langToggle || !langPanel) {
      return;
    }

    langToggle.setAttribute('aria-expanded', 'false');
    langPanel.hidden = true;
  }

  function toggleLangPanel() {
    if (!langToggle || !langPanel) {
      return;
    }

    var expanded = langToggle.getAttribute('aria-expanded') === 'true';
    langToggle.setAttribute('aria-expanded', String(!expanded));
    langPanel.hidden = expanded;
  }

  function findLocalNavigationMatch(keyword) {
    return navLinks.find(function (link) {
      var content = ((link.textContent || '') + ' ' + (link.getAttribute('data-search') || '')).toLowerCase();
      return content.indexOf(keyword) !== -1;
    }) || null;
  }

  function clearSearchResults() {
    if (!searchResults) {
      return;
    }
    searchResults.hidden = true;
    searchResults.innerHTML = '';
  }

  function renderSearchResults(items) {
    if (!searchResults) {
      return;
    }
    if (!Array.isArray(items) || !items.length) {
      clearSearchResults();
      return;
    }

    searchResults.innerHTML = items.map(function (item) {
      var href = escapeHtml(item && item.url ? item.url : '#');
      var title = escapeHtml(item && item.title ? item.title : '');
      var typeLabel = '';
      switch (item && item.type ? item.type : '') {
      case 'product':
        typeLabel = '产品';
        break;
      case 'product_category':
        typeLabel = '产品分类';
        break;
      case 'case':
        typeLabel = '案例';
        break;
      case 'news':
        typeLabel = '新闻';
        break;
      case 'nav':
        typeLabel = '页面';
        break;
      default:
        typeLabel = '';
      }
      typeLabel = escapeHtml(typeLabel);
      return ''
        + '<a class="site-header__search-result" href="' + href + '">'
        + '<span class="site-header__search-result-title">' + title + '</span>'
        + (typeLabel ? '<span class="site-header__search-result-type">' + typeLabel + '</span>' : '')
        + '</a>';
    }).join('');
    searchResults.hidden = false;
  }

  function requestSearchResults(keyword) {
    if (!searchApiUrl || typeof window.fetch !== 'function') {
      return Promise.resolve([]);
    }

    var requestUrl = searchApiUrl
      + (searchApiUrl.indexOf('?') === -1 ? '?' : '&')
      + 'keyword=' + encodeURIComponent(keyword)
      + '&limit=8';
    return window.fetch(requestUrl, {
      method: 'GET',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      },
      credentials: 'same-origin'
    }).then(function (response) {
      if (!response.ok) {
        return [];
      }
      return response.json();
    }).then(function (payload) {
      if (!payload || Number(payload.code) !== 1 || !payload.data || !Array.isArray(payload.data.items)) {
        return [];
      }
      return payload.data.items;
    }).catch(function () {
      return [];
    });
  }

  function handleSearchSubmit(event) {
    event.preventDefault();

    if (!searchInput || !searchStatus) {
      return;
    }

    var rawKeyword = searchInput.value.trim();
    if (!rawKeyword) {
      searchStatus.textContent = searchEmptyMessage;
      clearSearchResults();
      searchInput.focus();
      return;
    }
    var keyword = rawKeyword.toLowerCase();
    requestSearchResults(rawKeyword).then(function (items) {
      if (Array.isArray(items) && items.length) {
        renderSearchResults(items);
        searchStatus.textContent = '找到 ' + items.length + ' 个结果，请点击下方条目';
        return;
      }

      var matchedLink = findLocalNavigationMatch(keyword);
      if (!matchedLink) {
        searchStatus.textContent = searchMissMessage;
        clearSearchResults();
        return;
      }

      renderSearchResults([{
        title: matchedLink.textContent.trim(),
        type: 'nav',
        url: matchedLink.getAttribute('href')
      }]);
      searchStatus.textContent = '找到 1 个结果，请点击下方条目';
    });
  }

  function closeSocialQrPanel() {
    if (!socialQrPanel) {
      return;
    }
    socialQrPanel.hidden = true;
    socialQrPanel.style.removeProperty('left');
    socialQrPanel.style.removeProperty('--qr-arrow-offset');
    if (activeSocialTrigger) {
      activeSocialTrigger.classList.remove('is-active');
      activeSocialTrigger = null;
    }
  }

  function openSocialQrPanel(trigger, imageUrl, socialName) {
    if (!socialQrPanel || !socialQrImage) {
      return;
    }
    if (!imageUrl) {
      closeSocialQrPanel();
      return;
    }
    socialQrImage.setAttribute('src', imageUrl);
    socialQrImage.setAttribute('alt', (socialName || '二维码') + '二维码');
    socialQrPanel.hidden = false;
    socialQrTriggers.forEach(function (item) {
      item.classList.remove('is-active');
    });
    if (trigger) {
      trigger.classList.add('is-active');
      activeSocialTrigger = trigger;
    }

    var parent = socialQrPanel.offsetParent || socialQrPanel.parentElement;
    if (!parent || !trigger) {
      return;
    }
    var parentRect = parent.getBoundingClientRect();
    var triggerRect = trigger.getBoundingClientRect();
    var panelWidth = socialQrPanel.offsetWidth || 360;
    var triggerCenter = triggerRect.left + (triggerRect.width / 2) - parentRect.left;
    var left = triggerCenter - panelWidth / 2;
    var maxLeft = Math.max(0, parentRect.width - panelWidth);
    if (left < 0) {
      left = 0;
    } else if (left > maxLeft) {
      left = maxLeft;
    }
    var arrowOffset = triggerCenter - left;
    var minArrow = 24;
    var maxArrow = Math.max(minArrow, panelWidth - 24);
    if (arrowOffset < minArrow) {
      arrowOffset = minArrow;
    } else if (arrowOffset > maxArrow) {
      arrowOffset = maxArrow;
    }
    socialQrPanel.style.left = left + 'px';
    socialQrPanel.style.setProperty('--qr-arrow-offset', arrowOffset + 'px');
  }

  initNavActiveState();
  setHeaderState();
  window.addEventListener('scroll', setHeaderState, { passive: true });
  if (header) {
    header.addEventListener('mouseenter', syncHeaderHoverState);
    header.addEventListener('mouseleave', syncHeaderHoverState);
  }

  if (navToggle) {
    navToggle.addEventListener('click', toggleNav);
  }

  if (nav) {
    nav.addEventListener('click', function (event) {
      if (event.target.closest('a')) {
        closeNav();
      }
    });
    nav.addEventListener('mouseenter', syncHeaderHoverState);
    nav.addEventListener('mouseleave', syncHeaderHoverState);
    nav.addEventListener('focusin', syncHeaderHoverState);
    nav.addEventListener('focusout', function () {
      window.setTimeout(syncHeaderHoverState, 0);
    });
  }

  if (productTrigger && productDropdown && productWrap) {
    if (productCategoryLinks.length) {
      setActiveProductCategory(productCategoryLinks[0].getAttribute('data-product-category'));
      productCategoryLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
          event.preventDefault();
          setActiveProductCategory(link.getAttribute('data-product-category'));
        });
        link.addEventListener('focus', function () {
          setActiveProductCategory(link.getAttribute('data-product-category'));
        });
      });
    } else if (productCategories.length) {
      setActiveProductCategory(productCategories[0].key);
    }

    productWrap.addEventListener('mouseenter', function () {
      if (shouldUseProductClickToggle()) {
        return;
      }
      openProductDropdown();
      if (productCategoryLinks.length) {
        setActiveProductCategory(productCategoryLinks[0].getAttribute('data-product-category'));
      }
      closeSearchPanel();
      closeLangPanel();
    });

    productWrap.addEventListener('mouseleave', function () {
      if (shouldUseProductClickToggle()) {
        return;
      }
      closeProductDropdown();
    });

    productTrigger.addEventListener('click', function (event) {
      if (shouldUseProductClickToggle()) {
        closeProductDropdown();
        closeSearchPanel();
        closeLangPanel();
        closeNav();
        return;
      }
      event.preventDefault();
      event.stopPropagation();
      openProductDropdown();
      if (productCategoryLinks.length) {
        setActiveProductCategory(document.activeElement && document.activeElement.getAttribute
          ? (document.activeElement.getAttribute('data-product-category') || productCategoryLinks[0].getAttribute('data-product-category'))
          : productCategoryLinks[0].getAttribute('data-product-category'));
      }
      closeSearchPanel();
      closeLangPanel();
    });

    productDropdown.addEventListener('click', function (event) {
      event.stopPropagation();
      var anchor = event.target.closest('a');
      if (!anchor) {
        return;
      }
      if (
        anchor.classList.contains('product-dropdown-card') ||
        anchor.classList.contains('product-dropdown-all')
      ) {
        closeProductDropdown();
      }
    });
    productDropdown.addEventListener('mouseenter', syncHeaderHoverState);
    productDropdown.addEventListener('mouseleave', syncHeaderHoverState);
    productDropdown.addEventListener('focusin', syncHeaderHoverState);
    productDropdown.addEventListener('focusout', function () {
      window.setTimeout(syncHeaderHoverState, 0);
    });

    document.addEventListener('mousemove', function (event) {
      if (shouldUseProductClickToggle()) {
        return;
      }
      if (!productWrap.classList.contains('is-open')) {
        return;
      }
      var target = event.target;
      if (target && target.closest && target.closest('.site-header__nav-link--product-wrap')) {
        return;
      }
      closeProductDropdown();
    });

    window.addEventListener('resize', function () {
      if (!shouldUseProductClickToggle()) {
        closeProductDropdown();
      }
      syncHeaderHoverState();
    });

    bindProductDropdownScrollLock();
  }

  if (searchToggle) {
    searchToggle.addEventListener('click', function (event) {
      event.stopPropagation();
      toggleSearchPanel();
      closeLangPanel();
      if (window.innerWidth < 968) {
        closeNav();
      }
    });
  }

  if (searchPanel) {
    searchPanel.addEventListener('click', function (event) {
      event.stopPropagation();
    });
  }

  if (searchForm) {
    searchForm.addEventListener('submit', handleSearchSubmit);
  }

  if (searchInput) {
    searchInput.addEventListener('input', function () {
      if (!searchInput.value.trim()) {
        searchStatus.textContent = searchEmptyMessage;
        clearSearchResults();
      }
    });
  }

  if (langToggle) {
    langToggle.addEventListener('click', function (event) {
      event.stopPropagation();
      toggleLangPanel();
      closeSearchPanel();
    });
  }

  if (langWrap) {
    langWrap.addEventListener('click', function (event) {
      event.stopPropagation();
    });
  }

  if (socialQrPanel) {
    socialQrPanel.addEventListener('click', function (event) {
      event.stopPropagation();
    });
  }

  if (socialQrTriggers.length) {
    socialQrTriggers.forEach(function (trigger) {
      trigger.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        if (!socialQrPanel) {
          return;
        }
        if (!socialQrPanel.hidden && activeSocialTrigger === trigger) {
          closeSocialQrPanel();
          return;
        }
        openSocialQrPanel(
          trigger,
          trigger.getAttribute('data-social-qr') || '',
          trigger.getAttribute('data-social-name') || ''
        );
      });
    });
  }

  window.addEventListener('resize', function () {
    if (socialQrPanel && !socialQrPanel.hidden && activeSocialTrigger) {
      openSocialQrPanel(
        activeSocialTrigger,
        activeSocialTrigger.getAttribute('data-social-qr') || '',
        activeSocialTrigger.getAttribute('data-social-name') || ''
      );
    }
  });

  document.addEventListener('click', function () {
    closeProductDropdown();
    closeSearchPanel();
    closeLangPanel();
    closeSocialQrPanel();
    syncHeaderHoverState();
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
      closeProductDropdown();
      closeSearchPanel();
      closeLangPanel();
      closeSocialQrPanel();
      syncHeaderHoverState();
    }
  });

  syncHeaderHoverState();

  var BANNER_BREAKPOINT = 968;
  var bannerResizeTimer = null;

  function isMobileBannerViewport() {
    if (window.matchMedia) {
      return window.matchMedia('(max-width: ' + BANNER_BREAKPOINT + 'px)').matches;
    }
    return window.innerWidth <= BANNER_BREAKPOINT;
  }

  function pickBannerUrl(pc, mobile) {
    var mobileUrl = String(mobile || '').trim();
    var pcUrl = String(pc || '').trim();
    if (isMobileBannerViewport() && mobileUrl) {
      return mobileUrl;
    }
    return pcUrl || mobileUrl || '';
  }

  function escapeCssUrl(url) {
    return String(url).replace(/\\/g, '\\\\').replace(/'/g, "\\'");
  }

  function applyResponsiveBannerMedia() {
    Array.prototype.forEach.call(document.querySelectorAll('[data-banner-img]'), function (el) {
      var url = pickBannerUrl(el.getAttribute('data-banner-pc'), el.getAttribute('data-banner-mobile'));
      if (!url || el.getAttribute('src') === url) {
        return;
      }
      el.setAttribute('src', url);
    });

    Array.prototype.forEach.call(document.querySelectorAll('[data-banner-bg]'), function (el) {
      var url = pickBannerUrl(el.getAttribute('data-banner-pc'), el.getAttribute('data-banner-mobile'));
      if (!url) {
        return;
      }
      el.style.backgroundImage = "url('" + escapeCssUrl(url) + "')";
      el.style.backgroundSize = 'cover';
      el.style.backgroundPosition = 'center';
      el.style.backgroundRepeat = 'no-repeat';
    });

    Array.prototype.forEach.call(document.querySelectorAll('[data-banner-poster-pc]'), function (video) {
      var url = pickBannerUrl(
        video.getAttribute('data-banner-poster-pc'),
        video.getAttribute('data-banner-poster-mobile')
      );
      if (url) {
        video.setAttribute('poster', url);
      }
    });

    Array.prototype.forEach.call(document.querySelectorAll('[data-banner-video-pc]'), function (video) {
      var url = pickBannerUrl(
        video.getAttribute('data-banner-video-pc'),
        video.getAttribute('data-banner-video-mobile')
      );
      var source = video.querySelector('source');
      if (!url || !source || source.getAttribute('src') === url) {
        return;
      }
      source.setAttribute('src', url);
      video.load();
      if (video.closest('[data-hero-slide]') && video.closest('[data-hero-slide]').classList.contains('is-active')) {
        playHeroVideoIfExists(video);
      }
    });
  }

  function playHeroVideoIfExists(video) {
    if (!video || typeof video.play !== 'function') {
      return;
    }
    var playPromise = video.play();
    if (playPromise && typeof playPromise.catch === 'function') {
      playPromise.catch(function () {});
    }
  }

  function scheduleApplyResponsiveBannerMedia() {
    if (bannerResizeTimer) {
      window.clearTimeout(bannerResizeTimer);
    }
    bannerResizeTimer = window.setTimeout(function () {
      bannerResizeTimer = null;
      applyResponsiveBannerMedia();
    }, 80);
  }

  applyResponsiveBannerMedia();
  window.addEventListener('resize', scheduleApplyResponsiveBannerMedia);
  window.addEventListener('orientationchange', scheduleApplyResponsiveBannerMedia);
  window.applyResponsiveBannerMedia = applyResponsiveBannerMedia;
})();
