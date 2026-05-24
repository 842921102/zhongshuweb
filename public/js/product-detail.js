(function () {
  var data = {};
  try {
    var el = document.getElementById('productDetailData');
    if (el) data = JSON.parse(el.textContent || '{}');
  } catch (e) {}

  var slides = data.showcaseSlides || [];
  var specGroups = data.specGroups || [];
  var labels = data.labels || {};
  var slideIndex = 0;

  function renderSpecTable(key) {
    var body = document.getElementById('specTableBody');
    if (!body) return;
    var group = specGroups.find(function (g) { return g.key === key; }) || specGroups[0];
    if (!group) {
      body.innerHTML = '<p class="product-empty">' + (labels.specs_empty || '暂无参数信息') + '</p>';
      return;
    }
    body.innerHTML = (group.rows || []).map(function (row) {
      return '<dl class="specs-table-row"><dt>' + escapeHtml(row.label) + '</dt><dd>' + escapeHtml(row.value) + '</dd></dl>';
    }).join('');
  }

  function escapeHtml(s) {
    var d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
  }

  function setSlide(index) {
    if (!slides.length) return;
    slideIndex = (index + slides.length) % slides.length;
    var slide = slides[slideIndex];
    var img = document.getElementById('showcaseMainImage');
    if (img && slide) {
      img.src = slide.image;
      img.alt = slide.alt || '';
    }
    document.querySelectorAll('.showcase-indicator').forEach(function (btn, i) {
      btn.classList.toggle('is-active', i === slideIndex);
    });
  }

  var prev = document.getElementById('showcasePrev');
  var next = document.getElementById('showcaseNext');
  if (prev) prev.addEventListener('click', function () { setSlide(slideIndex - 1); });
  if (next) next.addEventListener('click', function () { setSlide(slideIndex + 1); });
  document.querySelectorAll('.showcase-indicator').forEach(function (btn) {
    btn.addEventListener('click', function () {
      setSlide(parseInt(btn.getAttribute('data-slide-index'), 10) || 0);
    });
  });

  document.querySelectorAll('.specs-tab').forEach(function (tab) {
    tab.addEventListener('click', function () {
      var key = tab.getAttribute('data-spec-key');
      document.querySelectorAll('.specs-tab').forEach(function (t) {
        var on = t === tab;
        t.classList.toggle('is-active', on);
        t.setAttribute('aria-selected', on ? 'true' : 'false');
      });
      renderSpecTable(key);
    });
  });

  var form = document.getElementById('contactForm');
  var statusEl = document.getElementById('productFormStatus');
  if (form && data.submitUrl) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var fd = new FormData(form);
      var token = form.querySelector('input[name="_token"]');
      fetch(data.submitUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': token ? token.value : '',
          'Accept': 'application/json',
        },
        body: fd,
      })
        .then(function (res) { return res.json().then(function (body) { return { ok: res.ok, body: body }; }); })
        .then(function (result) {
          if (!statusEl) return;
          statusEl.hidden = false;
          if (result.ok) {
            statusEl.textContent = result.body.message || labels.form_success || '提交成功';
            statusEl.className = 'product-form-status is-success';
            form.reset();
          } else {
            statusEl.textContent = result.body.message || labels.form_error || '提交失败';
            statusEl.className = 'product-form-status is-error';
          }
        })
        .catch(function () {
          if (statusEl) {
            statusEl.hidden = false;
            statusEl.textContent = labels.form_error || '提交失败';
            statusEl.className = 'product-form-status is-error';
          }
        });
    });
  }

  var downloadBtn = document.getElementById('downloadSpecBtn');
  if (downloadBtn && downloadBtn.tagName === 'BUTTON' && data.downloadDoc && data.downloadDoc.url) {
    downloadBtn.disabled = false;
    downloadBtn.addEventListener('click', function () {
      window.open(data.downloadDoc.url, '_blank');
    });
  }

})();
