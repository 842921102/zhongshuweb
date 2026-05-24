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
      panel.classList.toggle('is-active', panel.getAttribute('data-product-panel') === nextKey);
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

  initReveal();

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
