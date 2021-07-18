<?php
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
/////// SISFOKOL_SMA_v5.0_(PernahJaya)                          ///////
/////// (Sistem Informasi Sekolah untuk SMA)                    ///////
///////////////////////////////////////////////////////////////////////
/////// Dibuat oleh :                                           ///////
/////// Agus Muhajir, S.Kom                                     ///////
/////// URL 	:                                               ///////
///////     * http://omahbiasawae.com/                          ///////
///////     * http://sisfokol.wordpress.com/                    ///////
///////     * http://hajirodeon.wordpress.com/                  ///////
///////     * http://yahoogroup.com/groups/sisfokol/            ///////
///////     * http://yahoogroup.com/groups/linuxbiasawae/       ///////
/////// E-Mail	:                                               ///////
///////     * hajirodeon@yahoo.com                              ///////
///////     * hajirodeon@gmail.com                              ///////
/////// HP/SMS/WA : 081-829-88-54                               ///////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////



class Pager
{
var $target;
var $curpage;


//batas
function findStart($limit)
   	{
   	if ((!isset($_GET['page'])) || ($_GET['page'] == "1"))
   		{
   		$start = 0;
   		$_GET['page'] = 1;
   		}
     else
      	{
       	$start = ($_GET['page']-1) * $limit;
      	}

     return $start;
    }

//total halaman
function findPages($count, $limit)
    {
     $pages = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1;

     return $pages;
   	}

//penanda daftar halaman
function pageList($curpage, $pages, $target)
	{
    $page_list  = "";

	//jika $target kosong
	if ($target == "")
		{
		$xpage = "?page";
		}
	else
		{
		$xpage = "&page";
		}

    //awal-sebelumnya
   	if (($curpage != 1) && ($curpage))
		{
   		$page_list .= "| <a href=\"".$target."".$xpage."=1\" title=\"Awal\">Awal</a> ";
   		}
	else
		{
		$page_list .= "| <font color='#CCCCCC'>Awal</font> ";
		}

   	if (($curpage-1) > 0)
   		{
   		$page_list .= "| <a href=\"".$target."".$xpage."=".($curpage-1)."\" title=\"Sebelumnya\">Sebelumnya</a> | ";
   		}
	else
		{
		$page_list .= "| <font color='#CCCCCC'>Sebelumnya</font> | ";
		}


   	//selanjutnya-akhir
   	if (($curpage+1) <= $pages)
   		{
   		$page_list .= " <a href=\"".$target."".$xpage."=".($curpage+1)."\" title=\"Selanjutnya\">Selanjutnya</a> ";
   		}
	else
		{
		$page_list .= " <font color='#CCCCCC'>Selanjutnya</font> ";
		}

   	if (($curpage != $pages) && ($pages != 0))
   		{
   		$page_list .= "| <a href=\"".$target."".$xpage."=".$pages."\" title=\"Akhir\">Akhir</a> |";
   		}
	else
		{
		$page_list .= "| <font color='#CCCCCC'>Akhir</font> |";
		}

	$page_list .= "\n";

   	return $page_list;
   	}

//sebelumnya-selanjutnya
function nextPrev($curpage, $pages)
   	{
     $next_prev  = "";

     if (($curpage-1) <= 0)
   		{
   		$next_prev .= "Sebelumnya";
   		}
     else
   		{
   		$next_prev .= "<a href=\"".$target."".$xpage."=".($curpage-1)."\">Sebelumnya</a>";
   		}

   	$next_prev .= " | ";

   	if (($curpage+1) > $pages)
   		{
   		$next_prev .= "Selanjutnya";
  		}
   	else
   		{
   		$next_prev .= "<a href=\"".$target."".$xpage."=".($curpage+1)."\">Selanjutnya</a>";
   		}

   	return $next_prev;
   	}
}
?>