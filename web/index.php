<?php
  require_once('inc/functions.php');
  require_once('inc/conn.php');

  $requests = $_GET;
  $hmac = $_GET['hmac'];
  $serializeArray = serialize($requests);
  $requests = array_diff_key($requests, array('hmac' => ''));
  ksort($requests); 
   
  $store_name_real = $requests['shop'];
  $store_name = explode('.', $requests['shop']);
  $store_name = $store_name[0];

  $get_token = 'SELECT access_token FROM shop WHERE shop = "'.$store_name_real.'" AND status = "0" ';
  $rs = mysqli_query($conn, $get_token);
  $fetchRow = mysqli_fetch_assoc($rs);

  $token = $fetchRow['access_token'];
  $shop = $store_name;

  $script_array = array(
        'script_tag' => array( 
          'event' => 'onload',
          'src' => 'https://3ba74bc9.ngrok.io/shopify/custom_apps/core/scripts/script.js'
          )
      );

  $script_tag = shopify_call($token, $shop, "/admin/api/2019-10/script_tags.json", $script_array, 'POST');
  $script_tag = json_decode($script_tag['response'], JSON_PRETTY_PRINT);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Announcement Bar | DCKAP</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
</head>
<body>
  <div class="sidebar-contact active">
    <div class="toggle"></div>
    <h4><i><strong>Customize Your Bar!</strong></i></h4>
    <div class="scroll">
      <form method="POST">
        <div class="hidden">
        <input type="text" value="<?php echo $store_name_real ?>" name="store_name">
        </div>
        <?php 
          $get_record = "SELECT * FROM announcement_bar WHERE shop = '".$store_name_real."'";
          $rs = mysqli_query($conn, $get_record);
          $fetchRow = mysqli_fetch_assoc($rs);
        ?>
        <div class="form-group">
          <label for="text">Comment:</label>
          <input type="text" value="<?php echo $fetchRow['comment'] ?>" maxlength="250" class="form-control" placeholder="Enter Message" name="comment" id="comment" required="required">
        </div><div class="form-group col-md-6">
          <label for="pwd">Comment Alignment:</label>
          <select class="form-control" placeholder="select postion" name="text_position" id="text_position" required="required">
            <option value="">--Select Text Alignment--</option>
            <option value="left" <?=$fetchRow['comment_position'] == 'left' ? ' selected="selected"' : '';?>>Left</option>
            <option value="center" <?=$fetchRow['comment_position'] == 'center' ? ' selected="selected"' : '';?>>Center</option>
            <option value="right" <?=$fetchRow['comment_position'] == 'right' ? ' selected="selected"' : '';?>>Right</option>
          </select>
        </div>
        <div class="form-group col-md-6">
          <label for="pwd">Comment Case:</label>
          <select class="form-control" placeholder="select postion" name="comment_case" id="comment_case" required="required">
            <option value="">--Select Text Case--</option>
            <option value="uppercase" <?=$fetchRow['comment_case'] == 'uppercase' ? ' selected="selected"' : '';?>>Upper Case</option>
            <option value="lowercase" <?=$fetchRow['comment_case'] == 'lowercase' ? ' selected="selected"' : '';?>>Lower Case</option>
          </select>
        </div>
        <div class="form-group col-md-6">
          <label for="bgColor">Background:</label>
          <input type="color" id="bgColor" name="bgColor"  value="<?php echo $fetchRow['background'] ?>">
        </div>
        <div class="form-group col-md-6">
          <label for="bgColor">Text Color:</label>
          <input type="color" id="text_color" name="text_color"  value="<?php echo $fetchRow['text_color'] ?>">
        </div>
        <div class="form-group">
          <label for="text">Comment Redirect:</label>
          <input type="text" value="<?php echo $fetchRow['comment_link'] ?>" maxlength="250" class="form-control" placeholder="Enter comment link" name="comment_link" id="comment_link" required="required">
        </div>
        <button type="button" id="update_bar" name="submit" class="btn btn-sm btn-submit update_bar">update</button>
      </form>
    </div>
  </div>

  <div class="banner"></div>
  <div class="jumbotron custom-tron">
    <div class="content">
      <h2 class="text-center">Now Customize Your Announcement Bar As You Like!</h2>
      <p>
        <ol>
          <li>Customisable Announcement Bar.</li>
          <li>Feasable with all theme.</li>
          <li>Say your New Message or Offers.</li>
          <li>Redirect to New Offers.</li>
          <li>Select your Message Position.</li>
          <li>Select your Message Color.</li>
          <li>Select your Announcement Bar Color.</li>
        </ol>
      </p>
    </div>
  </div>

<!-- Modal -->
<div id="myModal" class="modal modal-md fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close-modal" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Success!</h4>
      </div>
      <div class="modal-body">
        <p>Your Announcement Bar has been updated!</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="close-modal" class="close-modal btn btn-success" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- update Status for generate token -->
 <?php
    $get_token_status_update = 'UPDATE shop SET status = "1" WHERE access_token = "'.$token.'"';
    $result = mysqli_query($conn, $get_token_status_update);
  ?>

<script>
  $(document).ready(function(){
  var comment_position = $('#text_position').val();
  var bar_position = $('#position').val();
  var background = $('#bgColor').val();
  var data = comment + ' ' + comment_position +' '+ bar_position +' '+ background;
   
  $('.toggle').click(function(){
    $('.sidebar-contact').toggleClass('active')
    $('.toggle').toggleClass('active')
    });
  });

  $('#update_bar').click(function(){
  // using this page stop being refreshing 
  event.preventDefault();
    $.ajax({
      type: 'POST',
      url: 'inc/update.php',
      data: $('form').serialize(),
      success: function () {
        $('.sidebar-contact').removeClass('active');
        $("#myModal").modal("show");
        $('.close-modal').click(function(){
        $('.sidebar-contact').addClass('active');
        })
      }
    });
  });
</script>
</body>
</html>

