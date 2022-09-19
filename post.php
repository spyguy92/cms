<?php include "includes/header.php" ?>
<?php include "includes/db.php" ?>
<!-- Navigation -->
<?php include "includes/navigation.php" ?>



<?php
if (isset($_POST['liked'])) {
    $post_id =  $_POST['post_id'];
    $user_id =  $_POST['user_id'];
    //select post

    $search_post = "SELECT * FROM posts WHERE post_id = '{$post_id}'";
    $query = mysqli_query($connection, $search_post);
    $postResult = mysqli_fetch_array($query);
    $likes = $postResult['likes'];

    //update it with likes
    mysqli_query($connection, "UPDATE posts SET likes= $likes+1 WHERE post_id = $post_id");





    //put data inside likes


    mysqli_query($connection, "INSERT INTO likes(post_id, user_id) VALUES($post_id, $user_id)");
    exit();
}

// unliking
if (isset($_POST['unliked'])) {


    $post_id =  $_POST['post_id'];
    $user_id =  $_POST['user_id'];
    // //select post

    $search_post = "SELECT * FROM posts WHERE post_id = '{$post_id}'";
    $query = mysqli_query($connection, $search_post);
    $postResult = mysqli_fetch_array($query);
    $likes = $postResult['likes'];

    // //delete a likes
    mysqli_query($connection, "DELETE FROM likes WHERE post_id = $post_id AND user_id=$user_id");





    // //update decrement likes
    mysqli_query($connection, "UPDATE posts SET likes= $likes-1 WHERE post_id = $post_id");
    exit();
}




?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <?php
            if (isset($_GET['p_id'])) {
                $the_post_id = $_GET['p_id'];
                $view_query = "UPDATE posts SET post_views_count = post_views_count +1 WHERE post_id = $the_post_id ";
                $send_query = mysqli_query($connection, $view_query);


                if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
                    $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
                } else {
                    $query = "SELECT * FROM posts WHERE post_id = $the_post_id AND post_status ='published' ";
                }


                $select_all_posts_query = mysqli_query($connection, $query);


                if (mysqli_num_rows($select_all_posts_query) < 1) {
                    echo "<h1 class='text-center'>No Posts Avalaible</h1>";
                } else {

                    while ($row = mysqli_fetch_assoc($select_all_posts_query)) {
                        $post_title = $row['post_title'];
                        $post_author = $row['post_author'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = $row['post_content'];

            ?>


                        <h1 class="page-header">
                            Posts

                        </h1>

                        <!-- First Blog Post -->
                        <h2>
                            <a href="#"><?php echo $post_title ?></a>
                        </h2>
                        <p class="lead">
                            by <a href="index.php"><?php echo $post_author ?></a>
                        </p>
                        <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?></p>
                        <hr>
                        <img class="img-responsive" src="images/<?php echo imagePlaceholder($post_image); ?>" alt="">
                        <hr>
                        <p><?php echo $post_content ?></p>

                        <hr>
                        <div class="row">
                            <?php
                            if (isLoggedIn()) { ?>
                                <p class="pull-right"><a data-toggle="tooltip" data-palcement="top" title="<?php echo userLikedThisPost($the_post_id) ? ' I liked this before' : ' Want to like it?' ?>" class="<?php echo userLikedThisPost($the_post_id) ? 'unlike' : 'like' ?>" href=""><span class="glyphicon glyphicon-thumbs-up"></span><?php echo userLikedThisPost($the_post_id) ? ' unlike' : ' like' ?></a></p>
                            <?php   } else { ?>
                                <p class="pull-right login-to-post">You need to login to like <a href="/cms/login.php">Login</a></p>

                            <?php }




                            ?>

                        </div>
                        <div class="row">
                            <p class="pull-right likes">Likes: <?php getPostLikes($the_post_id); ?></p>
                        </div>
                        <div class="clearfix"></div>

                    <?php }

                    ?>

                    <!-- Blog Comments -->
                    <?php
                    if (isset($_POST['create_comment'])) {
                        $the_post_id = $_GET['p_id'];
                        $comment_author = $_POST['comment_author'];
                        $comment_email = $_POST['comment_email'];
                        $comment_content = $_POST['comment_content'];
                        if (!empty($comment_author && $comment_email && $comment_content)) {
                            $query = "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date) ";
                            $query .= "VALUES ($the_post_id, '{$comment_author}', '{$comment_email}', '{$comment_content}', 'Unapproved', now()) ";
                            $create_comment_query = mysqli_query($connection, $query);
                            confirm($connection);
                            // $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 ";
                            // $query .= "WHERE post_id =$the_post_id ";
                            // $update_comment_count_query = mysqli_query($connection, $query);
                            // confirm($update_comment_count_query);
                        } else {
                            echo "<script>alert('Fields cannot be empty')</script>";
                        }
                    }


                    ?>
                    <!-- Comments Form -->
                    <div class="well">
                        <h4>Leave a Comment:</h4>
                        <form action="" method="post" role="form">

                            <div class="form-group">
                                <label for="Author">Author</label>
                                <input class="form-control" type="text" name="comment_author">
                            </div>
                            <div class="form-group">
                                <label for="Email">Email</label>
                                <input class="form-control" type="text" name="comment_email">
                            </div>
                            <div class="form-group">
                                <label for="comment">Your Comment</label>
                                <textarea class="form-control" rows="3" name="comment_content" id="body"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary" name="create_comment">Submit</button>
                        </form>
                    </div>

                    <hr>

                    <?php
                    $query = "SELECT * FROM comments WHERE comment_post_id = $the_post_id ";
                    $query .= "AND comment_status = 'approved' ";
                    $query .= "ORDER BY comment_id DESC ";
                    $select_commet_query = mysqli_query($connection, $query);
                    confirm($select_commet_query);
                    while ($row = mysqli_fetch_array($select_commet_query)) {
                        $comment_date = $row['comment_date'];
                        $comment_content = $row['comment_content'];
                        $comment_author = $row['comment_author'];

                    ?>

                        <!-- Comment -->
                        <div class="media">
                            <a class="pull-left" href="#">
                                <img class="media-object" src="https://via.placeholder.com/140x100" alt="">
                            </a>
                            <div class="media-body">
                                <h4 class="media-heading"><?php echo $comment_author; ?>
                                    <small><?php echo $comment_date; ?></small>
                                </h4>
                                <?php echo $comment_content; ?>
                            </div>
                        </div>
            <?php


                    }
                }
            } else {
                header("Location index.php");
            }

            ?>

        </div>
        <!-- Blog Sidebar Widgets Column -->
        <?php include "includes/sidebar.php" ?>

    </div>

    <!-- /.row -->

    <hr>
    <?php include "includes/footer.php" ?>
    <script>
        $(document).ready(function() {
            var post_id = <?php echo $the_post_id; ?>;
            var user_id = <?php echo loggedInUserId(); ?>;
            $('.like').click(function() {
                $.ajax({
                    url: "/cms/post.php?p_id=<?php echo $the_post_id; ?>",
                    type: 'post',
                    data: {
                        'liked': 1,
                        'post_id': post_id,
                        'user_id': user_id

                    }
                })
            });
        });
    </script>

    <!-- unliking -->
    <script>
        $(document).ready(function() {
            var post_id = <?php echo $the_post_id; ?>;
            var user_id = <?php echo loggedInUserId(); ?>;
            $('.unlike').click(function() {
                $.ajax({
                    url: "/cms/post.php?p_id=<?php echo $the_post_id; ?>",
                    type: 'post',
                    data: {
                        'unliked': 1,
                        'post_id': post_id,
                        'user_id': user_id

                    }
                })
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>