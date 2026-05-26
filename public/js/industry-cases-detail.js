(function () {
  function initCarousel(root, options) {
    if (!root) {
      return;
    }

    var slides = Array.prototype.slice.call(root.querySelectorAll(options.slideSelector));
    if (slides.length <= 1) {
      return;
    }

    var dots = options.dotsSelector
      ? Array.prototype.slice.call(root.querySelectorAll(options.dotsSelector))
      : [];
    var prev = options.prevSelector ? root.querySelector(options.prevSelector) : null;
    var next = options.nextSelector ? root.querySelector(options.nextSelector) : null;
    var index = 0;
    var timer = null;
    var autoplayMs = options.autoplayMs || 0;

    function setActive(nextIndex) {
      index = (nextIndex + slides.length) % slides.length;
      slides.forEach(function (slide, i) {
        slide.classList.toggle('is-active', i === index);
      });
      dots.forEach(function (dot, i) {
        dot.classList.toggle('is-active', i === index);
      });
    }

    function restartAutoplay() {
      if (!autoplayMs) {
        return;
      }
      if (timer) {
        clearInterval(timer);
      }
      timer = setInterval(function () {
        setActive(index + 1);
      }, autoplayMs);
    }

    if (prev) {
      prev.addEventListener('click', function () {
        setActive(index - 1);
        restartAutoplay();
      });
    }

    if (next) {
      next.addEventListener('click', function () {
        setActive(index + 1);
        restartAutoplay();
      });
    }

    dots.forEach(function (dot, i) {
      dot.addEventListener('click', function () {
        setActive(i);
        restartAutoplay();
      });
    });

    setActive(0);
    restartAutoplay();
  }

  initCarousel(document.querySelector('[data-ic-hero-carousel]'), {
    slideSelector: '[data-ic-hero-slide]',
    dotsSelector: '[data-ic-hero-dot]',
    autoplayMs: 6000,
  });

  Array.prototype.forEach.call(document.querySelectorAll('[data-ic-scene-carousel]'), function (carousel) {
    initCarousel(carousel, {
      slideSelector: '[data-ic-scene-slide]',
      dotsSelector: '[data-ic-scene-dot]',
      prevSelector: '[data-ic-scene-prev]',
      nextSelector: '[data-ic-scene-next]',
      autoplayMs: 5000,
    });
  });

  function syncSceneRowHeights() {
    var minMedia = window.matchMedia('(min-width: 1024px)').matches ? 320 : 240;

    Array.prototype.forEach.call(document.querySelectorAll('[data-ic-scene-row]'), function (row) {
      var cards = row.querySelector('.ic-scene-article__cards');
      var media = row.querySelector('.ic-scene-article__media');
      var carousel = row.querySelector('.ic-scene-carousel');

      if (!cards || !media || !carousel) {
        if (media) {
          media.style.minHeight = '';
        }
        if (carousel) {
          carousel.style.minHeight = '';
        }
        return;
      }

      var cardsHeight = cards.getBoundingClientRect().height;
      var target = Math.max(Math.ceil(cardsHeight), minMedia);
      media.style.minHeight = target + 'px';
      carousel.style.minHeight = target + 'px';
    });
  }

  var syncSceneTimer = null;
  function scheduleSceneSync() {
    if (syncSceneTimer) {
      clearTimeout(syncSceneTimer);
    }
    syncSceneTimer = setTimeout(syncSceneRowHeights, 80);
  }

  if (typeof ResizeObserver !== 'undefined') {
    var sceneObserver = new ResizeObserver(scheduleSceneSync);
    Array.prototype.forEach.call(document.querySelectorAll('.ic-scene-article__cards'), function (cards) {
      sceneObserver.observe(cards);
    });
  }

  window.addEventListener('load', syncSceneRowHeights);
  window.addEventListener('resize', scheduleSceneSync);
  syncSceneRowHeights();

  Array.prototype.forEach.call(document.querySelectorAll('[data-ic-scene-row] img'), function (img) {
    if (img.complete) {
      return;
    }
    img.addEventListener('load', scheduleSceneSync);
    img.addEventListener('error', scheduleSceneSync);
  });

  var relatedTrack = document.querySelector('[data-ic-related-track]');
  var relatedPrev = document.querySelector('[data-ic-related-prev]');
  var relatedNext = document.querySelector('[data-ic-related-next]');

  function updateRelatedNav() {
    if (!relatedTrack || !relatedPrev || !relatedNext) {
      return;
    }
    var maxScroll = relatedTrack.scrollWidth - relatedTrack.clientWidth - 2;
    relatedPrev.disabled = relatedTrack.scrollLeft <= 0;
    relatedNext.disabled = relatedTrack.scrollLeft >= maxScroll;
  }

  if (relatedTrack && relatedPrev && relatedNext) {
    relatedPrev.addEventListener('click', function () {
      relatedTrack.scrollBy({ left: -relatedTrack.clientWidth * 0.85, behavior: 'smooth' });
    });
    relatedNext.addEventListener('click', function () {
      relatedTrack.scrollBy({ left: relatedTrack.clientWidth * 0.85, behavior: 'smooth' });
    });
    relatedTrack.addEventListener('scroll', updateRelatedNav);
    window.addEventListener('resize', updateRelatedNav);
    updateRelatedNav();
  }

  var navLinks = Array.prototype.slice.call(document.querySelectorAll('[data-ic-detail-link]'));
  var sections = Array.prototype.slice.call(document.querySelectorAll('[data-ic-detail-section]'));

  if (navLinks.length && sections.length && typeof IntersectionObserver !== 'undefined') {
    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) {
            return;
          }
          var id = entry.target.id;
          navLinks.forEach(function (link) {
            var active = link.getAttribute('href') === '#' + id;
            link.classList.toggle('is-active', active);
          });
        });
      },
      {
        root: null,
        rootMargin: '-45% 0px -45% 0px',
        threshold: 0,
      }
    );

    sections.forEach(function (section) {
      observer.observe(section);
    });

    navLinks.forEach(function (link) {
      link.addEventListener('click', function (event) {
        var href = link.getAttribute('href');
        if (!href || href.charAt(0) !== '#') {
          return;
        }
        var target = document.querySelector(href);
        if (!target) {
          return;
        }
        event.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });
  }
})();
