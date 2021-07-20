<?php
session_start();

//ambil nilai
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");
require("../inc/cek/admpeg.php");
$tpl = LoadTpl("../template/adminpeg.html");


nocache;

//nilai
$filenya = "index.php";
$e_nip = balikin($_SESSION['nip9_session']);
$e_nama = balikin($_SESSION['nama9_session']);



$judul = "$e_nama [NIP.$e_nip]";
$judulku = "$judul";





//jml total sppd
$qyuk = mysqli_query($koneksi, "SELECT SUM(total_semuanya) AS totalnya ".
									"FROM t_spt_pegawai ".
									"WHERE peg_nip = '$e_nip'");
$ryuk = mysqli_fetch_assoc($qyuk);
$total_sppd = balikin($ryuk['totalnya']);





//jml total sppd terakhir
$qyuk = mysqli_query($koneksi, "SELECT total_semuanya AS totalnya ".
									"FROM t_spt_pegawai ".
									"WHERE peg_nip = '$e_nip' ".
									"ORDER BY postdate DESC");
$ryuk = mysqli_fetch_assoc($qyuk);
$jml_sppd_terakhir = balikin($ryuk['totalnya']);






	
//jml login
$qyuk = mysqli_query($koneksi, "SELECT * FROM user_login ".
									"WHERE nip = '$e_nip'");
$ryuk = mysqli_fetch_assoc($qyuk);
$jml_login = mysqli_num_rows($qyuk);


//jml sppd
$qyuk = mysqli_query($koneksi, "select * FROM t_spt_pegawai ".
									"WHERE peg_nip = '$e_nip'");
$ryuk = mysqli_fetch_assoc($qyuk);
$jml_sppd = mysqli_num_rows($qyuk);



//sppd terakhir
$qyuk = mysqli_query($koneksi, "select * FROM t_spt_pegawai ".
									"WHERE peg_nip = '$e_nip' ".
									"ORDER BY spt_tgl DESC");
$ryuk = mysqli_fetch_assoc($qyuk);
$sppd_tujuan = balikin($ryuk['tujuan']);
$sppd_tgl_dari = balikin($ryuk['tgl_dari']);
$sppd_tgl_sampai = balikin($ryuk['tgl_sampai']);
$sppd_jml_lama = balikin($ryuk['jml_lama']);













//isi *START
ob_start();


//ketahui ordernya...
$qyuk = mysqli_query($koneksi, "SELECT DISTINCT(pegawai_nip) AS kodeku ".
						"FROM user_history_gps ".
						"WHERE lat_x <> '' ". 
						"AND pegawai_nip = '$e_nip' ".
						"ORDER BY postdate DESC LIMIT 0,1");
$ryuk = mysqli_fetch_assoc($qyuk);


do
	{
	//nilai
	$yuk_nrp = balikin($ryuk['kodeku']);
	
	
	//detailnya
	$qyuk2 = mysqli_query($koneksi, "SELECT * FROM user_history_gps ".
						"WHERE lat_x <> '' ".
						"AND pegawai_nip = '$e_nip' ". 
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
						"AND pegawai_nip = '$e_nip' ".
						"ORDER BY postdate DESC LIMIT 0,1");
$ryuk = mysqli_fetch_assoc($qyuk);


do
	{
	//nilai
	$yuk_nrp = balikin($ryuk['kodeku']);
	
	
	//detailnya
	$qyuk2 = mysqli_query($koneksi, "SELECT * FROM user_history_gps ".
						"WHERE lat_x <> '' ".
						"AND pegawai_nip = '$e_nip' ". 
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
     





              
                  <!-- Info boxes -->
      <div class="row">

					
        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-briefcase"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">JUMLAH LOGIN</span>
              <span class="info-box-number"><?php echo $jml_login;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->



        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-orange"><i class="fa fa-tasks"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">JUMLAH SPPD</span>
              <span class="info-box-number"><?php echo $jml_sppd;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        

        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL SPPD</span>
              <span class="info-box-number"><?php echo xduit2($total_sppd);?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->



        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">SPPD TERAKHIR</span>
              <span class="info-box-number"><?php echo xduit2($jml_sppd_terakhir);?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        




        <!-- /.col -->
	        <div class="col-md-8 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-green"><i class="fa fa-tachometer"></i></span>
	
	            <div class="info-box-content">
	              <span class="info-box-text">SPPD TERAKHIR, TUJUAN</span>
	              <span class="info-box-number"><?php 
	              echo "$sppd_tujuan. 
	              <br>
	              $sppd_jml_lama Hari. 
	              $sppd_tgl_dari sampai $sppd_tgl_sampai";
	              
	              ?></span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>
	        <!-- /.col -->





        <!-- /.col -->
      </div>
      <!-- /.row -->


            
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