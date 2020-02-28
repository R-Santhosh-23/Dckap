<?php
require_once('conn.php');

$shop_name = $_GET['shop'];

$getBar = 'SELECT * FROM announcement_bar WHERE shop = "'.$shop_name.'" ';
$rs = mysqli_query($conn, $getBar);
$fetchRow = mysqli_fetch_assoc($rs);
?>	

<a href="/<?php echo $fetchRow['comment_link'] ?>" target="blank">
	<div id='headerComment' 
		style="
		text-align: <?php echo $fetchRow['comment_position'] ?> ; 
		text-transform : <?php echo $fetchRow['comment_case'] ?>;  
		background: <?php echo $fetchRow['background'] ?>; 
		color: <?php echo $fetchRow['text_color'] ?>;
		z-index:9999; 
		font-size:15px; 
		padding:10px 18px;">
		<?php echo $fetchRow['comment'] ?>
	</div>
</a>
