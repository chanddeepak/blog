<?php 

    if(isset($_GET['p_id'])) {
        
        $the_post_id = $_GET['p_id'] ;
    }
 
         $query = "SELECT * FROM posts WHERE post_id = $the_post_id";

            $edit_posts = mysqli_query($connection, $query);


                while( $row = mysqli_fetch_assoc($edit_posts)) {

                    $post_id = $row['post_id'];
                    $post_user = $row['post_user'];
                    $post_title = $row['post_title'];
                    $post_category_id = $row['post_category_id'];
                    $post_status = $row['post_status'];
                    $post_image = $row['post_image'];
                    $post_content = $row['post_content'];
                    $post_tags = $row['post_tags'];
                    $post_comment_count = $row['post_comment_count'];
                    $post_date = $row['post_date'];

}

if(isset($_POST['create_post'])) {
    
    
    $post_title = $_POST['title'];
    $post_user = $_POST['post_user'];
    $post_category_id = $_POST['post_category'];
    $post_status = $_POST['post_status'];
    
    $post_image = $_FILES['image']['name'];
    $post_image_temp = $_FILES['image']['tmp_name'];
    
    $post_tags = $_POST['post_tags'];
    $post_content = $_POST['post_content'];
    
    move_uploaded_file($post_image_temp,"../images/$post_image");
        
        if(empty($post_image)) {
            
            $query = "SELECT * FROM posts WHERE post_id = $the_post_id";
            $select_image = mysqli_query($connection,$query);
            
            while($row = mysqli_fetch_assoc($select_image)) {
                
                $post_image = $row['post_image'];
                
            }
        }
    
    $query = "UPDATE posts SET ";
    $query .= "post_title = '{$post_title}', ";
    $query .= "post_category_id = '{$post_category_id}', ";
    $query .= "post_date = now(), ";
    $query .= "post_user = '{$post_user}', ";
    $query .= "post_status = '{$post_status}', ";
    $query .= "post_tags = '{$post_tags}', ";
    $query .= "post_content = '{$post_content}', ";
    $query .= "post_image = '{$post_image}' ";
    $query .= "WHERE post_id = {$the_post_id}";
    
    $update_query = mysqli_query($connection,$query);
    
    confirmQuery($update_query);
    
    
    echo "<p class='bg-success'>Post Updated. <a href='../post.php?p_id={$the_post_id}'>View Post </a>or<a href='posts.php'> Edit More Posts</a></p>";
}


?>
   

   <form action="" method="post" enctype="multipart/form-data">
    
    <div class="form-group">
        
        <label for="title">Post Title</label>
        <input type="text" value="<?php echo $post_title; ?>" class="form-control" name="title">
        
    </div>
    
     <div class="form-group">
        <label for="categories">Categories</label> 
        <select name="post_category">
      <?php
         
            $query = "SELECT * FROM categories ";

            $select_categories = mysqli_query($connection, $query);
            
            
            while( $row = mysqli_fetch_assoc($select_categories)) {

            $cat_id = $row['cat_id'];
            $cat_title = $row['cat_title'];
             
                echo "<option value='{$cat_id}'>{$cat_title}</option>";
                
            }

         
         
         ?>
        </select>
    </div>
    
    <div class="form-group">

        <label for="users">Users</label>   
        <select name="post_user" id="">

        <?php 
            $query = "SELECT * FROM users WHERE user_id= $post_user";
            $send_query = mysqli_query($connection, $query);
            $row = mysqli_fetch_array($send_query);
            $post_user = $row['username'];
            echo "<option value='$post_user'>$post_user</option>";

         ?>

      <?php
         
          $users_query = "SELECT * FROM users";

            $select_users = mysqli_query($connection, $users_query);
            
            confirmQuery($select_users);


            while( $row = mysqli_fetch_assoc($select_users)) {
                
            $user_id = $row['user_id'];
            $username = $row['username'];

                echo "<option value='{$user_id}'>{$username}</option>";

            }
         ?>
        </select>
    </div>
     <!-- <div class="form-group">
        
        <label for="author">Post Author</label>
        <input type="text" value="<?php //echo $post_author; ?>" class="form-control" name="author">
        
    </div> -->
    
    <div class="form-group">
        
        <select name='post_status'>
            <option value='<?php echo $post_status; ?>'><?php echo $post_status; ?></option>
            
            <?php
            
            if($post_status == 'published') {
            
            echo "<option value='draft'>Draft</option>";
                                            
            
            } else {
            
            echo "<option value='published'>Published</option>";
                                            
            
            }
            
            ?>
            
        </select>
        
    </div> 
    
     <div class="form-group">
        
       
        <img width="100" src="../images/<?php echo $post_image; ?>">
         <label for="post_image">Post Image</label>
         <input type="file" name="image">
        
    </div>
    
     <div class="form-group">
        
        <label for="post_tags">Post Tags</label>
        <input type="text" value="<?php echo $post_tags; ?>" class="form-control" name="post_tags">
        
    </div>
    
     <div class="form-group">
        
        <label for="post_content">Post Content</label>
        <textarea name="post_content" class="form-control" id="" cols="30" rows="10"><?php echo $post_content; ?> </textarea>
        
    </div>
    
    <div class="form-group">
        
        <input type="submit" class="btn btn-primary" name="create_post" value="Publish Post">
    </div>
    
    
    
</form>