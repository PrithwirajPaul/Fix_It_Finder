<?php
session_start();
include('database.php');
$current_user = $_GET['user'];

if (isset($_POST['bio'])) {
    $sql = "update info_table set bio='{$_POST['bio']}' where userid='$current_user'";
    mysqli_query($conn, $sql);
}

// Fetch profile information
$profile = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM info_table WHERE userid='$current_user'"));
$rating = mysqli_fetch_assoc(mysqli_query($conn, "select avg(review) as rating from postWork where postID=(select post_id from post_status where status_=5 and userid='$current_user')"));

if ($profile['type_'] == 'customer') {
    $Takensql = "SELECT pm.* FROM post_manage pm INNER JOIN post_status ps ON pm.post_id=ps.post_id WHERE pm.userid='$current_user' AND ps.status_>1 and ps.status_!=3";
    $taken = mysqli_query($conn, $Takensql);
} else {
    $providedSql = "SELECT pm.* FROM post_manage pm INNER JOIN post_status ps ON pm.post_id=ps.post_id WHERE ps.userid='$current_user' AND ps.status_>1 and ps.status_!=3";
    $provided = mysqli_query($conn, $providedSql);
}

$lookingSQL = "SELECT pm.* FROM post_manage pm INNER JOIN post_status ps ON pm.post_id=ps.post_id WHERE pm.userid='$current_user' AND ps.status_=1";
$looking = mysqli_query($conn, $lookingSQL);

$user = [
    'type' => $profile['type_'],
    'username' => $profile['username'],
    'mail' => $profile['email'],
    'bio' => $profile['bio'],
    'address' => $profile['adrs'],
    'rating' => $rating['rating'],
    'profile_picture' => $profile['img'],
    'services_taken' => $profile['type_'] == 'customer' ? mysqli_num_rows($taken) : 0,
    'services_looking_for' => mysqli_num_rows($looking),
    'reviews' => 4.5,
    'experience' => 5,
    'services_provided' => $profile['type_'] == 'service-provider' ? mysqli_num_rows($provided) : 0
];

// Handle button click for "Services Taken" and "Services Looking For"
$saved = mysqli_query($conn, "select * from post_manage where post_id=(select postID from interaction where userID='$current_user' and type='saved')");
$all = mysqli_query($conn, "SELECT * FROM post_manage WHERE userid='$current_user'");
$result = $all;
$current_filter = 'All';

if (isset($_POST['service_taken'])) {
    $result = $taken;
    $current_filter = 'service_taken';
} elseif (isset($_POST['service_looking'])) {
    $result = $looking;
    $current_filter = 'service_looking';
} else if (isset($_POST['service_provided'])) {
    $result = $provided;
    $current_filter = 'service_provided';
} else if (isset($_POST['All'])) {
    $result = $all;
    $current_filter = 'All';
} else if (isset($_POST['Saved'])) {
    $result = $saved;
    $current_filter = 'Saved';
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

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .profile-header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 20px;
        }

        .user-profile,
        .service-provider-profile {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .actions {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        .stat-button {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            padding: 10px;
            cursor: pointer;
        }

        .stat-button.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .stat-button:not(.active) {
            opacity: 0.7;
        }

        .stat-button:focus {
            outline: none;
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
    <div class="modal fade" id="logoutModal" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h4 style="font-color:black">Are you sure you want to log out?</h4>
                </div>
                <div class="modal-footer" style="justify-content:center">
                    <button type="button" class="btn btn-danger"
                        onclick="window.location.href='logout.php'">Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function submitProfilePicForm() {
            var fileInput = document.getElementById('profilePicUpload');

            // Check if a file is selected
            if (fileInput.files && fileInput.files.length > 0) {
                // Submit the form
                document.getElementById('profilePicForm').submit();
            }
        }
    </script>

    <section class="profile" style="margin-top:10px;margin-bottom:20px">
        <div class="container">
            <div class="profile-header"
                style="display: flex; flex-direction: column; align-items: center; background-color: #f9f9ff; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);">

                    <div style="margin-bottom:20px">
                        <div style="margin-bottom:10px; margin-left:-20px">
                            <?php if ($current_user == $_SESSION['userid']) { ?>
                                <form id="profilePicForm"
                                    action="upload_profile_picture.php?user=<?php echo $current_user ?>" method="POST"
                                    enctype="multipart/form-data" title="Tap to change picture">
                                    <label for="profilePicUpload" style="cursor: pointer;">
                                        <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture"
                                            style="width: 120px; height: 120px; border-radius: 50%; margin-right: 20px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                                    </label>

                                    <input type="file" id="profilePicUpload" name="profile_picture" style="display: none;"
                                        accept="image/*" onchange="submitProfilePicForm()">
                                </form>
                            <?php } else { ?>
                                <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture"
                                    style="width: 120px; height: 120px; border-radius: 50%; margin-right: 20px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                            <?php } ?>
                        </div>
                        <div class="d-flex flex-row">
                            <h2 style="font-size: 28px; color: #333;"><?php echo $user['username']; ?></h2>
                            <?php if ($current_user==$_SESSION['userid']) {?>
                            <a href="#" class="log" data-toggle="modal" data-target="#logoutModal" title="Log Out"
                                style="margin-left:auto">
                                <i class="bi bi-box-arrow-right"></i>
                            </a>
                        <?php }?> 
                        </div>
                           
                        <?php if ($user['type'] == 'service-provider') { ?>
                            <span style="font-size: 20px;  color: Grey;">Service Provider</span>
                            <div style="display: flex; align-items: center; margin-top: 5px;">
                                <span
                                    style="font-size: 20px; font-weight: bold; color: #f39c12;"><?php echo number_format($user['rating'], 1); ?>/5</span>
                                <div style="margin-left: 10px; display: flex;">
                                    <?php for ($i = 0; $i < 5; $i++) { ?>
                                        <i class="bi bi-star<?php echo ($i < $user['rating']) ? '-fill' : ''; ?>"
                                            style="color: #f39c12;"></i>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div style="display: flex; align-items: center; margin-top: 5px;">
                                <span style="font-size: 20px;  color: grey;"> Customer</span>

                            </div>
                        <?php } ?>
                        <span style="font-size: 16px;  color: Grey;">Mail :<a href="mailto:<?php echo $user['mail']?>">
                        <?php echo $user['mail'] ?></a></span>
                    </div>
                

                <div class="bio-section"
                    style="width: 100%; max-width: 600px; background: #fff; padding: 10px 15px; border-radius: 8px; box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);">
                    <h4 style="color: #666;">Bio</h4>
                    <textarea id="bio"
                        style="width: 100%; padding: 8px; border: none; border-radius: 5px; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);"
                        rows="3" <?php echo ($current_user == $_SESSION['userid']) ? '' : 'readonly'; ?>>
            <?php echo $user['bio']; ?>
        </textarea>
                    <?php if ($current_user == $_SESSION['userid']) { ?>
                        <button onclick="saveBio()"
                            style="margin-top: 10px; padding: 6px 12px; background-color: #6a1b9a; color: white; border: none; border-radius: 4px; cursor: pointer;">Save</button>
                    <?php } ?>
                </div>

                <div class="location-section" style="margin-top: 20px;">
                    <p style="font-size: 19px; color: #555;">Location: <?php echo $user['address']; ?>
                    </p>
                </div>
            </div>

            <script>
                function saveBio() {
                    const bio = document.getElementById('bio').value;
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "profile.php?user=" + <?php echo $_SESSION['userid'] ?>, true);
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.send("bio=" + bio);
                    xhr.onload = function () {
                        if (xhr.status == 200) {
                            alert('Bio updated successfully!');
                        }
                    };
                }
            </script>

            <div class="stats">
                <form method="post" action="profile.php?user=<?php echo $current_user; ?>" id="statsForm">
                    <button name="All" type="submit"
                        class="stat-button <?php echo $current_filter === 'All' ? 'active' : ''; ?>">All posts</button>
                    <?php if ($user['type'] == 'customer'): ?>
                        <button name="service_taken" type="submit"
                            class="stat-button <?php echo $current_filter === 'service_taken' ? 'active' : ''; ?>">Services
                            Taken: <?php echo $user['services_taken']; ?></button>
                    <?php else: ?>
                        <button name="service_provided" type="submit"
                            class="stat-button <?php echo $current_filter === 'service_provided' ? 'active' : ''; ?>">Services
                            Provided: <?php echo $user['services_provided']; ?></button>
                    <?php endif; ?>
                    <button name="service_looking" type="submit"
                        class="stat-button <?php echo $current_filter === 'service_looking' ? 'active' : ''; ?>">Services
                        Looking For: <?php echo $user['services_looking_for']; ?></button>
                    <?php if ($current_user == $_SESSION['userid']) { ?>
                        <button name="Saved" type="submit"
                            class="stat-button <?php echo $current_filter === 'Saved' ? 'active' : ''; ?>">Saved
                            posts</button>
                    <?php } ?>
                </form>
            </div>

            <div class="user-profile">
                <div class="posts">
                    <?php
                    $arr = array();

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $sql = "select img,username from info_table where userid= '{$row['userid']}'";
                            $user = mysqli_fetch_assoc(mysqli_query($conn, $sql));
                            array_push($arr, array($user['username'], $row['post_content'], $row['post_title'], $row['post_id'], $row['isPublic'], $row['photo'], $user['img'], $row['userid'], $row['dat']));
                        }
                    }
                    for ($i = 0; $i < count($arr); $i++) {
                        if ($arr[$i][4] == 0 && $_SESSION['type'] == 'customer' && $arr[$i][0] != $_SESSION['username']) {
                            continue;
                        }
                        ?>

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
                                <button class="btn btn-icon btn-text-dark dropdown-toggle" data-toggle="dropdown"
                                    aria-expanded="false">
                                    <i data-feather="more-vertical"></i>
                                </button>
                                <?php
                                include('database.php');
                                $res = mysqli_query($conn, "select * from interaction where postID='{$arr[$i][3]}' and type='saved' and userID='{$_SESSION['userid']}'");
                                $isSavedSQL = mysqli_num_rows($res) > 0;
                                ?>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><button class="dropdown-item"
                                            onclick="toggleSaveButton(<?php echo $arr[$i][3] ?>,'saved')">
                                            <?php echo $isSavedSQL ? 'Unsave' : 'Save'; ?>
                                        </button></li>
                                    <?php if ($_SESSION['username'] == $arr[$i][0]) { ?>
                                        <li><a class="dropdown-item" href="delete data.php?num= <?php echo $arr[$i][3] ?>">
                                                Delete</a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?php if ($arr[$i][5]) {
                                $imageName = trim($arr[$i][5]);
                                ?>

                                <div><img class="rounded w-100 mt-3" src="uploads/<?php echo $imageName; ?>" height="320px"
                                        width="680px" alt="feed"></div>

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


                                <?php
                                $button_text = "ACCEPT";
                                $title = "tap to accept the service";
                                $col = 'green';
                                $destination = "show-accepted.php?pid=" . $arr[$i][3];
                                $isAlreadyAccepted = checkAccepted($arr[$i][3]);
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
                                        <button class="btn" id="btn-<?php echo $arr[$i][3]; ?>" data-toggle="modal"
                                            data-target="#detailsModal-<?php echo $arr[$i][3]; ?>">
                                            <a class="dropdown-item" href="#" title="<?php echo $title ?>"
                                                style="color: <?php echo $col ?>; text-decoration: none;"><?php echo $button_text ?></a>
                                        </button>
                                    <?php } else { ?>
                                        <button class="btn success" id="btn"><a class="dropdown-item"
                                                href="<?php echo $destination . "&user=" . $current_user ?>"
                                                title="<?php echo $title ?>"
                                                style="color: <?php echo $col ?>; text-decoration: none;"><?php echo $button_text ?></a></button>
                                        </button>
                                    <?php } ?>

                                    <div class="modal fade" id="detailsModal-<?php echo $arr[$i][3]; ?>" role="dialog"
                                        aria-labelledby="detailsModalLabel-<?php echo $arr[$i][3]; ?>" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="detailsModalLabel-<?php echo $arr[$i][3]; ?>">
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
                                                            $sql = "select * from (postWork pw inner join connected_pairs cp on pw.postID=cp.postID) join post_status ps on ps.post_id=pw.postID  where pw.postID='{$arr[$i][3]}'";
                                                            $res = mysqli_fetch_assoc(mysqli_query($conn, $sql));
                                                            $name = mysqli_fetch_assoc(mysqli_query($conn, "select username as name from info_table where userid='{$res['professional']}'"));
                                                            ?>
                                                            <a
                                                                href="profile.php?user=<?php echo $res['userid'] ?>"><?php echo $name['name'] ?></a>
                                                            has finished this work successfully! <br>
                                                            <a href="profile.php?user=<?php echo $arr[$i][7] ?>"><?php echo $arr[$i][0] ?>
                                                            </a>
                                                            <?php echo ($res['review']) ? "has reviewed the work with " . $res['review'] . " star rating!" : "didn't review the work yet!" ?>
                                                            <br>
                                                            <?php if ($res['comments']) { ?>
                                                                <h7>Comments : </h7> <?php echo $res['comments'];
                                                            } ?>
                                                        <?php } else if ($col == '#FFC300') {
                                                            $sql = "select cp.professional as userid,it.username as name from info_table it join connected_pairs cp on cp.professional=it.userid where cp.postID='{$arr[$i][3]}'";
                                                            $res = mysqli_fetch_assoc(mysqli_query($conn, $sql));
                                                            ?>
                                                                <a
                                                                    href="profile.php?user=<?php echo $res['userid'] ?>"><?php echo $res['name'] ?></a>
                                                                has been hired for this service.
                                                        <?php } ?>
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

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

</body>

</html>
