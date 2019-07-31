
<?php error_reporting(E_ALL); ?>
<!-- widget Statistik -->
<style type="text/css">
  .highcharts-xaxis-labels tspan {font-size: 8px;}
  .keuangan-chart-label{
    color: #333;
    text-align: center;
    width: 100%;
    min-height: 40px;
    padding: 0;
  }

  g{
    /*display: none;*/
  }

  g.highcharts-series-group{
    /*display: inline;*/
  }

  #keuangan-title{
    font-size: 18px;
    font-weight: bold;
    text-align: center;
    padding-bottom: 16px;
  }

  .highcharts-subtitle {
    font-family: 'Courier New', monospace;
    /*font-style: italic;*/
    font-size: 10px;
    padding-bottom: 20px;
    /*fill: #000;*/
  }
</style>
<div class="box box-info box-solid">
  <div class="box-header">
    <h3 class="box-title"><a href="<?= site_url("first/keuangan/1")?>"><i class="fa fa-bar-chart"></i> Statistik Keuangan Desa</a></h3>
  </div>
  <div class="box-body">
    <div class="col-md-12 keuangan-selector" style="text-align: center; padding-bottom: 20px">
      Data tahun <select id="keuangan-selector">
        <option value="2016">2016</option>
        <option value="2017">2017</option>
      </select>
    </div>
    <div id="graph-container">
    </div>
  </div>
</div>

<?php
  //Realisasi Pelaksanaan APBD
  $raw_data = $this->keuangan_model->rp_apbd('1', '2016');
  
  $res_pelaksanaan = array();
  $nama = array(
    'PENDAPATAN' => '(PA) Pendapatan Desa',
    'BELANJA' => '(PA) Belanja Desa', 
    'PEMBIAYAAN' => '(PA) Pembiayaan Desa',
  );
  for ($i = 0; $i < count($raw_data['jenis_belanja']) / 2; $i++) { 
    $row = array(
      'jenis_belanja' => $raw_data['jenis_belanja'][$i]['Nama_Akun'],
      'anggaran' => $raw_data['anggaran'][$i]['AnggaranStlhPAK'],
      'realisasi' => $raw_data['realisasi'][$i]['Nilai'],
    );
    array_push($res_pelaksanaan, $row);
  }

  //Pendapatan APBD
  $raw_data = $this->keuangan_model->r_pd('1', '2016');
  $res_pendapatan = array();
  foreach ($raw_data['jenis_pendapatan'] as $r){
    $res_pendapatan[$r['Jenis']]['nama'] = $r['Nama_Jenis'];
  }

  foreach ($raw_data['anggaran'] as $r) {
    $res_pendapatan[$r['jenis_pendapatan']]['anggaran'] = $r['Pagu'];
  }

  foreach ($raw_data['realisasi'] as $r) {
    $res_pendapatan[$r['jenis_pendapatan']]['realisasi'] = $r['Pagu'];
  }

  //Belanja APBD
  $raw_data = $this->keuangan_model->r_bd('1', '2016');
  $res_belanja = array();
  foreach ($raw_data['bidang'] as $r){
    $res_belanja[$r['Kd_Bid']]['nama'] = $r['Nama_Bidang'];
  }

  foreach ($raw_data['anggaran'] as $r) {
    $res_belanja[$r['Kd_Bid']]['anggaran'] = $r['Pagu'];
  }

  foreach ($raw_data['realisasi'] as $r) {
    $res_belanja[$r['Kd_Bid']]['realisasi'] = $r['Nilai'];
  }
?>

<script type="text/javascript">
  function displayPelaksanaan(){
    <?php $i = 0; foreach ($res_pelaksanaan as $data):?>
      $("#graph-container").append("<div id='graph-<?= $i ?>'></div>");
      Highcharts.chart('graph-<?= $i ?>', {
          chart: {
              type: 'bar',
              margin: 0,
              height: 140
          },
          title: {
              text: ''
          },
          subtitle: {
            text: '<?= $nama[$data['jenis_belanja']] ?>',
            y: 4,
            style: {"color" : "#000"},
          },

          xAxis: {
              visible: false,
              categories: ['<?= $nama[$data['jenis_belanja']] ?>'],
          },
          tooltip: {
              valueSuffix: ''
          },
          plotOptions: {
              bar: {
                  dataLabels: {
                      enabled: true
                  }
              }
          },
          credits: {
            enabled: false
          },
          yAxis: {
            visible: false
          },
          exporting: {
            enabled: false
          },
          legend: {
            enabled: false
          },
          series: [{
              name: 'Anggaran',
              color: '#2E8B57',
              data: [<?= $data['anggaran'] ? $data['anggaran'] : 0 ?>]
              // data: 100,
          }, {
              name: 'Realisasi',
              color: '#FFD700',
              data: [<?= $data['realisasi'] ? $data['realisasi'] : 0 ?>],
              // data: 200,
          }]
      });
    <?php $i++; endforeach; ?>
  }

  function displayPendapatan() {
    <?php $i = 0; foreach ($res_pendapatan as $data):?>
      $("#graph-container").append("<div id='graph-<?= $i ?>'></div>");
      Highcharts.chart('graph-<?= $i ?>', {
          chart: {
              type: 'bar',
              margin: 0,
              height: 120
          },
          title: {
              text: ''
          },
          subtitle: {
            text: '<?= $data['nama'] ?>',
            y: -2,
            style: {"color" : "#000"},
          },

          xAxis: {
              visible: false,
              categories: ['<?= $data['nama'] ?>'],
          },
          tooltip: {
              valueSuffix: ''
          },
          plotOptions: {
              bar: {
                  dataLabels: {
                      enabled: true
                  }
              }
          },
          credits: {
            enabled: false
          },
          yAxis: {
            visible: false
          },
          exporting: {
            enabled: false
          },
          legend: {
            enabled: false
          },
          series: [{
              name: 'Anggaran',
              color: '#2E8B57',
              data: [<?= $data['anggaran'] ? $data['anggaran'] : 0 ?>]
              // data: 100,
          }, {
              name: 'Realisasi',
              color: '#FFD700',
              data: [<?= $data['realisasi'] ? $data['realisasi'] : 0 ?>],
              // data: 200,
          }]
      });
    <?php $i++; endforeach; ?>
  }

  function displayBelanja(){
    <?php $i = 0; foreach ($res_belanja as $data):?>
      $("#graph-container").append("<div id='graph-<?= $i ?>'></div>");
      Highcharts.chart('graph-<?= $i ?>', {
          chart: {
              type: 'bar',
              margin: 0,
              height: 120
          },
          title: {
              text: ''
          },
          subtitle: {
            text: '<?= $data['nama'] ?>',
            y: -2,
            style: {"color" : "#000"},
          },

          xAxis: {
              visible: false,
              categories: ['<?= $data['nama'] ?>'],
          },
          tooltip: {
              valueSuffix: ''
          },
          plotOptions: {
              bar: {
                  dataLabels: {
                      enabled: true
                  }
              }
          },
          credits: {
            enabled: false
          },
          yAxis: {
            visible: false
          },
          exporting: {
            enabled: false
          },
          legend: {
            enabled: false
          },
          series: [{
              name: 'Anggaran',
              color: '#2E8B57',
              data: [<?= $data['anggaran'] ? $data['anggaran'] : 0 ?>],
          }, {
              name: 'Realisasi',
              color: '#FFD700',
              data: [<?= $data['realisasi'] ? $data['realisasi'] : 0 ?>],
          }]
      });
    <?php $i++; endforeach; ?>
  }

  function resetContainer(){
    $("#graph-container").html("");
  }

	$(document).ready(function (){
    //Realisasi Pelaksanaan APBD
    displayBelanja();
	});
</script>
<!-- Highcharts -->
<script src="<?= base_url()?>assets/js/highcharts/highcharts.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/exporting.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/highcharts-more.js"></script>
