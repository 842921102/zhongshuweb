(function () {
  var mapSvg = document.querySelector('[data-about-global-map]');
  if (!mapSvg) {
    return;
  }

  var landGroup = mapSvg.querySelector('.about-global__land');
  var topoUrl = mapSvg.getAttribute('data-topo-url');
  var fallbackImg = mapSvg.parentElement && mapSvg.parentElement.querySelector('.about-global__map-fallback');
  var width = Number(mapSvg.getAttribute('data-map-width')) || 1100;
  var height = Number(mapSvg.getAttribute('data-map-height')) || 550;

  if (!landGroup || !topoUrl) {
    return;
  }

  function loadScript(src) {
    return new Promise(function (resolve, reject) {
      if (document.querySelector('script[src="' + src + '"]')) {
        resolve();
        return;
      }
      var script = document.createElement('script');
      script.src = src;
      script.defer = true;
      script.onload = resolve;
      script.onerror = reject;
      document.head.appendChild(script);
    });
  }

  function ensureLibraries() {
    var tasks = [];
    if (typeof window.d3 === 'undefined') {
      tasks.push(loadScript('https://cdn.jsdelivr.net/npm/d3@7/dist/d3.min.js'));
    }
    if (typeof window.topojson === 'undefined') {
      tasks.push(loadScript('https://cdn.jsdelivr.net/npm/topojson-client@3/dist/topojson-client.min.js'));
    }
    return Promise.all(tasks);
  }

  function showFallback() {
    if (fallbackImg) {
      mapSvg.setAttribute('hidden', 'hidden');
      fallbackImg.hidden = false;
    }
  }

  function isAntarctica(feature) {
    var id = feature.id;
    return id === '010' || id === 10 || id === 'AQ';
  }

  function positionMarkers(projection) {
    var markers = document.querySelectorAll('.about-global__marker[data-marker-lat][data-marker-lon]');
    markers.forEach(function (marker) {
      var lat = Number(marker.getAttribute('data-marker-lat'));
      var lon = Number(marker.getAttribute('data-marker-lon'));
      if (!Number.isFinite(lat) || !Number.isFinite(lon)) {
        return;
      }
      var point = projection([lon, lat]);
      if (!point || !Number.isFinite(point[0]) || !Number.isFinite(point[1])) {
        return;
      }
      marker.style.setProperty('--marker-x', ((point[0] / width) * 100).toFixed(3) + '%');
      marker.style.setProperty('--marker-y', ((point[1] / height) * 100).toFixed(3) + '%');
      marker.classList.add('is-positioned');
    });
  }

  ensureLibraries()
    .then(function () {
      return fetch(topoUrl);
    })
    .then(function (response) {
      if (!response.ok) {
        throw new Error('topojson load failed');
      }
      return response.json();
    })
    .then(function (world) {
      var countries = topojson.feature(world, world.objects.countries);
      var features = countries.features.filter(function (feature) {
        return !isAntarctica(feature);
      });
      var land = { type: 'FeatureCollection', features: features };
      var padding = 4;
      var projection = d3
        .geoNaturalEarth1()
        .fitExtent(
          [
            [padding, padding],
            [width - padding, height - padding],
          ],
          land
        );
      var path = d3.geoPath(projection);

      d3.select(landGroup)
        .selectAll('path')
        .data(features)
        .join('path')
        .attr('class', 'about-global__country')
        .attr('d', path);

      positionMarkers(projection);
      mapSvg.classList.add('is-map-ready');
    })
    .catch(function () {
      showFallback();
    });
})();
