<?php
session_start();
?>

<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!------ HEAD ---------->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;800;900&display=swap">
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">



<!------ BODY ---------->
<div class="container">
  <?php

  ?>

  <div class="row">
    <div class="col-lg-4 offset-lg-1 order-lg-2">
      <div class="d-flex justify-content-center">
        <div class="py-4">
          <!--<div ><img  src="https://i.ibb.co/L6ht5pP/people-3.jpg" alt="avatar"></div>-->
          <div><img src="https://i.ibb.co/L6ht5pP/people-3.jpg" height="150" width="150"></div>
          <div class="text-center mt-3">
            <h2 class="h5">Jane Doe</h2>
            <p class="small text-muted">@janedoe</p>
          </div>
        </div>
      </div>
      <div class="d-flex justify-content-center flex-wrap">
        <div class="text-center px-3 py-2">
          <p class="small text-muted mb-0">Followers</p>
          <p class="font-weight-bold mb-0">567K</p>
        </div>
        <div class="text-center px-3 py-2">
          <p class="small text-muted mb-0">Following</p>
          <p class="font-weight-bold mb-0">567K</p>
        </div>
        <div class="text-center px-3 py-2">
          <p class="small text-muted mb-0">Posts</p>
          <p class="font-weight-bold mb-0">67</p>
        </div>
      </div>
      <p class="text-muted mb-0 py-4">Lorem ipsum dolor sit amet Ut enim ad minim veniam, quis nostrud exercitation
        ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
        velit esse cillum dolore eu fugiat nulla pariatur.</p>
      <div class="text-center">
        <button class="btn btn-link" type="button">View your profile
        </button>
      </div>
    </div>
    <div class="col-12 col-lg-7 order-lg-1">
      <div class="py-4">
        <div class="input-group input-group-lg"><span class="input-group-text bg-light border-0" id="search-icon">
            <i data-feather="search"></i></span>
          <input class="form-control bg-light border-0" type="text" placeholder="Search" aria-label="Search"
            aria-describedby="search-icon">
        </div>
      </div>
      <br>
      <div>
        <form id="postform" autocomplete="off" action='home.php' method='post'>
          <textarea name="titlearea" rows="2" cols="50" placeholder="write something here" required></textarea>
          <br>
          <textarea name="postarea" rows="5" cols="50" placeholder="write something here" required></textarea>
          <br>
          <input type='submit' name='login'></input>
        </form>
      </div>
      <?php
      function post_insert_sql($data)
      {
        #sql codes give error in this function
      }
      ?>





      <?php
      $_SESSION['mypost'] = null;
      $_SESSION['mytitle'] = null;
      if (isset($_POST["login"])) {

        $_SESSION['mypost'] = $_POST['postarea'];
        $_SESSION['mytitle'] = $_POST['titlearea'];
        $post_string = $_SESSION['mypost'];
        $title_string = $_SESSION['mytitle'];

        if ($post_string != null) {

          $conn = mysqli_connect("localhost", "root", "", "social_database");

          if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
          }
          
          $post_string_escaped = mysqli_real_escape_string($conn, $post_string);
          $title_string_escaped = mysqli_real_escape_string($conn, $title_string);


          $sql = "INSERT INTO post_manage (username,post_content,post_title,vote) VALUES ('KEN','$post_string_escaped','$title_string_escaped',0)";
          if (mysqli_query($conn, $sql)) {
            echo 'Post created';
          } else {
            echo 'Error: ' . mysqli_error($conn);
          }
          mysqli_close($conn);

          header("location: home.php");
        }
      }
      ?>
      <?php
        include ("database.php");
        #code to show all the post_content+title+poster from db
        $sql = "SELECT * FROM post_manage";
        $result = mysqli_query($conn, $sql);
        $arr = array();
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            array_push($arr, array($row['username'], $row['post_content'], $row['post_title']));
          }
        }
        mysqli_close($conn);


      ?>
      <section class="py-4">
        <h1 class="h3">Feed</h1>

        <?php for ($i = 0; $i < count($arr); $i++) { ?>
          <div class="mb-4 py-4">
            <div class="d-flex justify-content-between align-items-center">
              <div class="d-flex flex-row align-items-center">

                <div><img src="https://i.ibb.co/vqNVTz1/people-2.jpg" width="100px" height="100px"></div>
                <div>
                  <h2 class="h6 mb-0"> <?php print_r($arr[$i][0]) ?></h2>
                  <p class="small text-muted mb-0">15 min ago</p>
                </div>
              </div>
              <button class="btn btn-icon btn-text-dark dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <i data-feather="more-vertical"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-right">
                <li><a class="dropdown-item" href="#">Save</a></li>
                <li><a class="dropdown-item" href="#">Unsubscribe</a></li>
              </ul>
            </div><img class="rounded w-100 mt-3" src="https://i.ibb.co/RChWN81/fruits-rose.jpg" alt="feed">
            <div class="mt-3">
              <h4 class="h5"><?php print_r($arr[$i][2]) ?></h4>
              <p class="text-muted mb-0"><?php print_r($arr[$i][1]) ?></p>
            </div>
            <div class="d-flex justify-content-between">
              <button class="btn btn-text-dark d-flex align-items-center px-1" type="button">
                <i class="mr-1" data-feather="heart"></i>432
              </button>
              <button class="btn btn-icon btn-text-dark" type="button">
                <i data-feather="share-2"></i>
              </button>
            </div>
          </div>
        <?php } ?>
      </section>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
  integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"
  integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
  feather.replace();

  var mySwiper = new Swiper('.swiper-container', {
    // Optional parameters
    slidesPerView: 'auto',
    spaceBetween: 24,
  });
</script>