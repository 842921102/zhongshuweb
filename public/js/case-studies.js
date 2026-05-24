(function () {
  var root = document.querySelector("[data-cs-featured]");
  if (!root) {
    return;
  }

  var slides = Array.prototype.slice.call(root.querySelectorAll("[data-cs-featured-slide]"));
  var dots = Array.prototype.slice.call(root.querySelectorAll("[data-cs-featured-dot]"));
  var prev = root.querySelector("[data-cs-featured-prev]");
  var next = root.querySelector("[data-cs-featured-next]");
  var index = 0;
  var timer = null;

  function show(i) {
    if (!slides.length) {
      return;
    }
    index = (i + slides.length) % slides.length;
    slides.forEach(function (slide, n) {
      slide.classList.toggle("is-active", n === index);
    });
    dots.forEach(function (dot, n) {
      dot.classList.toggle("is-active", n === index);
    });
  }

  function nextSlide() {
    show(index + 1);
  }

  function startAuto() {
    stopAuto();
    if (slides.length > 1) {
      timer = window.setInterval(nextSlide, 6000);
    }
  }

  function stopAuto() {
    if (timer) {
      window.clearInterval(timer);
      timer = null;
    }
  }

  if (prev) {
    prev.addEventListener("click", function () {
      show(index - 1);
      startAuto();
    });
  }

  if (next) {
    next.addEventListener("click", function () {
      show(index + 1);
      startAuto();
    });
  }

  dots.forEach(function (dot) {
    dot.addEventListener("click", function () {
      var i = parseInt(dot.getAttribute("data-cs-index"), 10);
      if (!isNaN(i)) {
        show(i);
        startAuto();
      }
    });
  });

  root.addEventListener("mouseenter", stopAuto);
  root.addEventListener("mouseleave", startAuto);

  show(0);
  startAuto();
})();
