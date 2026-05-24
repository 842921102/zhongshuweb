(function () {
  var config = window.joinApplyConfig || {};
  var form = document.getElementById('joinApplyForm');
  var feedback = document.getElementById('joinFormFeedback');
  var positionSelect = document.getElementById('joinPositionId');

  document.querySelectorAll('.join-job-apply[data-position-id]').forEach(function (link) {
    link.addEventListener('click', function () {
      var id = link.getAttribute('data-position-id');
      if (positionSelect && id) {
        positionSelect.value = id;
      }
    });
  });

  if (form && config.submitUrl) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (feedback) {
        feedback.hidden = true;
        feedback.className = 'join-form-feedback';
      }

      var fd = new FormData(form);
      var btn = form.querySelector('button[type="submit"]');
      if (btn) btn.disabled = true;

      fetch(config.submitUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': config.csrfToken || '',
          Accept: 'application/json',
        },
        body: fd,
      })
        .then(function (res) {
          return res.json().then(function (data) {
            return { ok: res.ok, data: data };
          });
        })
        .then(function (result) {
          if (!feedback) return;
          feedback.hidden = false;
          if (result.ok) {
            feedback.textContent = result.data.message || '提交成功';
            feedback.className = 'join-form-feedback is-success';
            form.reset();
          } else {
            feedback.textContent = result.data.message || config.errorMessage || '提交失败';
            feedback.className = 'join-form-feedback is-error';
          }
        })
        .catch(function () {
          if (feedback) {
            feedback.hidden = false;
            feedback.textContent = config.errorMessage || '提交失败，请稍后重试';
            feedback.className = 'join-form-feedback is-error';
          }
        })
        .finally(function () {
          if (btn) btn.disabled = false;
        });
    });
  }

  document.querySelectorAll('.site-header__nav-link[data-route]').forEach(function (link) {
    var routes = (link.getAttribute('data-route') || '').split(',');
    var path = window.location.pathname.replace(/\/$/, '') || '/';
    if (routes.some(function (key) {
      return key === 'joinus' && (path === '/join-us' || path.indexOf('/join-us') === 0);
    })) {
      link.classList.add('is-active');
      link.setAttribute('aria-current', 'page');
    }
  });

  var filterLinks = document.querySelectorAll('.join-job-filter-link');
  var jobCards = document.querySelectorAll('.join-job-card[data-join-category]');

  if (filterLinks.length && jobCards.length) {
    filterLinks.forEach(function (link) {
      link.addEventListener('click', function (e) {
        var cat = link.getAttribute('data-join-category');
        if (!cat || link.classList.contains('is-active')) {
          return;
        }
        if (link.getAttribute('href') && link.getAttribute('href').indexOf('category=') !== -1) {
          return;
        }
        e.preventDefault();
        filterLinks.forEach(function (l) { l.classList.remove('is-active'); });
        link.classList.add('is-active');
        jobCards.forEach(function (card) {
          var match = cat === 'all' || card.getAttribute('data-join-category') === cat;
          card.classList.toggle('is-hidden', !match);
        });
      });
    });
  }
})();
