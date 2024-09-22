<?php
include('database.php');
session_start();
$arr = array();
$result = mysqli_query($conn, "select * from post_manage where post_id='{$_GET['pid']}'");
if ($row = mysqli_fetch_assoc($result)) {
    $sql = "select img,username from info_table where userid= '{$row['userid']}'";
    $user = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    array_push($arr, array($user['username'], $row['post_content'], $row['post_title'], $row['post_id'], $row['isPublic'], $row['photo'], $user['img'], $row['userid'], $row['dat']));
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <style>
        body,
        h1,
        h2,
        h3,
        h4,
        p,
        ul,
        li,
        a {
            margin: 0;
            padding: 0;
            text-decoration: none;
            list-style: none;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            height: 100%;
            width: 100%;
            background-color: #f9f9ff;
        }

        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }

        header {
            background: #6a1b9a;
            color: #fff;
            padding: 1rem 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .actions {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

    </style>
</head>

<body>
    <header>
        <div class="container">
            <a href="home.php">
                <h1>FixItFinder</h1>
            </a>
        </div>
    </header>

    <?php
    function checkAccepted($postID)
    {
        include('database.php');
        $sql = "SELECT status_ FROM post_status where post_id = '$postID'";
        $result = $conn->query($sql);
        $isAccepted = 0;
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $isAccepted = $row['status_'];
        }
        if ($isAccepted == 1) {
            $sql = "SELECT status_ FROM post_status where userid='{$_SESSION['userid']}' and post_id = '$postID'";
            $result = $conn->query($sql);
            $isAccepted = 0;
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $isAccepted = $row['status_'];
            } else {
                $isAccepted = 0;
            }
        }
        mysqli_close($conn);


        return $isAccepted;
    }
    ?>
    <script>
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
    <div class="container" style=" align-self:center; margin-top:10%; ">
        <div class="" style="border: 2px solid #000; padding: 20px; ;">
            <div style="border-bottom: 1px solid blue; padding: 20px;"
                class="d-flex justify-content-between align-items-center">
                <div class="d-flex flex-row align-items-center">
                    <div><img src="<?php echo $arr[0][6]; ?>" width="70px" height="70px" alt=""
                            style="border-radius: 50%;"></div>
                    <div>
                        <a style="color:black;" href="profile.php?user=<?php echo $arr[0][7]; ?>">
                            <h2 class="h6 mb-0"> <?php print_r($arr[0][0]) ?></h2>
                        </a>
                        <h2 class="h6 text-muted mb-0">
                            <?php
                            $uploadedDate = $arr[0][8];
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
                $res = mysqli_query($conn, "select * from interaction where postID='{$arr[0][3]}' and type='saved' and userID='{$_SESSION['userid']}'");
                $isSavedSQL = mysqli_num_rows($res) > 0;
                ?>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><button class="dropdown-item" onclick="toggleSaveButton(<?php echo $arr[0][3] ?>,'saved')">
                            <?php echo $isSavedSQL ? 'Unsave' : 'Save'; ?>
                        </button></li>
                    <?php if ($_SESSION['username'] == $arr[0][0]) { ?>
                        <li><a class="dropdown-item" href="delete data.php?num= <?php echo $arr[0][3] ?>">
                                Delete</a></li>
                    <?php } ?>
                </ul>
            </div>
            <?php if ($arr[0][5]) {
                $imageName = trim($arr[0][5]);
                ?>

                <div><img class="rounded w-100 mt-3" src="uploads/<?php echo $imageName; ?>" height="320px" width="680px"
                        alt="feed"></div>

            <?php } ?>

            <div class="mt-3">
                <h4 class="h5"><?php print_r($arr[0][2]) ?></h4>
                <p class="text-muted mb-0"><?php print_r($arr[0][1]) ?></p>
            </div>
            <div class="d-flex justify-content-between">
                <?php
                $isLiked = mysqli_num_rows(mysqli_query($conn, "select * from interaction where userID='{$_SESSION['userid']}' and postID= '{$arr[0][3]}' and type='liked'")) > 0;
                $totalLikes = mysqli_num_rows(result: mysqli_query($conn, "select * from interaction where postID='{$arr[0][3]}' and type='liked'"));
                ?>
                <div class="actions">
                    <button class="btn btn-text-dark" type="button" id="like-btn"
                        onclick="toggleSaveButton(<?php echo $arr[0][3] ?>,'liked')">
                        <?php echo $isLiked ? '<i class="bi bi-hand-thumbs-up-fill"></i>' : '<i class="bi bi-hand-thumbs-up"></i>'; ?>
                    </button>
                    <p style="margin-top:4px; margin-left:4px"><b><?php echo $totalLikes ?></b></p>
                </div>


                <?php
                $button_text = "ACCEPT";
                $title = "tap to accept the service";
                $col = 'green';
                $destination = "show-accepted.php?pid=" . $arr[0][3];
                $isAlreadyAccepted = checkAccepted($arr[0][3]);
                if ($isAlreadyAccepted == 1) {
                    $button_text = "DECLINE";
                    $title = "tap to remove your acceptance";
                    $col = 'red';
                } else if ($isAlreadyAccepted == 5) {
                    $button_text = "FINISHED";
                    $title = "tap to see details";
                    $col = 'BLUE';
                    $destination = "#";
                } else if ($isAlreadyAccepted >= 2) {
                    $button_text = "HIRED";
                    $title = "tap to see details";
                    $col = '#FFC300';
                    $destination = "#";
                }
                ?>

                <?php if ($_SESSION['type'] == 'service-provider' || $isAlreadyAccepted >= 2) { ?>
                    <?php if ($destination == "#") { ?>
                        <button class="btn" id="btn-<?php echo $arr[0][3]; ?>" data-toggle="modal"
                            data-target="#detailsModal-<?php echo $arr[0][3]; ?>">
                            <a class="dropdown-item" href="#" title="<?php echo $title ?>"
                                style="color: <?php echo $col ?>; text-decoration: none;"><?php echo $button_text ?></a>
                        </button>
                    <?php } else { ?>
                        <button class="btn success" id="btn"><a class="dropdown-item"
                                href="<?php echo $destination . "&cuser=" . $current_user ?>" title="<?php echo $title ?>"
                                style="color: <?php echo $col ?>; text-decoration: none;"><?php echo $button_text ?></a></button>
                        </button>
                    <?php } ?>

                    <div class="modal fade" id="detailsModal-<?php echo $arr[0][3]; ?>" role="dialog"
                        aria-labelledby="detailsModalLabel-<?php echo $arr[0][3]; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailsModalLabel-<?php echo $arr[0][3]; ?>">
                                        <?php if ($col == 'BLUE') { ?>
                                            Service Details
                                        <?php } else if ($col == '#FFC300') { ?>
                                                Hiring Details
                                        <?php } ?>
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        <?php if ($col == 'BLUE') {
                                            $sql = "select * from (postWork pw inner join connected_pairs cp on pw.postID=cp.postID) join post_status ps on ps.post_id=pw.postID  where pw.postID='{$arr[0][3]}'";
                                            $res = mysqli_fetch_assoc(mysqli_query($conn, $sql));
                                            $name = mysqli_fetch_assoc(mysqli_query($conn, "select username as name from info_table where userid='{$res['professional']}'"));
                                            ?>
                                            <a
                                                href="profile.php?user=<?php echo $res['userid'] ?>"><?php echo $name['name'] ?></a>
                                            has finished this work successfully! <br>
                                            <a href="profile.php?user=<?php echo $arr[0][7] ?>"><?php echo $arr[0][0] ?>
                                            </a>
                                            <?php echo ($res['review']) ? "has reviewed the work with " . $res['review'] . " star rating!" : "didn't review the work yet!" ?>
                                            <br>
                                            <?php if ($res['comments']) { ?>
                                                <h7>Comments : </h7> <?php echo $res['comments'];
                                            } ?>
                                        <?php } else if ($col == '#FFC300') {
                                            $sql = "select cp.professional as userid,it.username as name from info_table it join connected_pairs cp on cp.professional=it.userid where cp.postID='{$arr[0][3]}'";
                                            $res = mysqli_fetch_assoc(mysqli_query($conn, $sql));
                                            ?>
                                                <a
                                                    href="profile.php?user=<?php echo $res['userid'] ?>"><?php echo $res['name'] ?></a>
                                                has been hired for this service.
                                        <?php } ?>
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>

</html>