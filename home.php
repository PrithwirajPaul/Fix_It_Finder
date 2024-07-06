<?php
session_start();
$current_user = $_SESSION['username'];
$current_user_type = $_SESSION['type'];
?>




<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!------ HEAD ---------->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;800;900&display=swap">
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<link rel="stylesheet" href="styler.css">

<!------ BODY ---------->
<div class="container">

    

    <div class="row">
      <div class="col-lg-4 offset-lg-1 order-lg-2">
        <div class="d-flex justify-content-center">
          <div class="py-4">
            <!--<div ><img  src="https://i.ibb.co/L6ht5pP/people-3.jpg" alt="avatar"></div>-->
            <div><img  src="<?php echo $_SESSION['image'] ?>" height="150" width="150"></div>
            <div class="text-center mt-3">
              <h2 class="h5"><?php echo $current_user?></h2>
              <p class="small text-muted"> <?php echo $_SESSION['email'] ?> </p>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-center flex-wrap">
          <div class="text-center px-3 py-2">
            <p class="small text-muted mb-0">Reviews</p>
            <p class="font-weight-bold mb-0">5 star</p>
          </div>
          <div class="text-center px-3 py-2">
            <p class="small text-muted mb-0">Experience</p>
            <p class="font-weight-bold mb-0">2+ years</p>
          </div>
          <div class="text-center px-3 py-2">
            <p class="small text-muted mb-0">Serviced</p>
            <p class="font-weight-bold mb-0">16</p>
          </div>
        </div>
        <p class="text-muted mb-0 py-4">Lorem ipsum dolor sit amet Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
        <div class="text-center">
          <a href="profile.php" class="btn btn-link" type="button">View your profile </a>
          </button>
        </div>
      </div>
      <div class="col-12 col-lg-7 order-lg-1">
        <div class="py-4">
          <div class="input-group input-group-lg"><span class="input-group-text bg-light border-0" id="search-icon">
                <i data-feather="search"></i></span>
            <input class="form-control bg-light border-0" type="text" placeholder="Search" aria-label="Search" aria-describedby="search-icon">
            <a onClick="window.close();" style="text-decoration: none;" href="view-accepted.php?cuser=<?php echo $current_user?>"><i class="mr-1" data-feather="bell" style="margin-top: 10px; margin-left: 10px;" ></i></a>
            </div>
        </div>
        
        
       
        <br>
        
        <div>
            <form id="postform" autocomplete="off" action = 'home.php' method ='post'>
                <textarea name="titlearea" rows="2" cols="50" placeholder="write title here" required></textarea>
                <br>
                <textarea name="postarea" rows="5" cols="50" placeholder="write something here" required></textarea>
                <br>
                <div>
                  <input type ='submit' name = 'login' value = 'Create post'></input>
                </div>
            </form>
        </div>
        <div>
        <script>
          var checked = false;
          function uncheck1(){
              if(checked) {
                  let x = document.getElementById("radiobtn1").checked = false;
                  checked = false;
                  return;
              }
              checked = true;
              document.write(x);
          }
          
        </script>
        <?php
$_SESSION['mypost'] = null;
$_SESSION['mytitle'] = null;
if(isset($_POST["login"])) { # when create post button clicked, these happens:
    $_SESSION['mypost'] = $_POST['postarea'];
    $_SESSION['mytitle'] = $_POST['titlearea'];
    $post_string = $_SESSION['mypost'];
    $title_string = $_SESSION['mytitle'];
    
if($post_string != null) {
    
    $is_public = "<script>document.write(666)</script>";
    $conn = mysqli_connect("localhost", "root", "", "social_database");

    if(!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Escape special characters in a string for use in an SQL statement
    $post_string_escaped = mysqli_real_escape_string($conn, $post_string);
    $title_string_escaped = mysqli_real_escape_string($conn, $title_string);



     
    
    $sql = "INSERT INTO post_manage(username,post_content,post_title,vote,isPublic) VALUES ('$current_user','$post_string_escaped','$title_string_escaped',0,'$is_public')";
    if(mysqli_query($conn, $sql)) {
        
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }

    

    mysqli_close($conn);
   
    
    header("location: home.php");
}
}
?>
 <?php
        include("database.php");
        #code to get all the post_content+title+poster from db
        $sql = "SELECT * FROM post_manage";
        $result = mysqli_query($conn, $sql);
        $img=array();
        $arr =array();

        if(mysqli_num_rows($result) > 0){ 
            while($row = mysqli_fetch_assoc($result)){
                array_push ($arr , array($row['username'],$row['post_content'],$row['post_title'],$row['post_id'],$row['isPublic']));
                $imgqr= mysqli_query($conn,"select img from info_table  where username='{$row['username']}'");
                if($imgrow= mysqli_fetch_assoc(($imgqr))) {
                  array_push($img,array($imgrow['img']));
                }
            } 
        }
        mysqli_close( $conn );
        
        # arr =[[name1,post_content1,post_title1],[name1,post_content1,post_title1]....]
        ?>




       
        <!--
        <section class="py-4">
          <h1 class="h3">Stories</h1>
          <div class="swiper-container w-100 h-100 py-4">
            <div class="swiper-wrapper">
              <div class="swiper-slide w-auto">
                <div class="btn btn-light avatar avatar-xl">
                    <i data-feather="plus"></i>
                </div>
              </div>
            </div>
          </div>
        </section>
        
        
--> 
<?php
    function checkAccepted($current_user,$postID){
      include('database.php');
      $sql = "SELECT * FROM post_status";
      $result = $conn->query($sql);
      $isAccepted=false;
      if(mysqli_num_rows($result) > 0){ 
        while($row = mysqli_fetch_assoc($result)){
          if($row['username'] == $current_user && $row['post_id']==$postID){
            $isAccepted=true;
          }
        } 
    }
    mysqli_close($conn);
    return $isAccepted;
    }
?>
  
  


        <section class="py-4">
          <h1 class="h3">Feed</h1>

          <?php for ($i = 0; $i < count($arr); $i++) { ?>
          <!-- show all the posts from database on feed (upto line 259)-->
            
          <div class="mb-4 py-4">
            <div class="d-flex justify-content-between align-items-center">
              <div class="d-flex flex-row align-items-center">
                
                <div><img  src="<?php echo $img[$i][0] ?>" width="70px" height="70px" alt="No image" style="border-radius: 50%;"></div>
                <div>
                  <h2 class="h6 mb-0"> <?php print_r($arr[$i][0])?></h2>
                  <p class="small text-muted mb-0">15 min ago</p>
                </div>
              </div>
              <button class="btn btn-icon btn-text-dark dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  <i data-feather="more-vertical"></i>
              </button>

              <ul class="dropdown-menu dropdown-menu-right">
                <li><a class="dropdown-item" href="#">Save</a></li>
                <?php if ($current_user == $arr[$i][0]){ ?>
                  
                  <li><a class="dropdown-item" href = "delete data.php?num= <?php echo $arr[$i][3] ?>"> Delete</a></li>
                <?php }?>
              </ul>

            </div><img class="rounded w-100 mt-3" src="https://i.ibb.co/RChWN81/fruits-rose.jpg" alt="feed">
            <div class="mt-3">
              <h4 class="h5"><?php print_r($arr[$i][2])?></h4>
              <p class="text-muted mb-0"><?php print_r($arr[$i][1])?></p>
            </div>
            <div class="d-flex justify-content-between">
              <button class="btn btn-text-dark d-flex align-items-center px-1" type="button">
                  <i class="mr-1" data-feather="heart"></i>432
              </button>
              
                <?php if ($current_user_type == 'service-provider' && $arr[$i][0]!=$current_user){?>
                  <?php 
                      $button_text="ACCEPT";
                      $col = 'green';
                      $isAlreadyAccepted = checkAccepted($current_user,$arr[$i][3]);
                      if ($isAlreadyAccepted){
                        $button_text = "DECLINE";
                        $col='red';
                      }
                  ?>
                  
                  <button class="btn success"><a class="dropdown-item" href="show-accepted.php?pid=<?php echo $arr[$i][3]; ?>&acceptor=<?php echo $current_user; ?>"style="color: <?php echo $col?>; text-decoration: none;"><?php echo $button_text ?></a></button>
                  <?php }?>
              </button>
            </div>
          </div>
          <?php } ?>


       <!--   <div>Take a look at <a href="https://bootstrap-minui.github.io/" target="_blank" rel="noopener noreferrer">Minui</a> for more clean and minimal templates built with Bootstrap</div>--->
        </section>
      </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
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