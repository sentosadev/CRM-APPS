<script>
  am4core.ready(function() {

    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart = am4core.create("performanceOverview", am4charts.XYChart);

    // Add data
    chart.data = [{
        "xAxis": "Result",
        "hot_prospect": 5,
        "med_prospect": 5,
      }, {
        "xAxis": "Contacted",
        "hot_prospect": 8,
        "med_prospect": 9,
        "low_prospect": 8,
      },
      {
        "xAxis": "Dispatch D",
        "hot_prospect": 100,
        "med_prospect": 150,
        "low_prospect": 150,
      }, {
        "xAxis": "Leads",
        "customer": 500
      }, {
        "xAxis": "Jumlah Source",
        "customer": 1000,
      }
    ];

    chart.legend = new am4charts.Legend();
    chart.legend.position = "bottom";
    chart.legend.maxHeight = 50;
    chart.legend.scrollable = true;

    // Create axes
    var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "xAxis";
    categoryAxis.renderer.grid.template.opacity = 0;

    var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
    valueAxis.min = 0;
    valueAxis.renderer.grid.template.opacity = 0;
    valueAxis.renderer.ticks.template.strokeOpacity = 0.5;
    valueAxis.renderer.ticks.template.stroke = am4core.color("#495C43");
    valueAxis.renderer.ticks.template.length = 5;
    valueAxis.renderer.line.strokeOpacity = 0.5;
    valueAxis.renderer.baseGrid.disabled = true;
    valueAxis.renderer.minGridDistance = 40;
    // valueAxis.renderer.cellStartLocation = 0;
    // valueAxis.renderer.cellEndLocation = 1;

    // Create series
    function createSeries(field, name) {
      var series = chart.series.push(new am4charts.ColumnSeries());
      series.dataFields.valueX = field;
      series.dataFields.categoryY = "xAxis";
      series.stacked = true;
      series.name = name;
      series.columns.template.tooltipText = "{name}: [bold]{valueY}[/]";

      var labelBullet = series.bullets.push(new am4charts.LabelBullet());
      labelBullet.locationX = 0.5;
      labelBullet.label.text = "{valueX}";
      labelBullet.label.fill = am4core.color("#fff");
    }

    createSeries("customer", "Customer");
    createSeries("hot_prospect", "Hot Prospect");
    createSeries("med_prospect", "Med Prospect");
    createSeries("low_prospect", "Low Prospect");
    createSeries("deal", "Deal");
    createSeries("need_fu", "Need FU");
    createSeries("not_deal", "Not Deal");

  }); // end am4core.ready()
</script>