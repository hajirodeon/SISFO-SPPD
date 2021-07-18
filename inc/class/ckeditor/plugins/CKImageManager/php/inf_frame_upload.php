<?php
/*
 * Copyright 2010 - Jose Carrero. All rights reserved.
 *
 * inf_frame_upload.php
 *
 * version 0.5 (2010/02/09) 
 * 
 * Licensed under the GPL license:  
 *   http://www.gnu.org/licenses/gpl.html
 *
 * This file is part of CKImageManager.
 *
 *  CKImageManager is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  CKImageManager is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with CKImageManager.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
?>
<html>
<head>
<script src="../js/jquery.js"></script>
<script src="../js/splitter.js"></script>
<script src="../js/jquery.treeview.js"></script>
<script src="../js/jquery-ui.js"></script>
<script>
$(document).ready(function(){
	<?php 
		if(isset($_GET["folder"])){?>
				window.parent.main.uploadFinished('<?php echo $_GET["folder"]?>');
	<?php
			}
	?>
	<?php 
			if(isset($_GET["error"])){?>
				window.parent.main.alert('<?php echo $_GET["error"]?>');
	<?php 
			}
	?>
	window.parent.main.$("#ajaxLoader").dialog("close");
});
</script>
<body>

</body>
</html>