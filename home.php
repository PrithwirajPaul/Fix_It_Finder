<?php
session_start();
include('database.php');
if (isset($_SESSION['username'])) {
  $current_user = $_SESSION['username'];
  $current_user_type = $_SESSION['type'];
}
$providedSql = "SELECT post_id from post_status ps WHERE ps.userid='$current_user' AND ps.status_=5";
$provided = mysqli_num_rows(mysqli_query($conn, $providedSql));
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;800;900&display=swap">
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    .actions {
      display: flex;
      flex-direction: row;
      align-items: center;
    }
  </style>
</head>

<body>
  <div class="container">


    <div class="modal fade" id="logoutModal" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <h4 style="font-color:black">Are you sure you want to log out?</h4>
          </div>
          <div class="modal-footer" style="justify-content:center">
            <button type="button" class="btn btn-danger" onclick="window.location.href='logout.php'">Yes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
          </div>
        </div>
      </div>
    </div>


    <div class="row">
      <div class="col-lg-4 offset-lg-1 order-lg-2">
        <div class="d-flex justify-content-center">
          <div class="py-4">
            <div><img src="<?php echo $_SESSION['image'] ?>" height="150" width="150">
              <a href="#" class="log" data-toggle="modal" data-target="#logoutModal" title="Log Out"
                style="margin-left:auto">
                <i class="bi bi-box-arrow-right"></i>
              </a>
            </div>
            <div class="text-center mt-3">
              <h2 class="h5"><?php echo $current_user ?></h2>
              <p class="small text-muted"><?php echo $_SESSION['email'] ?></p>
            </div>
          </div>
        </div>
        <?php if ($_SESSION['type'] == "service-provider"): ?>
          <div class="d-flex justify-content-center flex-wrap">
            <div class="text-center px-3 py-2">
              <?php
              include('database.php');
              $rating = mysqli_fetch_assoc(mysqli_query($conn, "select avg(review) as rating from postWork where postID=(select post_id from post_status where status_=5 and userid='{$_SESSION['userid']}')"));

              ?>
              <p class="small text-muted mb-0">Reviews</p>
              <p class="font-weight-bold mb-0"><?php echo number_format($rating['rating']?$rating['rating']:0, 1); ?>
                 star</p>
            </div>
            <div class="text-center px-3 py-2">
              <p class="small text-muted mb-0">Experience</p>
              <p class="font-weight-bold mb-0"><?php echo $_SESSION['exp'] ?></p>
            </div>
            <div class="text-center px-3 py-2">
              <p class="small text-muted mb-0">Serviced</p>
              <p class="font-weight-bold mb-0"><?php echo $provided?></p>
            </div>
          </div>
        <?php endif; ?>
        <div class="bio-section"
          style="width: 100%; max-width: 600px; background: #fff; padding: 10px 15px; margin-top:15px; border-radius: 8px; box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);">
          <h4 style="color: #666;margin-left:45%">Bio</h4>
          <textarea id="bio"
            style="width: 100%; padding: 8px; border: none; border-radius: 5px; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);"
            rows="3">
          <?php $_SESSION['bio']; ?>
      </textarea>
          <button onclick="saveBio()"
            style="margin-top: 10px; padding: 6px 12px; background-color: #6a1b9a; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left:40%"><?php echo $_SESSION['bio'] == null ? "Add bio" : "Save" ?></button>
        </div>

        <script>
          function saveBio() {
            const bio = document.getElementById('bio').value;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "home.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("bio=" + bio);
            xhr.onload = function () {
              if (xhr.status == 200) {
                alert('Bio updated successfully!');
              }
            };
          }
        </script>

        <div class="text-center">
          <a href="profile.php?user=<?php echo $_SESSION['userid']; ?>" class="btn btn-link" type="button">View your
            profile </a>
        </div>
      </div>
      <div class="col-12 col-lg-7 order-lg-1">
        <div class="py-4">
          <form action="search_page.php" method="GET">
            <div class="input-group input-group-lg">

              <input class="form-control bg-light border-0" type="text" name="my_search" placeholder="Search"
                aria-label="Search" aria-describedby="search-icon" required>
              <div class="input-group-append">
                <span class="input-group-text bg-light border-0" id="search-icon" type="submit" style="cursor:pointer">
                  <i data-feather="search"></i>
              </div>
              <a style="text-decoration: none;" href="view-accepted.php?cuser=<?php echo $current_user ?>"><i
                  class="mr-1" data-feather="bell" style="margin-top: 10px; margin-left: 10px;"></i></a>
            </div>
          </form>
        </div>

        <br>
        <div>
          <form id="postform" autocomplete="off" action='home.php' method='post' enctype="multipart/form-data">
            <textarea name="titlearea" rows="2" cols="50" placeholder="write title here" required></textarea>
            <br>
            <textarea name="postarea" rows="5" cols="50" placeholder="write something here" required></textarea>
            <br>
            <div>
              <input type='submit' name='login' value='Create post'></input>
              <label>
                <input type="checkbox" name="isPublic" value="1"> Public
              </label>
              <label for="file-upload" class="custom-file-upload">
                Photo Upload
              </label>
              <input name="post_img" id="file-upload" type="file" />
            </div>
          </form>
        </div>
        <div>
          <?php
          $_SESSION['mypost'] = null;
          $_SESSION['mytitle'] = null;
          if (isset($_POST["login"])) { # when create post button clicked, these happens:
            $_SESSION['mypost'] = $_POST['postarea'];
            $_SESSION['mytitle'] = $_POST['titlearea'];
            $post_string = $_SESSION['mypost'];
            $title_string = $_SESSION['mytitle'];
            $targetFile = 'empty';

            if ($post_string != null) {
              $conn = mysqli_connect("localhost", "root", "", "social_database");

              if (isset($_FILES["post_img"])) {
                $targetDir = "uploads/";
                $targetFile = $targetDir . basename($_FILES["post_img"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                move_uploaded_file($_FILES["post_img"]["tmp_name"], $targetFile);
                $photo = $_FILES["post_img"]["name"];

                if (!$conn) {
                  die("Connection failed: " . mysqli_connect_error());
                }

                $post_string_escaped = mysqli_real_escape_string($conn, $post_string);
                $title_string_escaped = mysqli_real_escape_string($conn, $title_string);
                $dat = date('d-m-Y');
                $is_public = isset($_POST['isPublic']) ? (int) $_POST['isPublic'] : 0;
                $sql = "INSERT INTO post_manage(userid,post_content,post_title,vote,isPublic,photo,dat) VALUES ('{$_SESSION['userid']}','$post_string_escaped','$title_string_escaped',0,'$is_public','$photo','{$dat}')";
                if (mysqli_query($conn, $sql)) {

                } else {
                  echo 'Error: ' . mysqli_error($conn);
                }
                mysqli_close($conn);
                header("location: home.php");
              }
            }
          }
          ?>
          <?php
          include("database.php");
          #code to get all the post_content+title+poster from db
          $sql = "SELECT * FROM post_manage where post_id != any(select post_id from post_status where status_=5)";
          $result = mysqli_query($conn, $sql);
          $arr = array();

          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              $sql = "select img,username from info_table where userid= '{$row['userid']}'";
              $user = mysqli_fetch_assoc(mysqli_query($conn, $sql));
              array_push($arr, array($user['username'], $row['post_content'], $row['post_title'], $row['post_id'], $row['isPublic'], $row['photo'], $user['img'], $row['userid'], $row['dat']));
            }
          }
          mysqli_close($conn);
          ?>
          <?php
          function checkAccepted($postID)
          {
            include('database.php');
            $sql = "SELECT status_ FROM post_status where post_id = '$postID' ";
            $result = $conn->query($sql);
            $c2 = 0;
            while ($row = mysqli_fetch_assoc($result)) {
              if ($row['status_'] == 2)
                $c2++;
            }
            if ($c2 > 0)
              return 2;
            else {
              $sql = "SELECT status_ FROM post_status where userid='{$_SESSION['userid']}' and post_id = '$postID'";
              $result = $conn->query($sql);
              $isAccepted = 0;
              if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                return $row['status_'];
              } else {
                return 0;
              }
            }
          }
          ?>
          <script>
            function myFunction(x) {
              x.classList.toggle("fa-thumbs-down");
            }</script>
          <section class="py-4">
            <h1 class="h3">Feed</h1>

            <?php for ($i = 0; $i < count($arr); $i++) {
              if ($arr[$i][4] == 0 && $current_user_type == 'customer' && $arr[$i][0] != $current_user || $arr[$i][4] == -1) {
                continue;
              }
              ?>
              <!-- show all the posts from database on feed (upto line 259)-->
              <div class="mb-4 py-4" style="border: 2px solid #000; padding: 20px;">
                <div style="border-bottom: 1px solid blue; padding: 20px;"
                  class="d-flex justify-content-between align-items-center">
                  <div class="d-flex flex-row align-items-center">
                    <div><img src="<?php echo $arr[$i][6]; ?>" width="70px" height="70px" alt=""
                        style="border-radius: 50%;"></div>
                    <div>
                      <a style="color:black;" href="profile.php?user=<?php echo $arr[$i][7]; ?>">
                        <h2 class="h6 mb-0"> <?php print_r($arr[$i][0]) ?></h2>
                      </a>
                      <h2 class="h6 text-muted mb-0">
                        <?php
                        $uploadedDate = $arr[$i][8];
                        $uploadedDate = DateTime::createFromFormat('d-m-Y', $uploadedDate);
                        $currentDate = new DateTime();
                        $dateDifference = $currentDate->diff($uploadedDate);

                        $years = $dateDifference->y;
                        $months = $dateDifference->m;
                        $days = $dateDifference->d;

                        if ($years > 0) {
                          echo $years . " years ago";
                        } elseif ($months > 0) {
                          echo $months . " months ago";
                        } elseif ($days > 0) {
                          echo $days . " days ago";
                        } else {
                          echo "Today";
                        }
                        ?>
                      </h2>
                    </div>
                  </div>
                  <button class="btn btn-icon btn-text-dark dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i data-feather="more-vertical"></i>
                  </button>
                  <?php
                  include('database.php');
                  $res = mysqli_query($conn, "select * from interaction where postID='{$arr[$i][3]}' and type='saved' and userID='{$_SESSION['userid']}'");
                  $isSavedSQL = mysqli_num_rows($res) > 0;
                  ?>
                  <ul class="dropdown-menu dropdown-menu-right">
                    <li><button class="dropdown-item" onclick="toggleSaveButton(<?php echo $arr[$i][3] ?>,'saved')">
                        <?php echo $isSavedSQL ? 'Unsave' : 'Save'; ?>
                      </button></li>
                    <?php if ($current_user == $arr[$i][0]) { ?>
                      <li><a class="dropdown-item" href="delete data.php?num= <?php echo $arr[$i][3] ?>"> Delete</a></li>
                    <?php } ?>
                  </ul>
                </div>
                <?php if ($arr[$i][5]) {
                  $imageName = trim($arr[$i][5]);
                  ?>

                  <div><img class="rounded w-100 mt-3" src="uploads/<?php echo $imageName; ?>" height="320px" width="680px"
                      alt="feed"></div>

                <?php } ?>

                <div class="mt-3">
                  <h4 class="h5"><?php print_r($arr[$i][2]) ?></h4>
                  <p class="text-muted mb-0"><?php print_r($arr[$i][1]) ?></p>
                </div>
                <div class="d-flex justify-content-between">
                  <?php
                  $isLiked = mysqli_num_rows(mysqli_query($conn, "select * from interaction where userID='{$_SESSION['userid']}' and postID= '{$arr[$i][3]}' and type='liked'")) > 0;
                  $totalLikes = mysqli_num_rows(result: mysqli_query($conn, "select * from interaction where postID='{$arr[$i][3]}' and type='liked'"));
                  ?>
                  <div class="actions">
                    <button class="btn btn-text-dark" type="button" id="like-btn"
                      onclick="toggleSaveButton(<?php echo $arr[$i][3] ?>,'liked')">
                      <?php echo $isLiked ? '<i class="bi bi-hand-thumbs-up-fill"></i>' : '<i class="bi bi-hand-thumbs-up"></i>'; ?>
                    </button>
                    <p style="margin-top:4px; margin-left:4px"><b><?php echo $totalLikes ?></b></p>
                  </div>

                  <?php if ($current_user_type == 'service-provider' && $arr[$i][0] != $current_user) { ?>
                    <?php
                    $button_text = "ACCEPT";
                    $col = 'green';
                    $text = 'Apply for the service';
                    $destination = "show-accepted.php?pid=" . $arr[$i][3];
                    $isAlreadyAccepted = checkAccepted($arr[$i][3]);
                    if ($isAlreadyAccepted == 1) {
                      $button_text = "DECLINE";
                      $text = "Cancel your appliance";
                      $col = 'red';
                    } else if ($isAlreadyAccepted == 3) {
                      $button_text = "REJECTED";
                      $text = "You are rejected";
                      $col = 'RED';
                      $destination = "#";
                    } else if ($isAlreadyAccepted >= 2) {
                      $button_text = "HIRED";
                      $text = "Already Hired";
                      $col = '#FFC300';
                      $destination = "#";
                    }
                    ?>

                    <?php if ($_SESSION['type'] == 'service-provider' || $isAlreadyAccepted >= 2) { ?>
                      <?php if ($destination == "#") { ?>
                        <button class="btn" id="btn-<?php echo $arr[$i][3]; ?>" data-toggle="modal"
                          data-target="#detailsModal-<?php echo $arr[$i][3]; ?>">
                          <a class="dropdown-item" href="#" title="<?php echo $text ?>"
                            style="color: <?php echo $col ?>; text-decoration: none;"><?php echo $button_text ?></a>
                        </button>
                      <?php } else { ?>
                        <button class="btn success" id="btn"><a class="dropdown-item"
                            href="<?php echo $destination . "&cuser=" . $current_user ?>" title="<?php echo $text ?>"
                            style="color: <?php echo $col ?>; text-decoration: none;"><?php echo $button_text ?></a></button>
                        </button>
                      <?php } ?>

                      <div class="modal fade" id="detailsModal-<?php echo $arr[$i][3]; ?>" role="dialog"
                        aria-labelledby="detailsModalLabel-<?php echo $arr[$i][3]; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="detailsModalLabel-<?php echo $arr[$i][3]; ?>">
                                Hiring Details
                              </h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <p>
                                <?php
                                $sql = "select cp.professional as userid,it.username as name from info_table it join connected_pairs cp on cp.professional=it.userid where cp.postID='{$arr[$i][3]}'";
                                $res = mysqli_fetch_assoc(mysqli_query($conn, $sql));
                                ?>
                                <a href="profile.php?user=<?php echo $res['userid'] ?>"><?php echo $res['name'] ?></a>
                                has been hired for this service.
                              </p>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                  <?php } ?>
                  </button>
                </div>
              </div>
            <?php } ?>
          </section>

        </div>
      </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
      integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
      crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"
      integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/"
      crossorigin="anonymous"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
      feather.replace();

      var mySwiper = new Swiper('.swiper-container', {
        // Optional parameters
        slidesPerView: 'auto',
        spaceBetween: 24,
      });

      function toggleSaveButton(post_id, action) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "interact.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
          if (xhr.readyState == 4 && xhr.status == 200) {
            location.reload();
          }
        };

        xhr.send("post_id=" + post_id + "&action=" + action);
      }

    </script>

  </div>
</body>

</html>