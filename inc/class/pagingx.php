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





class x_Pager
{
var $x_target;
var $x_curpage;


//batas
function x_findStart($x_limit)
   	{
   	if ((!isset($_GET['x_page'])) || ($_GET['x_page'] == "1"))
   		{
   		$x_start = 0;
   		$_GET['x_page'] = 1;
   		}
     else
      	{
       	$x_start = ($_GET['x_page']-1) * $x_limit;
      	}

     return $x_start;
    }

//total halaman
function x_findPages($x_count, $x_limit)
    {
     $x_pages = (($x_count % $x_limit) == 0) ? $x_count / $x_limit : floor($x_count / $x_limit) + 1;

     return $x_pages;
   	}

//penanda daftar halaman
function x_pageList($x_curpage, $x_pages, $x_target)
	{
    $x_page_list  = "";

	//jika $target kosong
	if ($x_target == "")
		{
		$x_xpage = "?x_page";
		}
	else
		{
		$x_xpage = "&x_page";
		}

    //awal-sebelumnya
   	if (($x_curpage != 1) && ($x_curpage))
		{
   		$x_page_list .= "| <a href=\"".$x_target."".$x_xpage."=1\" title=\"Awal\"><<</a> ";
   		}
	else
		{
		$x_page_list .= "| <font color='#CCCCCC'><<</font> ";
		}

   	if (($x_curpage-1) > 0)
   		{
   		$x_page_list .= "| <a href=\"".$x_target."".$x_xpage."=".($x_curpage-1)."\" title=\"Sebelumnya\"><</a> | ";
   		}
	else
		{
		$x_page_list .= "| <font color='#CCCCCC'><</font> | ";
		}


   	//selanjutnya-akhir
   	if (($x_curpage+1) <= $x_pages)
   		{
   		$x_page_list .= " <a href=\"".$x_target."".$x_xpage."=".($x_curpage+1)."\" title=\"Selanjutnya\">></a> ";
   		}
	else
		{
		$x_page_list .= " <font color='#CCCCCC'>></font> ";
		}

   	if (($x_curpage != $x_pages) && ($x_pages != 0))
   		{
   		$x_page_list .= "| <a href=\"".$x_target."".$x_xpage."=".$x_pages."\" title=\"Akhir\">>></a> |";
   		}
	else
		{
		$x_page_list .= "| <font color='#CCCCCC'>>></font> |";
		}

	$x_page_list .= "\n";

   	return $x_page_list;
   	}

//sebelumnya-selanjutnya
function x_nextPrev($x_curpage, $x_pages)
   	{
     $x_next_prev  = "";

     if (($x_curpage-1) <= 0)
   		{
   		$x_next_prev .= "Sebelumnya";
   		}
     else
   		{
   		$x_next_prev .= "<a href=\"".$x_target."".$x_xpage."=".($x_curpage-1)."\">Sebelumnya</a>";
   		}

   	$x_next_prev .= " | ";

   	if (($x_curpage+1) > $x_pages)
   		{
   		$x_next_prev .= "Selanjutnya";
  		}
   	else
   		{
   		$x_next_prev .= "<a href=\"".$x_target."".$x_xpage."=".($x_curpage+1)."\">Selanjutnya</a>";
   		}

   	return $x_next_prev;
   	}
}
?>