(function () {
  document.querySelectorAll('.product-banner__video').forEach(function (video) {
    var wrap = video.closest('.product-banner');
    if (!wrap) return;

    var playBtn = wrap.querySelector('[data-banner-play]');
    var muteBtn = wrap.querySelector('[data-banner-mute]');

    if (playBtn) {
      playBtn.addEventListener('click', function () {
        if (video.paused) {
          video.play();
          playBtn.classList.remove('is-paused');
          playBtn.setAttribute('aria-label', '暂停视频');
        } else {
          video.pause();
          playBtn.classList.add('is-paused');
          playBtn.setAttribute('aria-label', '播放视频');
        }
      });
    }

    if (muteBtn) {
      muteBtn.addEventListener('click', function () {
        video.muted = !video.muted;
        muteBtn.classList.toggle('is-muted', video.muted);
        muteBtn.setAttribute('aria-label', video.muted ? '开启声音' : '关闭声音');
      });
    }
  });
})();
