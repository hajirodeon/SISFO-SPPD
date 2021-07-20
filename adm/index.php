<?php
//////////////////////////////////////////////////////////////////////
// SISFO-SPPD, Sistem Informasi Surat Perintah Perjalanan Dinas.    //
// Cocok untuk kantor - kantor yang membutuhkan pengarsipan surat   //
// perintah tugas perjalanan dinas.                                 //
//////////////////////////////////////////////////////////////////////
// Dikembangkan oleh : Agus Muhajir                                 //
// E-Mail : hajirodeon@gmail.com                                    //
// HP/SMS/WA : 081-829-88-54                                        //
// source code : http://github.com/hajirodeon                       //
//////////////////////////////////////////////////////////////////////



session_start();

//ambil nilai
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");
require("../inc/cek/adm.php");
require("../inc/class/paging.php");
$tpl = LoadTpl("../template/admin.html");


nocache;

//nilai
$filenya = "index.php";
$judul = "Admin Web";
$judulku = "$judul  [$adm_session]";







//jml pegawai
$qyuk = mysqli_query($koneksi, "SELECT * FROM m_pegawai");
$jml_pegawai = mysqli_num_rows($qyuk);


//jml murni
$qyuk = mysqli_query($koneksi, "SELECT SUM(murni) AS totalnya ".
									"FROM t_kegiatan_anggaran");
$ryuk = mysqli_fetch_assoc($qyuk);
$jml_murni = balikin($ryuk['totalnya']);




//jml perubahan
$qyuk = mysqli_query($koneksi, "SELECT SUM(perubahan) AS totalnya ".
									"FROM t_kegiatan_anggaran");
$ryuk = mysqli_fetch_assoc($qyuk);
$jml_perubahan = balikin($ryuk['totalnya']);
$jml_total = $jml_murni + $jml_perubahan;




//jml realisasi
$qyuk = mysqli_query($koneksi, "SELECT SUM(total_semuanya) AS totalnya ".
									"FROM t_spt_pegawai");
$ryuk = mysqli_fetch_assoc($qyuk);
$jml_realisasi = balikin($ryuk['totalnya']);
$jml_sisa = $jml_total - $jml_realisasi;











//isi *START
ob_start();


echo '<div class="row">

  <div class="col-lg-12">
    <div class="info-box mb-3 bg-success">
      <span class="info-box-icon"><i class="fas fa-user-secret"></i></span>

      <div class="info-box-content">
        <span class="info-box-number">
        		ADMIN SPPD
			</span>
      </div>
    </div>

	</div>
</div>';




//isi
$judulku = ob_get_contents();
ob_end_clean();
              









//jml tujuan
$qyuk = mysqli_query($koneksi, "SELECT kd FROM t_spt ".
									"WHERE tujuan <> ''");
$jml_tujuan = mysqli_num_rows($qyuk);




//jml spt 
$qyuk = mysqli_query($koneksi, "SELECT kd FROM t_spt");
$jml_spt = mysqli_num_rows($qyuk);




//jml spt panjar
$qyuk = mysqli_query($koneksi, "SELECT kd FROM t_spt ".
									"WHERE status IS NULL OR status = 'Panjar'");
$jml_spt_panjar = mysqli_num_rows($qyuk);








//jml spt rampung
$qyuk = mysqli_query($koneksi, "SELECT kd FROM t_spt ".
									"WHERE status = 'Rampung'");
$jml_spt_rampung = mysqli_num_rows($qyuk);













//postdate entri
$qyuk = mysqli_query($koneksi, "SELECT * FROM t_spt ".
									"ORDER BY postdate DESC");
$ryuk = mysqli_fetch_assoc($qyuk);
$yuk_entri_terakhir = balikin($ryuk['postdate']);






//postdate entri
$qyuk = mysqli_query($koneksi, "SELECT * FROM user_login ".
									"ORDER BY postdate DESC");
$ryuk = mysqli_fetch_assoc($qyuk);
$yuk_login_terakhir = balikin($ryuk['postdate']);






//postdate entri
$qyuk = mysqli_query($koneksi, "SELECT DISTINCT(lat_x) AS lokasiku ".
									"FROM user_history_gps ".
									"WHERE lat_x <> '' ".
									"ORDER BY postdate DESC");
$ryuk = mysqli_fetch_assoc($qyuk);
$jml_gps = mysqli_num_rows($qyuk);























//isi *START
ob_start();



//jml notif
$qyuk = mysqli_query($koneksi, "SELECT * FROM user_history ".
									"WHERE dibaca = 'false'");
$jml_notif = mysqli_num_rows($qyuk);

echo $jml_notif;

//isi
$i_loker = ob_get_contents();
ob_end_clean();













//isi *START
ob_start();

//tanggal sekarang
$m = date("m");
$de = date("d");
$y = date("Y");

//ambil 14hari terakhir
for($i=0; $i<=14; $i++)
	{
	$nilku = date('Ymd',mktime(0,0,0,$m,($de-$i),$y)); 

	echo "$nilku, ";
	}


//isi
$isi_data1 = ob_get_contents();
ob_end_clean();










//isi *START
ob_start();

//tanggal sekarang
$m = date("m");
$de = date("d");
$y = date("Y");

//ambil 14hari terakhir
for($i=0; $i<=14; $i++)
	{
	$nilku = date('Y-m-d',mktime(0,0,0,$m,($de-$i),$y)); 


	//pecah
	$ipecah = explode("-", $nilku);
	$itahun = trim($ipecah[0]);  
	$ibln = trim($ipecah[1]);
	$itgl = trim($ipecah[2]);    


	//ketahui ordernya...
	$qyuk = mysqli_query($koneksi, "SELECT * FROM user_login ".
							"WHERE round(DATE_FORMAT(postdate, '%d')) = '$itgl' ".
							"AND round(DATE_FORMAT(postdate, '%m')) = '$ibln' ".
							"AND round(DATE_FORMAT(postdate, '%Y')) = '$itahun'");
	$tyuk = mysqli_num_rows($qyuk);
	
	if (empty($tyuk))
		{
		$tyuk = "1";
		}
		
	echo "$tyuk, ";
	}


//isi
$isi_data2 = ob_get_contents();
ob_end_clean();










//isi *START
ob_start();

//tanggal sekarang
$m = date("m");
$de = date("d");
$y = date("Y");

//ambil 14hari terakhir
for($i=0; $i<=14; $i++)
	{
	$nilku = date('Y-m-d',mktime(0,0,0,$m,($de-$i),$y)); 


	//pecah
	$ipecah = explode("-", $nilku);
	$itahun = trim($ipecah[0]);  
	$ibln = trim($ipecah[1]);
	$itgl = trim($ipecah[2]);    


	//ketahui 
	$qyuk = mysqli_query($koneksi, "SELECT * FROM t_spt ".
							"WHERE round(DATE_FORMAT(spt_tgl, '%d')) = '$itgl' ".
							"AND round(DATE_FORMAT(spt_tgl, '%m')) = '$ibln' ".
							"AND round(DATE_FORMAT(spt_tgl, '%Y')) = '$itahun'");
	$tyuk = mysqli_num_rows($qyuk);
	
	if (empty($tyuk))
		{
		$tyuk = "1";
		}
		
	echo "$tyuk, ";
	}


//isi
$isi_data3 = ob_get_contents();
ob_end_clean();



















//isi *START
ob_start();


//ketahui ordernya...
$qyuk = mysqli_query($koneksi, "SELECT DISTINCT(pegawai_nip) AS kodeku ".
						"FROM user_history_gps ".
						"WHERE lat_x <> '' ". 
						"ORDER BY postdate DESC LIMIT 0,100");
$ryuk = mysqli_fetch_assoc($qyuk);


do
	{
	//nilai
	$yuk_nrp = balikin($ryuk['kodeku']);
	
	
	//detailnya
	$qyuk2 = mysqli_query($koneksi, "SELECT * FROM user_history_gps ".
						"WHERE lat_x <> '' ".
						"AND pegawai_nip = '$yuk_nrp' ". 
						"ORDER BY postdate DESC LIMIT 0,1");
	$ryuk2 = mysqli_fetch_assoc($qyuk2);
	$yuk2_latx = balikin($ryuk2['lat_x']);
	$yuk2_laty = balikin($ryuk2['lat_y']);
	$yuk2_nama = balikin($ryuk2['pegawai_nama']);
		
	
	
	echo "['$yuk_nrp, $yuk2_nama', $yuk2_latx,$yuk2_laty],";
	}
while ($ryuk = mysqli_fetch_assoc($qyuk));



//isi
$isi_gps2 = ob_get_contents();
ob_end_clean();












//isi *START
ob_start();


//ketahui ordernya...
$qyuk = mysqli_query($koneksi, "SELECT DISTINCT(pegawai_nip) AS kodeku ".
						"FROM user_history_gps ".
						"WHERE lat_x <> '' ". 
						"ORDER BY postdate DESC LIMIT 0,100");
$ryuk = mysqli_fetch_assoc($qyuk);


do
	{
	//nilai
	$yuk_nrp = balikin($ryuk['kodeku']);
	
	
	//detailnya
	$qyuk2 = mysqli_query($koneksi, "SELECT * FROM user_history_gps ".
						"WHERE lat_x <> '' ".
						"AND pegawai_nip = '$yuk_nrp' ". 
						"ORDER BY postdate DESC LIMIT 0,1");
	$ryuk2 = mysqli_fetch_assoc($qyuk2);
	$yuk2_latx = balikin($ryuk2['lat_x']);
	$yuk2_laty = balikin($ryuk2['lat_y']);
	$yuk2_nama = balikin($ryuk2['pegawai_nama']);
	$yuk2_postdate = balikin($ryuk2['postdate']);
		
	


	echo "['<div class=\"info_content\">' +
        '<h3>$yuk2_nama</h3>' +
        '<p>$yuk_nrp. $yuk2_nama</p>' +
        '<p>$yuk2_postdate</p>' +        
        '</div>'],";	
	}
while ($ryuk = mysqli_fetch_assoc($qyuk));



//isi
$isi_gps3 = ob_get_contents();
ob_end_clean();

















//isi *START
ob_start();



for ($k=1;$k<=12;$k++)
	{
	$nilku = $arrbln2[$k];
	echo "'$nilku', ";
	}


//isi
$isi_bln3 = ob_get_contents();
ob_end_clean();








//isi *START
ob_start();



for ($k=1;$k<=12;$k++)
	{
	$ubln2 = "$k";

	//detailnya
	$qyuk2 = mysqli_query($koneksi, "SELECT kd FROM t_spt ".
									"WHERE (status IS NULL OR status <> 'Rampung') ".
									"AND round(DATE_FORMAT(postdate, '%m')) = '$ubln2' ".
									"AND round(DATE_FORMAT(postdate, '%Y')) = '$tahun'");
	$tyuk2 = mysqli_num_rows($qyuk2);

	 
	echo "$tyuk2, ";
	}


//isi
$isi_bln3_panjar = ob_get_contents();
ob_end_clean();







//isi *START
ob_start();



for ($k=1;$k<=12;$k++)
	{
	$ubln2 = "$k";

	//detailnya
	$qyuk2 = mysqli_query($koneksi, "SELECT kd FROM t_spt ".
									"WHERE status = 'Rampung' ".
									"AND round(DATE_FORMAT(postdate, '%m')) = '$ubln2' ".
									"AND round(DATE_FORMAT(postdate, '%Y')) = '$tahun'");
	$tyuk2 = mysqli_num_rows($qyuk2);

	echo "$tyuk2, ";
	}


//isi
$isi_bln3_rampung = ob_get_contents();
ob_end_clean();











//isi *START
ob_start();


?>







<style>
	#map_wrapper {
    height: 400px;
}

#map_canvas {
    width: 100%;
    height: 100%;
}
</style>


<div id="map_wrapper">
    <div id="map_canvas" class="mapping"></div>
</div>




<script>
	jQuery(function($) {
    // Asynchronously Load the map API 
    var script = document.createElement('script');
    script.src = "//maps.googleapis.com/maps/api/js?key=<?php echo $keyku;?>&sensor=false&callback=initialize";
    document.body.appendChild(script);
});

function initialize() {
    var map;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        mapTypeId: 'roadmap'
    };
                    
    // Display a map on the page
    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
    map.setTilt(45);
        
    // Multiple Markers
    var markers = [<?php echo $isi_gps2;?>];
                        
    // Info Window Content
    var infoWindowContent = [<?php echo $isi_gps3;?>
    ];
        
    // Display multiple markers on a map
    var infoWindow = new google.maps.InfoWindow(), marker, i;
    
    // Loop through our array of markers & place each one on the map  
    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: markers[i][0]
        });
        
        // Allow each marker to have an info window    
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.open(map, marker);
            }
        })(marker, i));

        // Automatically center the map fitting all markers on the screen
        map.fitBounds(bounds);
    }

    // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(14);
        google.maps.event.removeListener(boundsListener);
    });
    
}
</script>
     

<br>     
     
     
     
     
     
     
     
     
<div class="row">

<div class="col-md-8">
 
     
		<!-- Info boxes -->
      <div class="row">

        <!-- /.col -->
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">PEGAWAI</span>
              <span class="info-box-number"><?php echo $jml_pegawai;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->




        <!-- /.col -->
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-building"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TUJUAN</span>
              <span class="info-box-number"><?php echo $jml_tujuan;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->





        <!-- /.col -->
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-orange"><i class="fa fa-briefcase"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">SPT</span>
              <span class="info-box-number"><?php echo $jml_spt;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->




        <!-- /.col -->
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-orange"><i class="fa fa-briefcase"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">SPT PANJAR</span>
              <span class="info-box-number"><?php echo $jml_spt_panjar;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->



        <!-- /.col -->
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-orange"><i class="fa fa-briefcase"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">SPT RAMPUNG</span>
              <span class="info-box-number"><?php echo $jml_spt_rampung;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->



        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-calendar"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">LOGIN TERAKHIR</span>
              <span class="info-box-number"><?php echo $yuk_login_terakhir;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        





        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-calendar-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">ENTRI TERAKHIR</span>
              <span class="info-box-number"><?php echo $yuk_entri_terakhir;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->


        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fa fa-map"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">GPS</span>
              <span class="info-box-number"><?php echo $jml_gps;?> Lokasi</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        
                
      </div>
      <!-- /.row -->

	</div>

	<div class="col-md-4">

        <!-- Info Boxes Style 2 -->
            <div class="info-box mb-3 bg-danger">
              <span class="info-box-icon"><i class="fas fa-tag"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">ANGGARAN MURNI</span>
                <span class="info-box-number"><?php echo xduit3($jml_murni);?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            <div class="info-box mb-3 bg-success">
              <span class="info-box-icon"><i class="fas fa-tag"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">ANGGARAN PERUBAHAN</span>
                <span class="info-box-number"><?php echo xduit3($jml_perubahan);?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            <!-- /.info-box -->
            <div class="info-box mb-3 bg-info">
              <span class="info-box-icon"><i class="fas fa-tag"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">REALISASI ANGGARAN</span>
                <span class="info-box-number"><?php echo xduit3($jml_realisasi);?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            
            <!-- /.info-box -->
            <div class="info-box mb-3 bg-warning">
              <span class="info-box-icon"><i class="fas fa-tag"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">SISA ANGGARAN</span>
                <span class="info-box-number"><?php echo xduit3($jml_sisa);?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            

		
	</div>



</div>
 
     


      <div class="row">
        <div class="col-md-12">


			<script>
				$(function () {
				  'use strict'
				
				  var ticksStyle = {
				    fontColor: '#495057',
				    fontStyle: 'bold'
				  }
				
				  var mode      = 'index'
				  var intersect = true
				
				  var $salesChart = $('#sales-chart')
				  var salesChart  = new Chart($salesChart, {
				    type   : 'bar',
				    data   : {
				      labels  : [<?php echo $isi_bln3;?>],
				      datasets: [
				        {
				          backgroundColor: 'red',
				          borderColor    : 'red',
				          data           : [<?php echo $isi_bln3_panjar;?>]
				        },
				        {
				          backgroundColor: 'green',
				          borderColor    : 'green',
				          data           : [<?php echo $isi_bln3_rampung;?>]
				        }
				      ]
				    },
				    options: {
				      maintainAspectRatio: false,
				      tooltips           : {
				        mode     : mode,
				        intersect: intersect
				      },
				      hover              : {
				        mode     : mode,
				        intersect: intersect
				      },
				      legend             : {
				        display: false
				      },
				      scales             : {
				        yAxes: [{
				          // display: false,
				          gridLines: {
				            display      : true,
				            lineWidth    : '4px',
				            color        : 'rgba(0, 0, 0, .2)',
				            zeroLineColor: 'transparent'
				          },
				          ticks    : $.extend({
				            beginAtZero: true,
				
				            // Include a dollar sign in the ticks
				            callback: function (value, index, values) {
				              if (value >= 1000) {
				                value /= 1000
				                value += 'k'
				              }
				              return '' + value
				            }
				          }, ticksStyle)
				        }],
				        xAxes: [{
				          display  : true,
				          gridLines: {
				            display: false
				          },
				          ticks    : ticksStyle
				        }]
				      }
				    }
				  })
				
				  var $visitorsChart = $('#visitors-chart')
				  var visitorsChart  = new Chart($visitorsChart, {
				    data   : {
				      labels  : [<?php echo $isi_data1;?>],
				      datasets: [{
				        type                : 'line',
				        data                : [<?php echo $isi_data2;?>],
				        backgroundColor     : 'transparent',
				        borderColor         : '#007bff',
				        pointBorderColor    : '#007bff',
				        pointBackgroundColor: '#007bff',
				        fill                : false
				        // pointHoverBackgroundColor: '#007bff',
				        // pointHoverBorderColor    : '#007bff'
				      },
				        {
				          type                : 'line',
				          data                : [<?php echo $isi_data3;?>],
				          backgroundColor     : 'tansparent',
				          borderColor         : '#ced4da',
				          pointBorderColor    : '#ced4da',
				          pointBackgroundColor: '#ced4da',
				          fill                : false
				          // pointHoverBackgroundColor: '#ced4da',
				          // pointHoverBorderColor    : '#ced4da'
				        }]
				    },
				    options: {
				      maintainAspectRatio: false,
				      tooltips           : {
				        mode     : mode,
				        intersect: intersect
				      },
				      hover              : {
				        mode     : mode,
				        intersect: intersect
				      },
				      legend             : {
				        display: false
				      },
				      scales             : {
				        yAxes: [{
				          // display: false,
				          gridLines: {
				            display      : true,
				            lineWidth    : '4px',
				            color        : 'rgba(0, 0, 0, .2)',
				            zeroLineColor: 'transparent'
				          },
				          ticks    : $.extend({
				            beginAtZero : true,
				            suggestedMax: 200
				          }, ticksStyle)
				        }],
				        xAxes: [{
				          display  : true,
				          gridLines: {
				            display: false
				          },
				          ticks    : ticksStyle
				        }]
				      }
				    }
				  })
				})

			</script>






		<div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">History Online</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">



                <div class="position-relative mb-4">
                  <canvas id="visitors-chart" height="200"></canvas>
                </div>

                <div class="d-flex flex-row justify-content-end">
                  <span class="mr-2">
                    <i class="fas fa-square text-primary"></i> LOGIN
                  </span>

                  <span>
                    <i class="fas fa-square text-gray"></i> TANGGAL SPPD
                  </span>
                </div>


                
                
              </div>
            </div>





			<div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">JUMLAH STATUS SPT SPPD</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>

              <div class="card-body">

                <div class="position-relative mb-4">
                  <canvas id="sales-chart" height="200"></canvas>
                </div>

                <div class="d-flex flex-row justify-content-end">
                  <span class="mr-2">
                    <i class="fas fa-square text-red"></i> PANJAR 
                  </span>

                  <span class="mr-2">
                    <i class="fas fa-square text-green"></i> RAMPUNG 
                  </span>
                </div>
              </div>
            </div>

			<br>




		<div class="row">
          <div class="col-lg-8">

			<?php
			$sqlcount = "SELECT * FROM user_history ".
							"ORDER BY postdate DESC";

			//query
			$p = new Pager();
			$start = $p->findStart($limit);
			
			$sqlresult = $sqlcount;
			
			$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
			$pages = $p->findPages($count, $limit);
			$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
			$pagelist = $p->pageList($_GET['page'], $pages, $target);
			$data = mysqli_fetch_array($result);
			?>
			
			
            <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">History Entri</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table m-0">
                    <thead>
                    <tr>
                      <th>POSTDATE</th>
                      <th>NIP</th>
                      <th>NAMA</th>
                      <th>JABATAN</th>
                      <th>MENU</th>
                      <th>AKTIVITAS</th>
                    </tr>
                    </thead>
                    <tbody>
                    	
                    <?php
                    	do 
							{
							if ($warna_set ==0)
								{
								$warna = $warna01;
								$warna_set = 1;
								}
							else
								{
								$warna = $warna02;
								$warna_set = 0;
								}
					
							$nomer = $nomer + 1;
							$i_kd = nosql($data['kd']);
							$i_postdate = balikin($data['postdate']);
							$i_nip = balikin($data['user_nip']);
							$i_nama = balikin($data['user_nama']);
							$i_jabatan = balikin($data['user_jabatan']);
							$i_menu_ket = balikin($data['menu_ket']);
							$i_perintah_sql = balikin($data['perintah_sql']);
						
						
							echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
							echo '<td>'.$i_postdate.'</td>
							<td>'.$i_nip.'</td>
							<td>'.$i_nama.'</td>
							<td>'.$i_jabatan.'</td>
							<td>'.$i_menu_ket.'</td>
							<td>'.$i_perintah_sql.'</td>
					        </tr>';
							}
						while ($data = mysqli_fetch_assoc($result));
						?>
						
						
                    </tbody>
                  </table>
                </div>
                <!-- /.table-responsive -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <a href="<?php echo $sumber;?>/adm/log/notif.php" class="btn btn-sm btn-danger float-right">SELENGKAPNYA >></a>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->


		</div>
		
		<div class="col-lg-4">

	
				<div class="card">
	              <div class="card-header border-transparent">
	                <h3 class="card-title">PEGAWAI :  PERINGKAT SPPD</h3>
	
	                <div class="card-tools">
	                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
	                    <i class="fas fa-minus"></i>
	                  </button>
	                </div>
	              </div>
	
	              <div class="card-body">
	
					<?php
					//total entri
					$qyuk2 = mysqli_query($koneksi, "SELECT DISTINCT(peg_kd) AS totalnya ".
														"FROM t_spt_pegawai ".
														"ORDER BY postdate DESC");
					$ryuk2 = mysqli_fetch_assoc($qyuk2);
					$jml2_entri = mysqli_num_rows($qyuk2);
					

					//kumpulkan data dulu
					$qdt = mysqli_query($koneksi, "SELECT kd FROM m_pegawai ".
													"ORDER BY RAND() LIMIT 0,1000");
					$rdt = mysqli_fetch_assoc($qdt);
				
					do
						{
						//nilai
						$dt_nrp = balikin($rdt['kd']);
						
						
						//detailnya
						$qku = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
														"WHERE kd = '$dt_nrp'");
						$rku = mysqli_fetch_assoc($qku);
						$dt_kd = balikin($rku['kd']);
						$dt_nip = balikin($rku['nip']);
						$dt_nama = balikin($rku['nama']);
						$dt_jabatan = balikin($rku['jabatan']);
					

						//jumlah entri
						$qyuk = mysqli_query($koneksi, "SELECT DISTINCT(peg_kd) AS totalnya ".
															"FROM t_spt_pegawai ".
															"WHERE peg_kd = '$dt_nrp' ".
															"ORDER BY postdate DESC");
						$ryuk = mysqli_fetch_assoc($qyuk);
						$jml_entri = mysqli_num_rows($qyuk);
						


						//set update jumlahnya
						mysqli_query($koneksi, "UPDATE m_pegawai SET jml_entri = '$jml_entri' ".
													"WHERE kd = '$dt_nrp'");


						}
					while ($rdt = mysqli_fetch_assoc($qdt));
						
					
					
					
					
					
					
					
					
					
					


					
					//list 
					$qdt = mysqli_query($koneksi, "SELECT kd FROM m_pegawai ".
													"ORDER BY round(jml_entri) DESC LIMIT 0,50");
					$rdt = mysqli_fetch_assoc($qdt);
				
					do
						{
						//nilai
						$dt_nox = $dt_nox + 1;
						$dt_nrp = balikin($rdt['kd']);
						
						
						//detailnya
						$qku = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
														"WHERE kd = '$dt_nrp'");
						$rku = mysqli_fetch_assoc($qku);
						$dt_kd = balikin($rku['kd']);
						$dt_nip = balikin($rku['nip']);
						$dt_nama = balikin($rku['nama']);
						$dt_jabatan = balikin($rku['jabatan']);
					

						//jumlah entri
						$qyuk = mysqli_query($koneksi, "SELECT DISTINCT(peg_kd) AS totalnya ".
															"FROM t_spt_pegawai ".
															"WHERE peg_kd = '$dt_nrp' ".
															"ORDER BY postdate DESC");
						$ryuk = mysqli_fetch_assoc($qyuk);
						$jml_entri = mysqli_num_rows($qyuk);
						


						//set update jumlahnya
						mysqli_query($koneksi, "UPDATE m_pegawai SET jml_entri = '$jml_entri' ".
													"WHERE kd = '$dt_nrp'");



						//persen
						$ku_persen = ($jml_entri / $jml2_entri) * 100; 

	                    echo '<div class="progress-group">
	                    '.$dt_nox.'. '.$dt_nama.' 
	                      <br>
	                      NIP.'.$dt_nip.'
	                      <br>
	                      '.$dt_jabatan.' 
	                      <span class="float-right"><b>'.$jml_entri.'</b>/'.$jml2_entri.'</span>
	                      <div class="progress progress-sm">
	                        <div class="progress-bar bg-success" style="width: '.$ku_persen.'%"></div>
	                      </div>
	                    </div>';
						}
					while ($rdt = mysqli_fetch_assoc($qdt));
						
					
					?>



	              </div>
	            </div>
	
				<br>
	




           </div>


      </div>
            







		<!-- OPTIONAL SCRIPTS -->
		<script src="../template/adminlte3/plugins/chart.js/Chart.min.js"></script>
		




	
	<script language='javascript'>
	//membuat document jquery
	$(document).ready(function(){
	
	$.noConflict();

	});
	
	</script>
	


            
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//isi
$isi = ob_get_contents();
ob_end_clean();




require("../inc/niltpl.php");

//diskonek
xfree($qbw);
xclose($koneksi);
exit();
?>