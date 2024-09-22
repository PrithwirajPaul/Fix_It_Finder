<?php
session_start();
include('database.php');
$current_user = $_SESSION['username'];
$sql = "SELECT post_status.userid as provider,post_manage.userid as customer, post_manage.post_title,status_ as postStatus, post_manage.post_id FROM
           post_status INNER JOIN post_manage
           ON post_status.post_id = post_manage.post_id where (post_manage.userid ='{$_SESSION['userid']}' or post_status.userid='{$_SESSION['userid']}') order by post_status.id desc";
$result = $conn->query($sql);
$accepted_list = array();
if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $provider_query = "SELECT username, img FROM info_table WHERE userid='{$row['provider']}'";
    $user_result = mysqli_query($conn, $provider_query);
    $provider = mysqli_fetch_assoc($user_result);
    $customer_query = "SELECT username, img,adrs,phone FROM info_table WHERE userid='{$row['customer']}'";
    $user_result = mysqli_query($conn, $customer_query);
    $customer = mysqli_fetch_assoc($user_result);
    array_push($accepted_list, array($provider['username'], $row['post_title'], $row['post_id'], $provider['img'], $row['provider'], $row['postStatus'], $row['customer'], $customer['username'], $customer['img'], $customer['adrs'], $customer['phone']));
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>

<body>
  <link href="http://getbootstrap.com/examples/jumbotron-narrow/jumbotron-narrow.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">



  <div class="container bootstrap snippets bootdey">
    <div class="header">
      <h3 class="text-muted prj-name">
        <span class="fa fa-users fa-2x principal-title"></span>
        Notification
      </h3>
    </div>


    <div class="jumbotron list-content">
      <ul class="list-group">
        <?php for ($i = 0; $i < count($accepted_list); ++$i) { ?>
          <li href="#" class="list-group-item text-left">

            <?php if ($accepted_list[$i][7] == $current_user) {
              #ur post got accepted
              if ($accepted_list[$i][5] == 1) { ?>
                <div style="display:flex; flex-direction:column; margin-left: 15px">
                  <img class="img-thumbnail" height="70px" width="70px" src="<?php echo $accepted_list[$i][3] ?>">
                  <p style="font-size:14px; margin-top:-25px"><a href="profile.php?user=<?php echo $accepted_list[$i][4] ?>">
                      <h2><?php echo $accepted_list[$i][0] ?></h2>
                    </a>
                    is interested in doing the work that you posted.Here's <a
                      href="show_post.php?pid=<?php echo $accepted_list[$i][2] ?>">the post</a></p>
                  <p style="font-size:14px; color:brown"> Agree with it or not?</p>
                </div>
                <div style="margin-left:15px">
                  <a class="btn btn-success btn-xs glyphicon glyphicon-ok" title="View"
                    href="set-up-connection.php?op=<?php echo 1; ?>&acceptor=<?php echo $accepted_list[$i][4] ?>&postId=<?php echo $accepted_list[$i][2] ?>&cuser=<?php echo $_SESSION['userid'] ?>"></a>
                  <a class="btn btn-danger  btn-xs glyphicon glyphicon-trash" title="Delete"
                    href="set-up-connection.php?op=<?php echo 0; ?>&acceptor=<?php echo $accepted_list[$i][4] ?>&postId=<?php echo $accepted_list[$i][2] ?>&cuser=<?php echo $_SESSION['userid'] ?>"></a>
                  <p class="text-muted" style="font-size:15px; margin-top:8px"> (N.B: Your contact details will be shared with the provider if you agree!)</p>
                </div>
                <!-- work finished and review  -->

              <?php } else if ($accepted_list[$i][5] == 4) { ?>
                  <div style="display:flex; flex-direction:column; margin-left: 15px">
                    <img class="img-thumbnail" height="70px" width="70px" src="<?php echo $accepted_list[$i][8] ?>">
                    <p style="font-size:14px; margin-top:-25px"><a href="profile.php?user=<?php echo $accepted_list[$i][6] ?>">
                        <h2><?php echo $accepted_list[$i][7] ?></h2>
                      </a>
                      is asking for your review for the service that he has provided to<a
                        href="show_post.php?pid=<?php echo $accepted_list[$i][2] ?>">this work</a></p>
                    <form id="postform" autocomplete="off" action='review.php?pID=<?php echo $accepted_list[$i][2] ?>' method='post' enctype="multipart/form-data">
                      <textarea name="ratings" rows="1" cols="50" placeholder="Give rating out of 5" required></textarea>
                      <br>
                      <textarea name="comment" rows="5" cols="50" placeholder="Add comments about the service you got" required></textarea>
                      <br>
                      <div>
                        <button name="Add" class="btn btn-success btn-xs glyphicon glyphicon-ok" title="Add"></button>
                        <button name="Remove" class="btn btn-danger  btn-xs glyphicon glyphicon-trash" title="Remove"></button>
                      </div>
                    </form>
                  </div>
            </div>
        <?php } ?>

      <?php } else if ($accepted_list[$i][0] == $current_user) { ?>
          <!-- your work request got accepted -->
        <?php if ($accepted_list[$i][5] == 2) { ?>
            <div style="display:flex; flex-direction:column; margin-left: 15px">
              <img class="img-thumbnail" height="70px" width="70px" src="<?php echo $accepted_list[$i][8] ?>">
              <p style="font-size:14px; margin-top:-25px"><a href="profile.php?user=<?php echo $accepted_list[$i][6] ?>">
                  <h2><?php echo $accepted_list[$i][7] ?></h2>
                </a>
                has accepted your work request that you applied to.Here's <a
                  href="show_post.php?pid=<?php echo $accepted_list[$i][2] ?>">the post</a></p>
              <p style="font-size:14px; color:brown"> Details of the customer :
              <ol>
                <li>Name : <?php echo $accepted_list[$i][7] ?></li>
                <li>Location : <?php echo $accepted_list[$i][9] ?></li>
                <li>Phone : <?php echo $accepted_list[$i][10] ?></li>
              </ol>
              </p>
              <div style="display:flex; flex-direction:row;">
                <p style="font-size:14px; color:brown" class="text-muted">Please contact with the person to start the work!</p>
                <b style="font-size:14px;margin-left:auto"> Finished this work? <a
                    href="finishing.php?pid=<?php echo $accepted_list[$i][2] ?>">Ask for a review</a></b>
              </div>
            </div>

            <!-- your work request got rejected -->
        <?php } else if ($accepted_list[$i][5] == 3) { ?>
              <div style="display:flex; flex-direction:column; margin-left: 15px">
                <img class="img-thumbnail" height="70px" width="70px" src="<?php echo $accepted_list[$i][8] ?>">
                <p style="font-size:14px; margin-top:-25px"><a href="profile.php?user=<?php echo $accepted_list[$i][6] ?>">
                    <h2><?php echo $accepted_list[$i][7] ?></h2>
                  </a>
                  has rejected your work request that you applied to.Here's <a
                    href="show_post.php?pid=<?php echo $accepted_list[$i][2] ?>">the post</a></p>
              </div>
        <?php } else if($accepted_list[$i][5]==5) {
          $sql = "select review from postWork where postID= '{$accepted_list[$i][2]}'";
          $res=mysqli_query($conn,$sql);
          $r = mysqli_fetch_assoc($res);
          if(mysqli_num_rows($res)>0) { ?>
            <div style="display:flex; flex-direction:column; margin-left: 15px">
                <img class="img-thumbnail" height="70px" width="70px" src="<?php echo $accepted_list[$i][8] ?>">
                <p style="font-size:14px; margin-top:-25px"><a href="profile.php?user=<?php echo $accepted_list[$i][6] ?>">
                    <h2><?php echo $accepted_list[$i][7] ?></h2>
                  </a>
                  has reviewed your work with <?php echo $r['review'] ?> star rating to <a
                    href="show_post.php?pid=<?php echo $accepted_list[$i][2] ?>">this service</a></p>
              </div>
          <?php } mysqli_close($conn);?>
          
        <?php } ?>  
      <?php } ?>

      <div class="break"></div>
      </li>
    <?php } ?>
    </ul>
  </div>
  </div>
  </div>

  

</body>

</html>
</body>

</html>
