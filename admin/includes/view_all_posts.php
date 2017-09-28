<?php 

if(isset($_POST['checkBoxArray'])) {
    
    foreach($_POST['checkBoxArray'] as $postValueId) {
        
        
        $bulk_options = $_POST['bulk_options'];
        
        switch($bulk_options) {
                
            case 'published' :
                
                $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$postValueId}";
                $update_to_published_status = mysqli_query($connection,$query);
                confirmQuery($update_to_published_status);
                
                break;
                
            case 'draft' :
                
                $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$postValueId}";
                $update_to_draft_status = mysqli_query($connection,$query);
                confirmQuery($update_to_draft_status);
                
                break;
                
            case 'delete' :
                
                $query = "Delete FROM posts WHERE post_id = {$postValueId}";
                $delete_query = mysqli_query($connection, $query);
                confirmQuery($delete_query);
                
                break;
                
            case 'clone':
                
                $query = "SELECT * FROM posts WHERE post_id = {$postValueId}";
                $clone_query = mysqli_query($connection, $query);
                confirmQuery($clone_query);
                
                while($row = mysqli_fetch_array($clone_query)) {
                    
                    $post_title = $row['post_title'];
                    $post_author = $row['post_author'];
                    $post_user = $row['post_user'];
                    $post_category_id = $row['post_category_id'];
                    $post_status = $row['post_status'];

                    $post_image = $row['post_image'];

                    $post_tags = $row['post_tags'];
                    $post_content = $row['post_content'];
                    $post_date = $row['post_date'];
                }
                
                $query = "INSERT INTO posts(post_category_id, post_title, post_author, post_date, post_image, post_content, post_tags, post_status) ";
                $query .="VALUES({$post_category_id},'{$post_title}', '{$post_author}', now(), '{$post_image}', '{$post_content}','{$post_tags}','{$post_status}')";

                $clone_post_query = mysqli_query($connection,$query);

                confirmQuery($clone_post_query);
                
                break;

            default :
                
                break;
                
                
                
        }
        
    }
    
}


?>
   

   
   <form action='' method='post'> 
    <table class="table table-bordered table-hover">
               
            <div id="bulkOptionContainer" class="col-xs-4">
                                      
                           <select name="bulk_options" class="form-control" id="">
                               
                               <option value="">Select Options</option>
                               <option value="published">Publish</option>
                               <option value="draft">Draft</option>
                               <option value="clone">Clone</option>
                               <option value="delete">Delete</option>
                               
                           </select>
                           
                           
                </div> 
                          <div class="col-xs-4">
                              
                              <input type="submit" name="submit" class="btn btn-success" value="Apply">
                              <a class="btn btn-primary" href="posts.php?source=add_posts">Add New</a>
                              
                              
                          </div>          
                           
                            <thead>
                                <tr>
                                   <th><input id="selectAllBoxes" type='checkbox'></th>
                                    <th>Id</th>
                                    <th>Users</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Image</th>
                                    <th>Tags</th>
                                    <th>Comments</th>
                                    <th>Date</th>
                                    <th>View Post</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                    <th>Views</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                
                                <?php 
                                
                                 $query = "SELECT * FROM posts ORDER BY post_id DESC";

                                        $select_posts = mysqli_query($connection, $query);


                                        while( $row = mysqli_fetch_assoc($select_posts)) {

                                            $post_id = $row['post_id'];
                                            $post_author = $row['post_author'];
                                            $post_user = $row['post_user'];
                                            $post_title = $row['post_title'];
                                            $post_category_id = $row['post_category_id'];
                                            $post_status = $row['post_status'];
                                            $post_image = $row['post_image'];
                                            $post_tags = $row['post_tags'];
                                            $post_comment_count = $row['post_comment_count'];
                                            $post_date = $row['post_date'];
                                            $post_views_count = $row['post_views_count'];
                                            
                                            echo "<tr>";
                                            ?>
                                            
                                           <td><input type='checkbox' class='checkBoxes' name='checkBoxArray[]' value='<?php echo $post_id?>'></td> 
                                            
                                            <?php
                                            echo "<td>{$post_id}</td>";

                                            if(!empty($post_author)){

                                            echo "<td>{$post_author}</td>";

                                        } elseif (!empty($post_user)) {

                                            $query = "SELECT * FROM users WHERE user_id= $post_user";
                                            $send_query = mysqli_query($connection, $query);
                                            $row = mysqli_fetch_array($send_query);
                                            $post_user = $row['username'];
                                            echo "<td>{$post_user}</td>";
                                        }

                                            echo "<td>{$post_title}</td>";
                                            
                                             $query = "SELECT * FROM categories WHERE cat_id = {$post_category_id}";

                                            $select_categories_id = mysqli_query($connection, $query);


                                            while( $row = mysqli_fetch_assoc($select_categories_id)) {

                                            $cat_id = $row['cat_id'];
                                            $cat_title = $row['cat_title'];

                                            echo "<td value='{$cat_id}'>{$cat_title}</td>";

                                        }
                                            
                                            echo "<td>{$post_status}</td>";
                                            echo "<td><img src='../images/{$post_image}' width='100' alt='image'></td>";
                                            echo "<td>{$post_tags}</td>";

                                            $query = "SELECT * FROM comments WHERE comment_post_id = $post_id";
                                            $send_comment_query = mysqli_query($connection, $query);
                                            $row = mysqli_fetch_array($send_comment_query);
                                            $comment_id = $row['comment_id'];
                                            $post_comment_count = mysqli_num_rows($send_comment_query);

                                            echo "<td><a href='post_comments.php?id=$post_id'>{$post_comment_count}</a></td>";
                                            echo "<td>{$post_date}</td>";
                                            echo "<td><a href='../post.php?p_id={$post_id}'>View Post </a></td>";
                                            echo "<td><a href='posts.php?source=edit_posts&p_id={$post_id}'>Edit</a></td>";
                                             echo "<td><a onClick=\"javascript: return confirm('Are you sure you want to delete');\" href='posts.php?delete={$post_id}'>Delete</a></td>";
                                             echo "<td><a href='posts.php?reset={$post_id}'>{$post_views_count}</a></td>";
                                            echo "</tr>";

                                            
                                        }
                                
                                if(isset($_GET['delete'])) {
                                    
                                    $the_post_id = $_GET['delete'];
                                    $query = "DELETE FROM posts WHERE post_id = '$the_post_id'";
                                    $delete_query = mysqli_query($connection, $query);
                                     header("Location: posts.php");
                                    
                                }

                                 if(isset($_GET['reset'])) {
                                    
                                    $the_post_id = $_GET['reset'];
                                    $query = "UPDATE posts SET post_views_count = 0 WHERE post_id=" . mysqli_real_escape_string($connection, $_GET['reset'])." ";
                                    $reset_query = mysqli_query($connection, $query);
                                     header("Location: posts.php");

                                }
                                
                                ?>
                                
                            </tbody>
                        </table>
                        
                       </form>