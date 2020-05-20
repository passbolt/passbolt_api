class ReportWidget{
  static getStyleSheetPropertyValue(selectorText, propertyName) {
    // search backwards because the last match is more likely the right one
    for (let s= document.styleSheets.length - 1; s >= 0; s--) {
      const cssRules = document.styleSheets[s].cssRules ||
        document.styleSheets[s].rules || []; // IE support
      for (let c=0; c < cssRules.length; c++) {
        if (cssRules[c].selectorText === selectorText)
          return cssRules[c].style[propertyName];
      }
    }
    return null;
  }
}

class ReportGaugeWidget{
  constructor(options) {
    const color = options.color || "#636363";
    const radd = options.radd === undefined ? '' : options.radd;
    const value = options.value || 0;

    this.options = {
      chart: {
        height: 200,
        type: 'radialBar',
      },
      series: [value],
      labels: ['total'],

      plotOptions: {
        radialBar: {
          hollow: {
            margin: 15,
            size: "70%",
          },

          dataLabels: {
            showOn: "always",
            name: {
              show: false,
            },
            value: {
              offsetY: 7,
              color: "#000",
              fontSize: "25px",
              show: true,
              formatter: function (val) {
                return val + radd;
              }
            }
          }
        }
      },
      fill: {
        type: "solid",
        colors: [color]
      },
      stroke: {
        lineCap: "round",
      },
    };
  }

  getOptions() {
    return this.options;
  }

  render(elt) {
    const chart = new ApexCharts(elt[0], this.getOptions());
    chart.render();
  }
}


$(()=> {
  $('.report-widget.gauge').each((i, e) => {
    const $widgetEl = $(".widget-content", e);
    const cssColorsPath = '.report-widget .colors.';
    const color = $widgetEl.data('color');
    const graphColor = ReportWidget.getStyleSheetPropertyValue(cssColorsPath + color, 'color');

    // Get data properties from element.
    const options = {
      "value" : $widgetEl.data('value'),
      "radd" : $widgetEl.data('textradd'),
      "color" : graphColor
    };

    const gauge = new ReportGaugeWidget(options);
    gauge.render($widgetEl);
  });
});