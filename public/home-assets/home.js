(function () {
  var revealItems = document.querySelectorAll('.reveal');
  var scrollButtons = document.querySelectorAll('[data-scroll-target]');
  var heroSlides = Array.prototype.slice.call(document.querySelectorAll('[data-hero-slide]'));
  var heroDots = Array.prototype.slice.call(document.querySelectorAll('[data-hero-dot]'));
  var heroPrev = document.querySelector('[data-hero-prev]');
  var heroNext = document.querySelector('[data-hero-next]');
  var caseSlides = Array.prototype.slice.call(document.querySelectorAll('[data-case-slide]'));
  var caseDots = Array.prototype.slice.call(document.querySelectorAll('[data-case-dot]'));
  var casePrev = document.querySelector('[data-case-prev]');
  var caseNext = document.querySelector('[data-case-next]');
  var productTabs = Array.prototype.slice.call(document.querySelectorAll('[data-product-tab]'));
  var productPanels = Array.prototype.slice.call(document.querySelectorAll('[data-product-panel]'));
  var heroIndex = 0;
  var caseIndex = 0;
  var heroTimer = null;
  var HERO_SWIPE_THRESHOLD = 48;

  function loadDeferredMedia(root) {
    if (!root) {
      return;
    }

    root.querySelectorAll('img[data-deferred-src]').forEach(function (img) {
      var src = img.getAttribute('data-deferred-src');
      if (!src || img.getAttribute('src') === src) {
        return;
      }
      img.src = src;
      img.removeAttribute('data-deferred-src');
    });

    root.querySelectorAll('source[data-deferred-srcset]').forEach(function (source) {
      var srcset = source.getAttribute('data-deferred-srcset');
      if (!srcset || source.getAttribute('srcset') === srcset) {
        return;
      }
      source.srcset = srcset;
      source.removeAttribute('data-deferred-srcset');
    });

    var video = root.querySelector('[data-hero-video][data-deferred-video]');
    if (video) {
      var videoSrc = video.getAttribute('data-banner-video-pc');
      if (videoSrc && !video.querySelector('source[src]')) {
        var source = document.createElement('source');
        source.src = videoSrc;
        source.type = 'video/mp4';
        video.appendChild(source);
        video.load();
      }
      var poster = video.getAttribute('data-banner-poster-pc');
      if (poster && !video.getAttribute('poster')) {
        video.setAttribute('poster', poster);
      }
      video.removeAttribute('data-deferred-video');
    }
  }

  function preloadHeroSlide(index) {
    if (!heroSlides.length) {
      return;
    }
    var slide = heroSlides[(index + heroSlides.length) % heroSlides.length];
    if (slide) {
      loadDeferredMedia(slide);
    }
  }

  function scrollToTarget(selector) {
    var target = document.querySelector(selector);
    if (!target) {
      return;
    }

    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  function getSlideVideo(slide) {
    return slide ? slide.querySelector('[data-hero-video]') : null;
  }

  function playHeroVideo(video) {
    if (!video) {
      return;
    }
    var playPromise = video.play();
    if (playPromise && typeof playPromise.catch === 'function') {
      playPromise.catch(function () {});
    }
  }

  function pauseHeroVideo(video) {
    if (!video) {
      return;
    }
    video.pause();
    try {
      video.currentTime = 0;
    } catch (error) {
      /* ignore */
    }
  }

  function syncHeroVideos() {
    heroSlides.forEach(function (slide, index) {
      var video = getSlideVideo(slide);
      if (!video) {
        return;
      }
      if (index === heroIndex) {
        playHeroVideo(video);
      } else {
        pauseHeroVideo(video);
      }
    });
  }

  function setHeroSlide(nextIndex) {
    if (!heroSlides.length) {
      return;
    }

    heroIndex = (nextIndex + heroSlides.length) % heroSlides.length;

    heroSlides.forEach(function (slide, index) {
      slide.classList.toggle('is-active', index === heroIndex);
    });

    heroDots.forEach(function (dot, index) {
      dot.classList.toggle('is-active', index === heroIndex);
    });

    loadDeferredMedia(heroSlides[heroIndex]);

    if (window.requestIdleCallback) {
      window.requestIdleCallback(function () {
        preloadHeroSlide(heroIndex + 1);
      });
    } else {
      window.setTimeout(function () {
        preloadHeroSlide(heroIndex + 1);
      }, 400);
    }

    syncHeroVideos();
  }

  function restartHeroTimer() {
    if (heroTimer) {
      window.clearInterval(heroTimer);
    }

    if (heroSlides.length <= 1) {
      return;
    }

    heroTimer = window.setInterval(function () {
      setHeroSlide(heroIndex + 1);
    }, 5000);
  }

  function initHeroSwipe() {
    var hero = document.getElementById('home-hero');
    if (!hero || heroSlides.length <= 1) {
      return;
    }

    var touchStartX = 0;
    var touchStartY = 0;
    var touchTracking = false;

    hero.addEventListener('touchstart', function (event) {
      if (event.touches.length !== 1) {
        return;
      }
      touchStartX = event.touches[0].clientX;
      touchStartY = event.touches[0].clientY;
      touchTracking = true;
    }, { passive: true });

    hero.addEventListener('touchend', function (event) {
      if (!touchTracking) {
        return;
      }
      touchTracking = false;

      var touch = event.changedTouches[0];
      if (!touch) {
        return;
      }

      var deltaX = touch.clientX - touchStartX;
      var deltaY = touch.clientY - touchStartY;

      if (Math.abs(deltaX) < HERO_SWIPE_THRESHOLD) {
        return;
      }
      if (Math.abs(deltaX) < Math.abs(deltaY)) {
        return;
      }

      if (deltaX < 0) {
        setHeroSlide(heroIndex + 1);
      } else {
        setHeroSlide(heroIndex - 1);
      }
      restartHeroTimer();
    }, { passive: true });

    hero.addEventListener('touchcancel', function () {
      touchTracking = false;
    }, { passive: true });
  }

  function setCaseSlide(nextIndex) {
    if (!caseSlides.length) {
      return;
    }

    caseIndex = (nextIndex + caseSlides.length) % caseSlides.length;

    caseSlides.forEach(function (slide, index) {
      slide.classList.toggle('is-active', index === caseIndex);
    });

    caseDots.forEach(function (dot, index) {
      dot.classList.toggle('is-active', index === caseIndex);
    });

    loadDeferredMedia(caseSlides[caseIndex]);
  }

  function setProductPanel(nextKey) {
    if (!productPanels.length) {
      return;
    }

    productTabs.forEach(function (tab) {
      var isActive = tab.getAttribute('data-product-key') === nextKey;
      tab.classList.toggle('is-active', isActive);
      tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
    });

    productPanels.forEach(function (panel) {
      var isActive = panel.getAttribute('data-product-panel') === nextKey;
      panel.classList.toggle('is-active', isActive);
      if (isActive) {
        loadDeferredMedia(panel);
      }
    });
  }

  function initReveal() {
    var caseRevealItems = Array.prototype.slice.call(document.querySelectorAll('.case-studies .reveal'));
    var normalRevealItems = Array.prototype.slice.call(revealItems).filter(function (item) {
      return !item.closest('.case-studies');
    });

    if (!('IntersectionObserver' in window)) {
      revealItems.forEach(function (item) {
        item.classList.add('is-visible');
      });
      return;
    }

    var observer = new IntersectionObserver(function (entries, obs) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting && entry.intersectionRatio >= 0.22) {
          entry.target.classList.add('is-visible');
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: [0.22, 0.35], rootMargin: '0px 0px -12% 0px' });

    normalRevealItems.forEach(function (item) {
      observer.observe(item);
    });

    if (caseRevealItems.length) {
      var caseObserver = new IntersectionObserver(function (entries, obs) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting && entry.intersectionRatio >= 0.4) {
            entry.target.classList.add('is-visible');
            obs.unobserve(entry.target);
          }
        });
      }, { threshold: [0.4, 0.55], rootMargin: '0px 0px -8% 0px' });

      caseRevealItems.forEach(function (item) {
        caseObserver.observe(item);
      });
    }
  }

  function initCopyInteraction() {
    var blocks = Array.prototype.slice.call(document.querySelectorAll('.copy-interactive'));

    if (!blocks.length) {
      return;
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      return;
    }

    if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
      return;
    }

    blocks.forEach(function (block) {
      block.addEventListener('mouseenter', function () {
        block.classList.add('is-hover');
        block.style.setProperty('--copy-line-scale', '1');
      });

      block.addEventListener('mouseleave', function () {
        block.classList.remove('is-hover');
        block.style.setProperty('--copy-x', '50%');
        block.style.setProperty('--copy-y', '50%');
        block.style.setProperty('--copy-tilt-x', '0px');
        block.style.setProperty('--copy-tilt-y', '0px');
        block.style.setProperty('--copy-line-scale', '0');
      });

      block.addEventListener('mousemove', function (event) {
        var rect = block.getBoundingClientRect();
        var x = (event.clientX - rect.left) / rect.width;
        var y = (event.clientY - rect.top) / rect.height;
        var tiltX = ((x - 0.5) * 14).toFixed(2);
        var tiltY = ((y - 0.5) * 10).toFixed(2);

        block.style.setProperty('--copy-x', (x * 100).toFixed(1) + '%');
        block.style.setProperty('--copy-y', (y * 100).toFixed(1) + '%');
        block.style.setProperty('--copy-tilt-x', tiltX + 'px');
        block.style.setProperty('--copy-tilt-y', tiltY + 'px');
      });
    });
  }

  function initPanelProximityBg() {
    var zones = Array.prototype.slice.call(document.querySelectorAll('.solution-card, .products__feature, .product-mini-card'));

    if (!zones.length) {
      return;
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      return;
    }

    if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
      return;
    }

    var nearMax = 132;
    var nearFull = 40;

    function distanceToRect(x, y, rect) {
      var dx = Math.max(rect.left - x, 0, x - rect.right);
      var dy = Math.max(rect.top - y, 0, y - rect.bottom);

      return Math.hypot(dx, dy);
    }

    function resetPanel(panel) {
      panel.style.setProperty('--panel-bg-opacity', '0');
      panel.classList.remove('is-copy-near');
    }

    zones.forEach(function (zone) {
      var panel = zone.querySelector('.solution-card__panel, .products__feature-panel, .product-mini-card__panel');

      if (!panel) {
        return;
      }

      zone.addEventListener('mousemove', function (event) {
        var rect = panel.getBoundingClientRect();
        var distance = distanceToRect(event.clientX, event.clientY, rect);
        var opacity = 0;

        if (distance <= nearFull) {
          opacity = 1;
        } else if (distance <= nearMax) {
          opacity = 1 - (distance - nearFull) / (nearMax - nearFull);
        }

        panel.style.setProperty('--panel-bg-opacity', opacity.toFixed(3));
        panel.classList.toggle('is-copy-near', opacity > 0.08);
      });

      zone.addEventListener('mouseleave', function () {
        resetPanel(panel);
      });
    });
  }

  initReveal();
  initCopyInteraction();
  initPanelProximityBg();

  if (heroPrev) {
    heroPrev.addEventListener('click', function () {
      setHeroSlide(heroIndex - 1);
      restartHeroTimer();
    });
  }

  if (heroNext) {
    heroNext.addEventListener('click', function () {
      setHeroSlide(heroIndex + 1);
      restartHeroTimer();
    });
  }

  if (casePrev) {
    casePrev.addEventListener('click', function () {
      setCaseSlide(caseIndex - 1);
    });
  }

  if (caseNext) {
    caseNext.addEventListener('click', function () {
      setCaseSlide(caseIndex + 1);
    });
  }

  heroDots.forEach(function (dot) {
    dot.addEventListener('click', function () {
      var nextIndex = Number(dot.getAttribute('data-hero-index'));
      if (!Number.isNaN(nextIndex)) {
        setHeroSlide(nextIndex);
        restartHeroTimer();
      }
    });
  });

  caseDots.forEach(function (dot) {
    dot.addEventListener('click', function () {
      var nextIndex = Number(dot.getAttribute('data-case-index'));
      if (!Number.isNaN(nextIndex)) {
        setCaseSlide(nextIndex);
      }
    });
  });

  productTabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      var nextKey = tab.getAttribute('data-product-key');
      if (nextKey) {
        setProductPanel(nextKey);
      }
    });
  });

  scrollButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      var selector = button.getAttribute('data-scroll-target');
      if (selector) {
        scrollToTarget(selector);
      }
    });
  });

  function initHeroVideos() {
    heroSlides.forEach(function (slide) {
      var video = getSlideVideo(slide);
      if (!video) {
        return;
      }

      video.addEventListener('ended', function () {
        if (!slide.classList.contains('is-active') || heroSlides.length <= 1) {
          return;
        }
        setHeroSlide(heroIndex + 1);
        restartHeroTimer();
      });

      video.addEventListener('error', function () {
        if (!slide.classList.contains('is-active') || heroSlides.length <= 1) {
          return;
        }
        setHeroSlide(heroIndex + 1);
        restartHeroTimer();
      });
    });
  }

  initHeroSwipe();
  initHeroVideos();

  setHeroSlide(0);
  setCaseSlide(0);
  if (productTabs.length) {
    setProductPanel(productTabs[0].getAttribute('data-product-key'));
  }
  restartHeroTimer();
})();
