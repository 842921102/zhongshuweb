(function () {
  var tabsWrap = document.querySelector('[data-ic-tabs]');
  if (!tabsWrap) {
    return;
  }

  var tabs = Array.prototype.slice.call(tabsWrap.querySelectorAll('[data-ic-tab]'));
  var indicator = tabsWrap.querySelector('[data-ic-indicator]');
  var blocks = Array.prototype.slice.call(document.querySelectorAll('[data-ic-block]'));
  var activeIndex = 0;

  function moveIndicator(index) {
    var btn = tabs[index];
    if (!btn || !indicator) {
      return;
    }
    indicator.style.width = btn.offsetWidth + 'px';
    indicator.style.left = btn.offsetLeft + 'px';
  }

  function setActiveTab(index, scrollToBlock) {
    if (index < 0 || index >= tabs.length) {
      return;
    }
    activeIndex = index;
    tabs.forEach(function (tab, i) {
      tab.classList.toggle('is-active', i === index);
      tab.setAttribute('aria-selected', i === index ? 'true' : 'false');
    });
    moveIndicator(index);
    if (scrollToBlock && blocks[index]) {
      blocks[index].scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }

  tabs.forEach(function (tab, index) {
    tab.addEventListener('click', function () {
      setActiveTab(index, true);
    });
  });

  if (typeof IntersectionObserver !== 'undefined' && blocks.length) {
    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) {
            return;
          }
          var idx = blocks.indexOf(entry.target);
          if (idx >= 0 && idx !== activeIndex) {
            setActiveTab(idx, false);
          }
        });
      },
      {
        root: null,
        rootMargin: '-40% 0px -45% 0px',
        threshold: 0,
      }
    );
    blocks.forEach(function (block) {
      observer.observe(block);
    });
  }

  window.addEventListener('resize', function () {
    moveIndicator(activeIndex);
  });

  setActiveTab(0, false);
  requestAnimationFrame(function () {
    moveIndicator(0);
  });
})();
