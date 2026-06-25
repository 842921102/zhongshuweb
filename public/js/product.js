(function () {
  var data = {};
  try {
    var el = document.getElementById('productPageData');
    if (el) data = JSON.parse(el.textContent || '{}');
  } catch (e) {}

  var catalogRoots = data.catalogRoots || [];
  var catalogChildren = data.catalogChildren || {};
  var labels = data.labels || {};
  var catalogTabsEnabled = data.catalogTabsEnabled !== false;
  var activeRoot = data.initialRoot || 'all';
  var activeSub = data.initialSub || null;
  var catalogApiUrl = data.catalogApiUrl || '/api/products/catalog';
  var productCache = {};
  var gridLoading = false;

  function cacheKey(root, sub) {
    return (root || 'all') + ':' + (sub || 'all');
  }

  function categoryParam(root, sub) {
    if (!root || root === 'all') return null;
    if (!sub || sub === 'all') return root;
    return sub;
  }

  function escapeHtml(s) {
    var d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
  }

  function renderCard(p, delay) {
    var metrics = (p.metrics || []).map(function (m) {
      return (
        '<div class="product-card-metric">' +
          '<dt>' + escapeHtml(m[0]) + '</dt>' +
          '<dd>' + escapeHtml(m[1]) + '</dd>' +
        '</div>'
      );
    }).join('');

    return (
      '<article class="product-catalog-card product-card-enter" style="animation-delay:' + delay + 'ms">' +
        '<div class="product-card-inner">' +
          '<a href="' + escapeHtml(p.detailHref) + '" class="product-card-media" aria-label="' + escapeHtml(p.name) + '">' +
            (p.image ? '<img src="' + escapeHtml(p.image) + '" alt="' + escapeHtml(p.name) + '" loading="lazy" decoding="async">' : '') +
          '</a>' +
          '<div class="product-card-body">' +
            (p.model ? '<p class="product-card-series">' + escapeHtml(p.model) + '</p>' : '') +
            '<h3 class="product-card-title">' + escapeHtml(p.name) + '</h3>' +
            (p.subtitle ? '<p class="product-card-subtitle">' + escapeHtml(p.subtitle) + '</p>' : '') +
            (metrics ? '<dl class="product-card-metrics">' + metrics + '</dl>' : '') +
            '<div class="product-card-foot">' +
              '<a href="' + escapeHtml(p.detailHref) + '" class="product-card-link">' +
                '<span>' + escapeHtml(labels.detail || '查看详情') + '</span>' +
                '<svg class="product-card-link-icon" viewBox="0 0 10 10" fill="none" aria-hidden="true">' +
                  '<path d="M2.9161 2.9161H7.08195V7.08195" stroke="currentColor" stroke-width="0.624878"/>' +
                  '<path d="M2.9161 7.08195L7.08195 2.9161" stroke="currentColor" stroke-width="0.624878"/>' +
                '</svg>' +
              '</a>' +
            '</div>' +
          '</div>' +
        '</div>' +
      '</article>'
    );
  }

  function renderGrid(list, options) {
    var container = document.getElementById('productCatalogGrid');
    if (!container) return;
    if (options && options.error) {
      container.innerHTML =
        '<p class="product-empty product-empty--error" role="alert">' +
          escapeHtml(labels.catalog_load_error || '产品加载失败，请稍后重试') +
        '</p>';
      return;
    }
    if (!list.length) {
      container.innerHTML = '<p class="product-empty">' + escapeHtml(labels.catalog_empty || '暂无产品数据') + '</p>';
      return;
    }
    container.innerHTML = list.map(function (p, i) {
      return renderCard(p, i * 60);
    }).join('');
  }

  function showGridLoading() {
    var container = document.getElementById('productCatalogGrid');
    if (container) {
      container.setAttribute('aria-busy', 'true');
    }
  }

  function hideGridLoading() {
    var container = document.getElementById('productCatalogGrid');
    if (container) {
      container.removeAttribute('aria-busy');
    }
  }

  function fetchProducts(root, sub, done) {
    var key = cacheKey(root, sub);
    if (productCache[key]) {
      done(productCache[key]);
      return;
    }

    if (
      root === (data.initialRoot || 'all') &&
      (sub || 'all') === (data.initialSub || 'all') &&
      Array.isArray(data.products)
    ) {
      productCache[key] = data.products;
      done(data.products);
      return;
    }

    var url = new URL(catalogApiUrl, window.location.origin);
    var param = categoryParam(root, sub);
    if (param) {
      url.searchParams.set('category', param);
    }

    var lang = new URLSearchParams(window.location.search).get('lang');
    if (lang) {
      url.searchParams.set('lang', lang);
    }

    gridLoading = true;
    showGridLoading();

    fetch(url.toString(), { headers: { Accept: 'application/json' } })
      .then(function (res) {
        if (!res.ok) throw new Error('catalog fetch failed');
        return res.json();
      })
      .then(function (payload) {
        productCache[key] = payload.products || [];
        done(productCache[key]);
      })
      .catch(function () {
        done([], { error: true });
      })
      .finally(function () {
        gridLoading = false;
        hideGridLoading();
      });
  }

  function applySelection(root, sub) {
    fetchProducts(root, sub, function (list, options) {
      renderGrid(list, options);
    });
  }

  function renderSubTabs() {
    var bar = document.getElementById('productCatalogSubTabsBar');
    var tabs = document.getElementById('productCatalogSubTabs');
    if (!bar || !tabs) return;

    if (activeRoot === 'all') {
      bar.hidden = true;
      tabs.innerHTML = '';
      return;
    }

    var children = catalogChildren[activeRoot] || [];
    if (!children.length) {
      bar.hidden = true;
      tabs.innerHTML = '';
      return;
    }

    bar.hidden = false;
    if (!activeSub || activeSub === null) activeSub = 'all';

    var html =
      '<button type="button" class="product-catalog-tab product-catalog-tab--sub' + (activeSub === 'all' ? ' is-active' : '') + '" data-sub="all" role="tab" aria-selected="' + (activeSub === 'all' ? 'true' : 'false') + '">' +
        escapeHtml(labels.all || '全部') +
      '</button>';

    children.forEach(function (child) {
      var on = activeSub === child.key;
      html +=
        '<button type="button" class="product-catalog-tab product-catalog-tab--sub' + (on ? ' is-active' : '') + '" data-sub="' + child.key + '" role="tab" aria-selected="' + (on ? 'true' : 'false') + '">' +
          escapeHtml(child.label) +
        '</button>';
    });

    tabs.innerHTML = html;
    bindSubTabs();
  }

  function updateUrl(root, sub) {
    var base = window.productIndexUrl || '/products';
    var u = new URL(base, window.location.origin);
    var param = categoryParam(root, sub);
    if (param) {
      u.searchParams.set('category', param);
    } else {
      u.searchParams.delete('category');
    }
    window.history.replaceState({}, '', u.pathname + u.search);
  }

  function setActiveRootTab(button) {
    document.querySelectorAll('#productCatalogTabs .product-catalog-tab').forEach(function (tab) {
      var on = tab === button;
      tab.classList.toggle('is-active', on);
      tab.setAttribute('aria-selected', on ? 'true' : 'false');
    });
  }

  function setActiveSubTab(button) {
    document.querySelectorAll('#productCatalogSubTabs .product-catalog-tab').forEach(function (tab) {
      var on = tab === button;
      tab.classList.toggle('is-active', on);
      tab.setAttribute('aria-selected', on ? 'true' : 'false');
    });
  }

  function bindRootTabs() {
    document.querySelectorAll('#productCatalogTabs .product-catalog-tab').forEach(function (tab) {
      tab.addEventListener('click', function () {
        if (gridLoading) return;
        activeRoot = tab.getAttribute('data-root') || 'all';
        activeSub = activeRoot === 'all' ? null : 'all';
        setActiveRootTab(tab);
        renderSubTabs();
        applySelection(activeRoot, activeSub);
        updateUrl(activeRoot, activeSub);
      });
    });
  }

  function bindSubTabs() {
    document.querySelectorAll('#productCatalogSubTabs .product-catalog-tab').forEach(function (tab) {
      tab.addEventListener('click', function () {
        if (gridLoading) return;
        activeSub = tab.getAttribute('data-sub') || 'all';
        setActiveSubTab(tab);
        applySelection(activeRoot, activeSub);
        updateUrl(activeRoot, activeSub);
      });
    });
  }

  if (catalogTabsEnabled) {
    bindRootTabs();
    bindSubTabs();
  }

  productCache[cacheKey(activeRoot, activeSub)] = data.products || [];

})();
