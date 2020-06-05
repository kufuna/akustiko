function drawGeneralChart(cfg) {
  var stats = cfg.stats;
  var yMin = cfg.yMin;
  var yMax = cfg.yMax;
  var label = cfg.label;

  var plot = $.plot($(".chart"),
     stats, {
     series: {
         lines: { show: true },
         points: { show: true }
     },
     grid: { hoverable: true, clickable: true },
     yaxis: { min: yMin, max: yMax, minTickSize: 1, tickDecimals: false },
     xaxis: { mode: "time", minTickSize: [1, "day"], min: new Date(new Date().getTime() - 2592000000), max: new Date() }
   });

  function showTooltip(x, y, contents) {
      $('<div id="tooltip" class="tooltip">' + contents + '</div>').css( {
          position: 'absolute',
          display: 'none',
          top: y,
          left: x + 15,
		'z-index': '9999',
		'color': '#fff',
		'font-size': '11px',
          opacity: 0.8
      }).appendTo("body").fadeIn(200);
  }
  
  function renderDate(ts) {
    var date = new Date(ts);
    return date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
  }

  var previousPoint = null;
  $(".chart").bind("plothover", function (event, pos, item) {
      $("#x").text(pos.x.toFixed(2));
      $("#y").text(pos.y.toFixed(2));

      if ($(".chart").length > 0) {
          if (item) {
              if (previousPoint != item.dataIndex) {
                  previousPoint = item.dataIndex;
                  
                  $("#tooltip").remove();
                  var x = item.datapoint[0],
                      y = item.datapoint[1];
                  
                  showTooltip(item.pageX, item.pageY, y + ' ' + item.series.label + (label || " added in ") + renderDate(x));
              }
          }
          else {
              $("#tooltip").remove();
              previousPoint = null;
          }
      }
  });

  $(".chart").bind("plotclick", function (event, pos, item) {
      if (item) {
          $("#clickdata").text("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
          plot.highlight(item.series, item.datapoint);
      }
  });
}
