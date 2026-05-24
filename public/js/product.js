(function () {
  var data = {};
  try {
    var el = document.getElementById('productPageData');
    if (el) data = JSON.parse(el.textContent || '{}');
  } catch (e) {}

  var products = data.products || [];
  var megaChildren = data.megaChildren || {};
  var labels = data.labels || {};
  var activeSeries = data.initialCategory;
  var activeCatalog = data.initialCatalogCategory;

  function productsForSeries(seriesKey) {
    return products.filter(function (p) {
      return p.parent_key === seriesKey || p.category_key === seriesKey;
    });
  }

  function productsForTab(seriesKey, tab) {
    var list = productsForSeries(seriesKey);
    if (!tab || tab.indexOf('all:') === 0) return list;
    return list.filter(function (p) { return p.category_key === tab; });
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

  function renderGrid(list) {
    var container = document.getElementById('productCatalogGrid');
    if (!container) return;
    if (!list.length) {
      container.innerHTML = '<p class="product-empty">' + escapeHtml(labels.catalog_empty || '暂无产品数据') + '</p>';
      return;
    }
    container.innerHTML = list.map(function (p, i) {
      return renderCard(p, i * 60);
    }).join('');
  }

  function buildCatalogTabs(seriesKey) {
    var tabs = document.getElementById('productCatalogTabs');
    if (!tabs) return;
    var children = megaChildren[seriesKey] || [];
    var isAll = !activeCatalog || activeCatalog.indexOf('all:') === 0;
    var html =
      '<button type="button" class="product-catalog-tab' + (isAll ? ' is-active' : '') + '" data-category="all:' + seriesKey + '" role="tab" aria-selected="' + (isAll ? 'true' : 'false') + '">' +
        escapeHtml(labels.all || '全部') +
      '</button>';
    children.forEach(function (c) {
      var on = activeCatalog === c.key;
      html +=
        '<button type="button" class="product-catalog-tab' + (on ? ' is-active' : '') + '" data-category="' + c.key + '" role="tab" aria-selected="' + (on ? 'true' : 'false') + '">' +
          escapeHtml(c.label) +
        '</button>';
    });
    tabs.innerHTML = html;
    bindCatalogTabs();
  }

  function updateUrl(category) {
    var base = window.productIndexUrl || '/products';
    var u = new URL(base, window.location.origin);
    if (category) u.searchParams.set('category', category);
    window.history.replaceState({}, '', u.pathname + u.search);
  }

  function bindCatalogTabs() {
    document.querySelectorAll('.product-catalog-tab').forEach(function (tab) {
      tab.addEventListener('click', function () {
        activeCatalog = tab.getAttribute('data-category');
        document.querySelectorAll('.product-catalog-tab').forEach(function (t) {
          var on = t === tab;
          t.classList.toggle('is-active', on);
          t.setAttribute('aria-selected', on ? 'true' : 'false');
        });
        renderGrid(productsForTab(activeSeries, activeCatalog));
        updateUrl(activeCatalog);
      });
    });
  }

  bindCatalogTabs();

  document.querySelectorAll('.site-header__nav-link[data-route]').forEach(function (link) {
    var routes = (link.getAttribute('data-route') || '').split(',');
    var path = window.location.pathname.replace(/\/$/, '') || '/';
    if (routes.some(function (key) { return key === 'product' && path === '/products'; })) {
      link.classList.add('is-active');
      link.setAttribute('aria-current', 'page');
    }
  });
})();
