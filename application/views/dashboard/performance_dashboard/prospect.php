<script>
  // Create chart instance
  var container = am4core.create("prospect", am4core.Container);
  container.width = am4core.percent(100);
  container.height = am4core.percent(100);
  container.layout = "horizontal";

  function createChart(data, legend = false) {

    // Create chart
    var chart = container.createChild(am4charts.PieChart);

    // Add data
    chart.data = data;

    // Add and configure Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "value";
    pieSeries.dataFields.category = "key";
    pieSeries.labels.template.disabled = true;
    pieSeries.ticks.template.disabled = true;
    pieSeries.innerRadius = am4core.percent(50);

    if (legend == true) {
      // pieSeries.legend = new am4charts.Legend();
      // pieSeries.legend.position = "bottom";
      // pieSeries.legend.maxHeight = 50;
      // pieSeries.legend.scrollable = true;
      // console.log(pieSeries.legend)
    }
  };

  createChart([{
    "key": "Deal",
    "value": 20
  }, {
    "key": "Need FU",
    "value": 50
  }, {
    "key": "Not Deal",
    "value": 30
  }], true);

  createChart([{
    "key": "Deal",
    "value": 20
  }, {
    "key": "Need FU",
    "value": 50
  }, {
    "key": "Not Deal",
    "value": 30
  }]);

  createChart([{
    "key": "Deal",
    "value": 20
  }, {
    "key": "Need FU",
    "value": 50
  }, {
    "key": "Not Deal",
    "value": 30
  }]);
</script>