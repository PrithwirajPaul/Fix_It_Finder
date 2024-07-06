<!-- This page finds and shows the list of 'professionals' who has accepted my post req. i can now decline or accept his service.-->
<?php
    include('database.php');
    $current_user = $_GET['cuser'];
    $sql= "SELECT post_status.username, post_manage.post_title, post_manage.post_id FROM
           post_status INNER JOIN post_manage
           ON post_status.post_id = post_manage.post_id AND post_manage.username = '$current_user'";
    $result = $conn->query($sql);
    $accepted_list = array();
    if(mysqli_num_rows($result) > 0){ 
        while($row = mysqli_fetch_assoc($result)){
            array_push($accepted_list,array($row['username'],$row['post_title'],$row['post_id']));
        } 
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .flex-container {
            display: flex;
            justify-content: space-between;
            align-items:end;
            background-color: beige;
            padding: 10px; /* Optional: Adds some padding inside the div */
        }

        .flex-container p {
            margin: 0; /* Optional: Removes default margin from the paragraph */
        }
        .button-container {
            display: flex;
            gap: 10px; /* Optional: Adds some space between the buttons */
        }
    </style>
</head>

<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!------ HEAD ---------->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;800;900&display=swap">
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<link rel="stylesheet" href="styler.css">
<body>
    <!-- Load Font Awesome Icon Library -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">





<h2 style="text-align:center; color:blueviolet">REQUEST LIST</h2>
<div class="row">
    <?php for ($i = 0; $i < count($accepted_list); $i++){ ?>
        <div class="column" style="background-color:aquamarine;">
        <h4 style="display: inline;"><?php echo $accepted_list[$i][0]; ?><span style="color: green;"> Accepted Your Request </span></h4>
        <div class="flex-container" style="background-color: beige;">
            <p>Title: <?php echo $accepted_list[$i][1]?></p>
            <div class="button-container">
            <button><a onClick="window.close();" style="text-decoration: none;" href="set-up-connection.php?op=<?php echo 1;?>&acceptor=<?php echo $accepted_list[$i][0]?>&postId=<?php echo $accepted_list[$i][2]?>&cuser=<?php echo $current_user?>">AC</a></button>
            <button><a onClick="window.close();" style="text-decoration: none;" href="set-up-connection.php?op=<?php echo 0;?>&acceptor=<?php echo $accepted_list[$i][0]?>&postId=<?php echo $accepted_list[$i][2]?>&cuser=<?php echo $current_user?>">DC</a></button>
        </div>
            
        </div>
        <script>
            var elements = document.getElementsByClassName("column");
            var i;
        for (i = 0; i < elements.length; i++) {
            elements[i].style.width = "50%";
        }
        </script>
        
      </div>
    <?php } ?>
    </div>
    <h2 style="text-align:center; color:cornflowerblue">ESTABLISHED CONNECTION</h2>
    <?php
        include('database.php');
        $sql = "SELECT * FROM connected_pairs";
        $result = $conn->query($sql);
        $connectedList=array();
        if(mysqli_num_rows($result) > 0){ 
            while($row = mysqli_fetch_assoc($result)){
              array_push($connectedList,array($row['professional'],$row['average_joe']));
              }
            } 
        mysqli_close($conn);
    ?>


        


<?php for ($i = 0; $i < count($connectedList); $i++) { ?>

    <?php if($connectedList[$i][0]==$current_user  || $connectedList[$i][1]==$current_user){ ?>
        <?php $guy = $connectedList[$i][0];?>
        <?php if($connectedList[$i][0]==$current_user){ ?>
            <?php $guy = $connectedList[$i][1];?>
            <?php } ?>
        
        <div class="column" style="background-color:aquamarine;width: 50%; display: flex; align-items: center;">
        <h4 style="display: inline;"><?php echo $guy; ?><span style="color: green;"> Accepted Your Request </span></h4>
        <a href="delete-notify.php"><i class="mr-1" data-feather="x" style="margin-top: 10px; margin-left: 10px;" ></i></a>
        <br>
        </div>
        <script>
            var elements = document.getElementsByClassName("column");
            var i;
        for (i = 0; i < elements.length; i++) {
            elements[i].style.width = "50%";
        }
        </script>        
      </div>
      <?php } ?>
<?php } ?>
    
</body>
</html>

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
