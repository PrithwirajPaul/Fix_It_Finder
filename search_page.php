<?php
function wordSeparate($s)
{
    $words = preg_split('/\s+/', trim($s));
    return $words;
}
function isVisited($visited, $s)
{
    return isset($visited[$s]) && $visited[$s] === true;
}
?>
<?php
session_start();
$my_search = $_GET['my_search'];
include('database.php');
$words = wordSeparate($my_search);

$visited = array();
$postList = array();
if ($words[0] != '') {
    for ($i = 0; $i < count($words); $i++) {
        $word = $words[$i];
        $sql = "SELECT * FROM post_manage WHERE post_content LIKE '%$word%' or post_title like '%$word%'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (!isVisited($visited, $row['post_id'])) {
                    array_push($postList, array($row['post_id'], $row['post_title'], $row['post_content'], $row['photo'], $row['vote']));
                    $visited[$row['post_id']] = true;
                }
            }
        }
    }
    $visited = array();
    $userList = array();
    for ($i = 0; $i < count($words); $i++) {
        $word = $words[$i];
        $sql = "SELECT * FROM info_table WHERE username LIKE '%$word%' or adrs like '%$word%'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (!isVisited($visited, $row['userid'])) {
                    array_push($userList, array($row['userid'], $row['username'], $row['type_'], $row['img']));
                    $visited[$row['userid']] = true;
                }
            }
        }
    }
}
mysqli_close($conn);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

    <style>
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
    <h1 class="h3 text-center" style="margin-top: 10px; color:Blue">You Searched For <?php echo $_GET['my_search'] ?>
    </h1>

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

    <div class="container bootstrap snippets bootdey">
        <hr>
        <ol class="breadcrumb">
            <li><a href="#">Search Results</a></li>
            <li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
        </ol>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="well search-result">

                </div>
            </div>
            <?php for ($i = 0; $i < count($postList); ++$i) { ?>

                <div class="well search-result" style="cursor: pointer;">
                    <div class="row" onclick="window.location.href='show_post.php?pid=<?php echo $postList[$i][0] ?>';">
                        <a href="#">
                            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-2">
                                <img class="img-responsive" src="<?php echo $postList[$i][3] ?>">
                            </div>
                            <div class="col-xs-6 col-sm-9 col-md-9 col-lg-10 title">
                                <h3><?php echo $postList[$i][1] ?></h3>
                                <p><?php echo $postList[$i][2] ?></p>
                            </div>
                        </a>
                    </div>
                </div>
            <?php } ?>
            <?php for ($i = 0; $i < count($userList); ++$i) { ?>
                <div class="well search-result"
                    onclick="window.location.href='profile.php?user=<?php echo $userList[$i][0] ?>';"
                    style="cursor: pointer;">
                    <div class="row">
                        <a href="#">
                            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-2">
                                <img src="<?php echo $userList[$i][3] ?>" width="100px" height="100px" alt="Avatar"
                                    style="border-radius: 50%; margin-right: 10px;">
                            </div>
                            <div class="col-xs-6 col-sm-9 col-md-9 col-lg-10 title" style="margin-left:-100px">
                                <h3><?php echo $userList[$i][1] ?></h3>
                                <p style="font-size: 16px;  color: Grey;"><?php echo $userList[$i][2] ?></p>
                            </div>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    </div>
</body>

</html>