<script>
  function ajaxDataChartLeadsSourceEffectiveness() {
    values = {}
    $.ajax({
      beforeSend: function() {
        $('#leadsSourceEffectiveness').html('<i class="fa fa-spinner fa-spin"></i>');
      },
      enctype: 'multipart/form-data',
      url: '<?= site_url(get_controller() . '/chartLeadsSourceEffectiveness') ?>',
      type: "POST",
      data: values,
      // processData: false,
      // contentType: false,
      cache: false,
      dataType: 'JSON',
      success: function(response) {
        if (response.status == 1) {
          generatedChartLeadsSourceEffectiveness(response.data);
        }
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: '<font color="white">Peringatan</font>',
          html: '<font color="white">Telah terjadi kesalahan Saat Load Chart Leads Source Effectiveness !</font>',
          background: '#dd4b39',
          confirmButtonColor: '#cc3422',
          confirmButtonText: 'Tutup',
          iconColor: 'white'
        })
      }
    });
  }

  function generatedChartLeadsSourceEffectiveness(data) {
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // create chart
    var chart = am4core.create("leadsSourceEffectiveness", am4plugins_sunburst.Sunburst);
    chart.padding(0, 0, 0, 0);
    chart.radius = am4core.percent(98);

    chart.data = data;

    chart.colors.step = 2;
    chart.fontSize = 11;
    chart.innerRadius = am4core.percent(0);

    // define data fields
    chart.dataFields.value = "value";
    chart.dataFields.name = "name";
    chart.dataFields.children = "children";


    var level0SeriesTemplate = new am4plugins_sunburst.SunburstSeries();
    level0SeriesTemplate.hiddenInLegend = false;
    chart.seriesTemplates.setKey("0", level0SeriesTemplate)

    // this makes labels to be hidden if they don't fit
    level0SeriesTemplate.labels.template.truncate = true;
    level0SeriesTemplate.labels.template.hideOversized = true;
    level0SeriesTemplate.slices.template.tooltipText = '{name} - {value}';


    level0SeriesTemplate.labels.template.adapter.add("rotation", (rotation, target) => {
      target.maxWidth = target.dataItem.slice.radius - target.dataItem.slice.innerRadius - 10;
      target.maxHeight = Math.abs(target.dataItem.slice.arc * (target.dataItem.slice.innerRadius + target.dataItem.slice.radius) / 2 * am4core.math.RADIANS);

      return rotation;
    })


    var level1SeriesTemplate = level0SeriesTemplate.clone();
    chart.seriesTemplates.setKey("1", level1SeriesTemplate)
    level1SeriesTemplate.fillOpacity = 0.75;
    level1SeriesTemplate.hiddenInLegend = true;


    var level2SeriesTemplate = level0SeriesTemplate.clone();
    chart.seriesTemplates.setKey("2", level2SeriesTemplate)
    level2SeriesTemplate.fillOpacity = 0.5;
    level2SeriesTemplate.hiddenInLegend = true;

    chart.legend = new am4charts.Legend();
  }
</script>