<form action="" method="post">
    <div class="form-group">
        <label for="cat_title">Add Category</label>
        <?php
        if (isset($_GET['edit'])) {
            $cat_id = $_GET['edit'];
            $query = "SELECT * FROM categories WHERE cat_id = $cat_id ";
            $select_categories_id = mysqli_query($connection, $query);

            while ($row = mysqli_fetch_assoc($select_categories_id)) {
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];
        ?>
                <input value="<?php if (isset($cat_title)) {
                                    echo $cat_title;
                                } ?>" class="form-control" type="text" name="cat_title">




        <?php }
        } ?>
        <?php // update query

        if (isset($_POST['update_category'])) {
            $the_cat_title = $_POST['cat_title'];
            $stmt = mysqli_prepare($connection, "UPDATE categories SET cat_title = ? WHERE cat_id = ?");
            mysqli_stmt_bind_param($stmt, "si", $the_cat_title, $cat_id);
            mysqli_stmt_execute($stmt);

            header("location: categories.php");
            if (!$stmt) {
                die("query failed") . mysqli_error($connection);
            }
            mysqli_stmt_close($stmt);
        }







        ?>

    </div>
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="update_category" value="Update">
    </div>
</form>