<?php include "includes/admin_header.php" ?>


<div id="wrapper">

    <!-- Navigation -->

    <?php include "includes/admin_navigation.php" ?>


    <div id="page-wrapper">

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Welcome to admin
                            <small>Author</small>
                        </h1>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Author</th>
                                    <th>Comment</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>In Response to</th>
                                    <th>Date</th>
                                    <th>Approve</th>
                                    <th>Unapprove</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $get_id = mysqli_real_escape_string($connection, $_GET['id']);
                                $query = "SELECT * FROM comments WHERE comment_post_id = $get_id";
                                $select_comments = mysqli_query($connection, $query);

                                while ($row = mysqli_fetch_assoc($select_comments)) {
                                    $comment_id = $row['comment_id'];
                                    $comment_author = $row['comment_author'];
                                    $comment_post_id = $row['comment_post_id'];
                                    $comment_status = $row['comment_status'];
                                    $comment_email = $row['comment_email'];
                                    $comment_date = $row['comment_date'];
                                    $comment_content = $row['comment_content'];
                                    echo "<tr>";
                                    echo "<td>{$comment_id}</td>";
                                    echo "<td>{$comment_author}</td>";
                                    echo "<td>{$comment_content}</td>";

                                    // $query = "SELECT * FROM categories WHERE cat_id = $post_category_id ";
                                    // $select_categories_id = mysqli_query($connection, $query);

                                    // while ($row = mysqli_fetch_assoc($select_categories_id)) {
                                    //     $cat_id = $row['cat_id'];
                                    //     $cat_title = $row['cat_title'];




                                    //     echo "<td>{$cat_title}</td>";
                                    // }




                                    echo "<td>{$comment_email}</td>";
                                    echo "<td>{$comment_status}</td>";
                                    $query = "SELECT * FROM posts WHERE post_id = $comment_post_id ";
                                    $select_post_id_query = mysqli_query($connection, $query);
                                    while ($row = mysqli_fetch_assoc($select_post_id_query)) {
                                        $post_id = $row['post_id'];
                                        $post_title = $row['post_title'];
                                        echo "<td><a href='../post.php?p_id=$post_id'>$post_title</a></td>";
                                    }


                                    echo "<td>{$comment_date}</td>";
                                    echo "<td><a href='comments.php?approve=$comment_id'>Approve</a></td>";
                                    echo "<td><a href='comments.php?unapprove=$comment_id'>Unapprove</a></td>";
                                    echo "<td><a href='post_comments.php?delete=$comment_id&id=$get_id'>Delete</a></td>";
                                    echo "</tr>";
                                }
                                ?>

                            </tbody>
                        </table>



                        <?php
                        if (isset($_GET['delete'])) {
                            $the_comment_id = $_GET['delete'];
                            $query = "DELETE FROM comments WHERE comment_id={$the_comment_id} ";
                            $delete_query = mysqli_query($connection, $query);
                            header("location: post_comments.php?id=$get_id");
                        }
                        if (isset($_GET['approve'])) {
                            $the_comment_id = $_GET['approve'];
                            $query = "UPDATE comments SET comment_status = 'approved' WHERE comment_id =  $the_comment_id ";
                            $unapprove_comment_query = mysqli_query($connection, $query);
                            header("location: comments.php");
                        }



                        if (isset($_GET['unapprove'])) {
                            $the_comment_id = $_GET['unapprove'];
                            $query = "UPDATE comments SET comment_status = 'unapproved' WHERE comment_id =  $the_comment_id";
                            $unapprove_comment_query = mysqli_query($connection, $query);
                            header("location: comments.php");
                        }









                        ?>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

        <?php include "includes/admin_footer.php"; ?>