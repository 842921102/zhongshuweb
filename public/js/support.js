(function () {
  var pageData = {};
  try {
    var el = document.getElementById('supportPageData');
    if (el) pageData = JSON.parse(el.textContent || '{}');
  } catch (e) {}

  var config = window.supportConfig || {};
  var toast = document.getElementById('supportToast');
  var form = document.getElementById('supportRequestForm');
  var feedback = document.getElementById('supportFormFeedback');

  function showToast(msg) {
    if (!toast) return;
    toast.textContent = msg;
    toast.classList.add('is-visible');
    setTimeout(function () {
      toast.classList.remove('is-visible');
    }, 3200);
  }

  function setFeedback(msg, type) {
    if (!feedback) return;
    feedback.hidden = !msg;
    feedback.textContent = msg || '';
    feedback.className = 'support-form-feedback' + (type ? ' is-' + type : '');
  }

  /* Banner responsive */
  var bannerImg = document.querySelector('[data-banner-img]');
  if (bannerImg) {
    function swapBanner() {
      var mobile = bannerImg.getAttribute('data-banner-mobile');
      var pc = bannerImg.getAttribute('data-banner-pc') || bannerImg.src;
      bannerImg.src = window.innerWidth <= 640 && mobile ? mobile : pc;
    }
    swapBanner();
    window.addEventListener('resize', swapBanner);
  }

  /* Nav active */
  /* Topic buttons */
  if (form) {
    var topicInput = form.querySelector('input[name="topic"]');
    form.querySelectorAll('.support-topic-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        form.querySelectorAll('.support-topic-btn').forEach(function (b) {
          b.classList.remove('is-active');
          b.setAttribute('aria-pressed', 'false');
        });
        btn.classList.add('is-active');
        btn.setAttribute('aria-pressed', 'true');
        if (topicInput) topicInput.value = btn.getAttribute('data-topic') || '';
      });
    });
  }

  /* Region picker */
  var province = document.getElementById('supportProvince');
  var city = document.getElementById('supportCity');
  var district = document.getElementById('supportDistrict');
  var regionValue = document.getElementById('supportRegionValue');
  var provinceCode = document.getElementById('supportProvinceCode');
  var cityCode = document.getElementById('supportCityCode');
  var districtCode = document.getElementById('supportDistrictCode');

  function resetSelect(sel, placeholder) {
    if (!sel) return;
    sel.innerHTML = '';
    var opt = document.createElement('option');
    opt.value = '';
    opt.textContent = placeholder;
    sel.appendChild(opt);
    sel.disabled = true;
  }

  function fillSelect(sel, items, placeholder) {
    if (!sel) return;
    sel.innerHTML = '';
    var first = document.createElement('option');
    first.value = '';
    first.textContent = placeholder;
    sel.appendChild(first);
    items.forEach(function (item) {
      var o = document.createElement('option');
      o.value = item.code;
      o.textContent = item.name;
      sel.appendChild(o);
    });
    sel.disabled = false;
  }

  function updateRegionValue() {
    var parts = [];
    if (province && province.selectedOptions[0] && province.value) {
      parts.push(province.selectedOptions[0].textContent);
    }
    if (city && city.selectedOptions[0] && city.value) {
      parts.push(city.selectedOptions[0].textContent);
    }
    if (district && district.selectedOptions[0] && district.value) {
      parts.push(district.selectedOptions[0].textContent);
    }
    if (regionValue) regionValue.value = parts.join(' / ');
    if (provinceCode) provinceCode.value = province ? province.value : '';
    if (cityCode) cityCode.value = city ? city.value : '';
    if (districtCode) districtCode.value = district ? district.value : '';
  }

  function loadRegion(parent, target, placeholder, errMsg) {
    var url = config.regionUrl + (parent ? '?parent=' + encodeURIComponent(parent) : '');
    return fetch(url, { headers: { Accept: 'application/json' } })
      .then(function (r) { return r.json(); })
      .then(function (json) {
        fillSelect(target, json.data || [], placeholder);
      })
      .catch(function () {
        showToast(errMsg || pageData.regionLoadError || '地区数据加载失败');
      });
  }

  if (province) {
    province.addEventListener('change', function () {
      resetSelect(city, '选择城市');
      resetSelect(district, '选择区县');
      updateRegionValue();
      if (!province.value) return;
      loadRegion(province.value, city, '选择城市', pageData.regionCityLoadError);
    });
  }

  if (city) {
    city.addEventListener('change', function () {
      resetSelect(district, '选择区县');
      updateRegionValue();
      if (!city.value) return;
      loadRegion(city.value, district, '选择区县', pageData.regionDistrictLoadError);
    });
  }

  if (district) {
    district.addEventListener('change', updateRegionValue);
  }

  /* Form submit */
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      setFeedback('', '');

      var name = form.querySelector('[name="name"]');
      var phone = form.querySelector('[name="phone"]');
      var email = form.querySelector('[name="email"]');
      var topic = form.querySelector('[name="topic"]');

      if (!name || !name.value.trim()) {
        setFeedback(pageData.validationNameRequired || '请输入姓名', 'error');
        return;
      }
      if (!phone || !/^1\d{10}$/.test(phone.value.trim())) {
        setFeedback(pageData.validationPhoneInvalid || '请输入正确的 11 位手机号码', 'error');
        return;
      }
      if (email && email.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        setFeedback(pageData.validationEmailInvalid || '电子邮箱格式不正确', 'error');
        return;
      }
      if (!regionValue || !regionValue.value.trim()) {
        setFeedback(pageData.validationRegionRequired || '请选择所在地区', 'error');
        return;
      }
      if (!topic || !topic.value) {
        setFeedback(pageData.validationTopicRequired || '请选择咨询主题', 'error');
        return;
      }

      var btn = form.querySelector('.support-submit-btn');
      if (btn) btn.disabled = true;
      setFeedback(pageData.submitting || '正在提交…', '');

      var body = new FormData(form);
      fetch(config.submitUrl, {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          'X-CSRF-TOKEN': config.csrfToken || '',
        },
        body: body,
      })
        .then(function (r) {
          return r.json().then(function (json) {
            return { ok: r.ok, json: json };
          });
        })
        .then(function (res) {
          if (res.ok) {
            var msg = res.json.message || pageData.submitSuccess;
            setFeedback(msg, 'success');
            showToast(msg);
            form.reset();
            var activeTopic = form.querySelector('.support-topic-btn.is-active');
            if (topicInput) topicInput.value = activeTopic ? (activeTopic.getAttribute('data-topic') || '') : '';
            resetSelect(city, '选择城市');
            resetSelect(district, '选择区县');
            updateRegionValue();
          } else {
            setFeedback(res.json.message || pageData.submitError, 'error');
          }
        })
        .catch(function () {
          setFeedback(pageData.submitError || '提交失败', 'error');
        })
        .finally(function () {
          if (btn) btn.disabled = false;
        });
    });
  }

  /* Video modal */
  var modal = document.getElementById('supportVideoModal');
  var player = document.getElementById('supportVideoPlayer');
  var modalTitle = document.getElementById('supportVideoModalTitle');

  function openVideo(id, title, url) {
    if (!modal || !player) return;
    modalTitle.textContent = title || '';
    player.src = url;
    modal.hidden = false;
    player.play().catch(function () {});

    if (id && config.videoPlayUrl) {
      fetch(config.videoPlayUrl + '/' + id + '/play', {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          'X-CSRF-TOKEN': config.csrfToken || '',
        },
      })
        .then(function (r) { return r.json(); })
        .then(function (json) {
          document.querySelectorAll('.support-video-play-count[data-video-id="' + id + '"]').forEach(function (el) {
            el.textContent = '播放量 ' + (json.play_count || 0);
          });
        })
        .catch(function () {});
    }
  }

  function closeVideo() {
    if (!modal || !player) return;
    player.pause();
    player.removeAttribute('src');
    modal.hidden = true;
  }

  document.querySelectorAll('[data-video-url]').forEach(function (el) {
    el.addEventListener('click', function () {
      openVideo(
        el.getAttribute('data-video-id'),
        el.getAttribute('data-video-title'),
        el.getAttribute('data-video-url')
      );
    });
  });

  document.querySelectorAll('[data-video-modal-close]').forEach(function (el) {
    el.addEventListener('click', closeVideo);
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeVideo();
  });
})();
