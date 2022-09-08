<?php
   function remove_underscore($data){
       $data = strtolower($data);
        $data = str_replace("_", " ", $data);
       return $data;
   }

function test_input($data){
    $data = trim($data);
    $data = stripcslashes($data) ;
    $data = htmlspecialchars($data) ;
    return $data;
}

//image file validation and upload

function image_format($format){
       $image_type = array("jpg","png","gif","jpeg");
       if(!in_array($format,$image_type)){
           return false;
       }
}

function check_size($image_size,$allowed_size){
       if($image_size<=$allowed_size){
           return true;
       }
}
function get_format($format){
    $format = strtolower(pathinfo($format, PATHINFO_EXTENSION));
    return $format;
}
function set_uniqid($input_file){
    $format = get_format($input_file);
    $input_file = uniqid().".".$format;
    return $input_file;
}
function set_date($file_name){
    $file_name = date("y-m-d-h-i-s").".".$file_name;
    return $file_name;
}
//for single file
function get_image($image_file , $target)
{
    $image_name = $image_file['name'];
    if (file_exists($target . $image_name)) {
        return $image_name;
    } else {
        $format = get_format($image_name);
        $image_name = date("y-m-d-h-i-s") . '.' . $format;
        $target_file = $target . $image_name;
        $tmp_name = $image_file['tmp_name'];

        move_uploaded_file($tmp_name, $target_file);
        if (file_exists($target_file)) {
            return $image_name;
        } else {
            return false;
        }
    }
}
//for multiple file

function multi_file($image_name,$target){

}
//Categories

function save_categories()
{
    global $conn;
    if (isset($_POST['submit'])) {
        echo "<pre>";
        print_r($_POST);
        $cat_title = mysqli_real_escape_string($conn,$_POST['cat_title']);
        $cat_title = test_input($cat_title);
        $master_id = $_POST['master_id'];
        $id = $_POST['cat_id'];
        $sql = "INSERT INTO categories(cat_id,master_type_id, cat_title)
                                VALUES('$id','$master_id','$cat_title') ON DUPLICATE KEY 
                                UPDATE master_type_id='$master_id' , cat_title='$cat_title' ";
        if ($conn->query($sql) === TRUE) {
            echo "Data inserted Successfully";
            header("location:content_type.php");
        } else {
            echo "Data does not recorded";
        }
    }
}

function delete_categories()
{
    global $conn;
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $delete = "DELETE FROM categories WHERE categories.cat_id ={$id}";
        if ($conn->query($delete)) {
            header("location:content_type.php");
        } else {
            echo "Something went wrong, Please try again";
        }
    }

}

//master category

function save_master_category()
{
    global $conn;
    if (isset($_POST['submit'])) {
        $master_title = $_POST['master_title'];
        $id = $_POST['master_id'];
        $sql = "INSERT INTO master(master_id,master_title)
                                VALUES('$id','$master_title')ON DUPLICATE KEY 
                                UPDATE master_title='$master_title'";
        if ($conn->query($sql) === TRUE) {
            echo "Data inserted Successfully";
            header("location:master_category.php");
        } else {
            echo "Data does not recorded";
        }
    }
}

function delete_master_id()
{
    global $conn;
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $delete = "DELETE FROM master WHERE master.master_id ={$id}";
        if ($conn->query($delete)) {
            header("location:master_category.php");
        } else {
            echo "Something went wrong, Please try again";
        }
    }

}

//Content Type

function save_content_type()
{
    global $conn;
    if (isset($_POST['submit'])) {

        print_r($_POST);
      /*  $content_id = $_POST['content_id'];
        $content_title = $_POST['content_title'];
        $master_id = $_POST['master_id'];
       $sql = "INSERT INTO content_type (content_id,master_id,content_title)
                                VALUES('$content_id','$master_id','$content_title')ON DUPLICATE KEY 
                                UPDATE master_id = '$master_id', content_title='$content_title'";
        if ($conn->query($sql) === TRUE) {
            echo "Data inserted Successfully";
            header("location:content_type.php");
        } else {
            echo "Data does not recorded";
        } */
    }
}

function delete_content_type()
{
    global $conn;
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $delete = "DELETE FROM content_type WHERE content_type.content_id ={$id}";
        if ($conn->query($delete)) {
            header("location:content_type.php");
        } else {
            echo "Something went wrong, Please try again";
        }
    }

}

//content

function save_content()
{
    global $conn;
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $input_err = array();
        foreach ($_POST as $key => $value) {

            $value = mysqli_real_escape_string($conn, $value);
            $value = test_input($value);
            echo $value;
        }
            extract($_POST);
            if(!empty($_FILES['feature_image']['name'])){
                $image_file = $_FILES['feature_image'];
                $image = get_image($image_file,"assets/images/content/");
            }elseif(!empty($id)) {
                $query = "SELECT add_content.feature_image FROM add_content WHERE id ={$id}";
                $select_image = $conn->query($query);
                while($row = $select_image->fetch_assoc()){
                    $image = $row['feature_image'];
                }
            }else{
                $input_err['content_image'] = "Please Select an Image";
            }


       if(count($input_err) == 0) {
            $sql = "INSERT INTO add_content ( id,content_title, content_url, feature_image, short_desc, page_content, meta_keywords, meta_description, content_types) VALUES ('$id','$content_title', '$content_url', '$image', '$short_desc', '$page_content', '$meta_keywords', '$meta_desc', '$content_types')
              ON DUPLICATE KEY 
            UPDATE content_title='$content_title',content_url='$content_url',feature_image='$image',short_desc='$short_desc', page_content='$page_content', meta_keywords='$meta_keywords',meta_description='$meta_desc', content_types='$content_types'";


            if ($conn->query($sql) === TRUE) {
             //   $_SESSION['msg'] = "Data Saved successfully";
              //  header("location:content.php");
            } else {
                echo "<div class='alert alert-danger' role='alert'>
  Data is not recorded , something went wrong
</div>";
            }
        }else{
            echo "<div class='alert alert-danger' role='alert'>
   fill all data
</div>";
        }
    }

}

function delete_content()
{
    global $conn;
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $delete = "DELETE FROM add_content WHERE add_content.id ={$id}";
        if ($conn->query($delete)) {
            header("location:content.php");
        } else {
            echo "Something went wrong, Please try again";
        }
    }

}

//Slider

function save_slider(){
       global $conn;
    if($_SERVER["REQUEST_METHOD"]=="POST") {
        foreach ($_POST as $key => $value) {
            if (!empty($value)) {
                $value = mysqli_real_escape_string($conn,$value);
                $key = test_input($key);
                $value = test_input($value);
            }
        }
        extract($_POST);
        //validation of Image file
        if (!empty($_FILES["slider_image"]['name'])) {
            $slider = get_image($_FILES["slider_image"], "assets/images/slider/");
        } elseif (!empty($id)) {
            $query = "SELECT slider.image FROM slider WHERE id ={$id}";
            $select_image = $conn->query($query);
            while ($row = $select_image->fetch_assoc()) {
                $slider = $row['image'];
            }
        }
        $sql = "INSERT INTO slider (id,title , description , image , button_text , button_url) 
                     VALUES ('$id','$title' , '$slider_desc' , '$slider' , '$button_text' , '$button_url')
                     ON DUPLICATE KEY 
                     UPDATE
                     title = '$title' , description ='$slider_desc' , image = '$slider' , button_text='$button_text', button_url='$button_url'";
        if ($conn->query($sql) === TRUE) {
            echo "Data recorded successfully";
            header("location:slider.php");
        } else {
            echo "Failed to insert the data";
        }
    }
}

function delete_slider(){
    global $conn;
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $delete = "DELETE FROM slider WHERE slider.id ={$id}";
        if ($conn->query($delete)) {
            header("location:slider.php");
        } else {
            echo "Something went wrong, Please try again";
        }
    }
}

//popup

function save_popup(){
       global $conn;
       $input_err = array();

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        foreach ($_POST as $key => $value) {
            if (empty($value)) {
            } else {
                $value = test_input($value);
            }
        }
        $id = 3;
        $title = $_POST['popup_title'];
        $content = $_POST['popup_content'];
        if(empty($_POST['popup_status'])){
            $status = "";
        }else{
            $status = $_POST['popup_status'];
        }
        if(count($input_err)==0) {
            $sql = "INSERT INTO popup ( id, popup_title, popup_content, popup_status) VALUES ('$id','$title','$content', '$status')
                    ON DUPLICATE KEY 
                   UPDATE popup_title='$title' , popup_content='$content', popup_status ='$status'";
            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success' role='alert'>
                       Data recorded successfully
                  </div>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>
                     Data is not recorded , something went wrong
                 </div>";
            }
        }
    }
}

//message
function delete_contact_message(){
    global $conn;
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $delete = "DELETE FROM user_message WHERE id = '$id'";
        if ($conn->query($delete)) {
            header("location:message.php");
        } else {
            echo "Something went wrong, Please try again";
        }
    }
}

//Team Member

function save_team_member(){
       global $conn;
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        //initializing array for errors
        $input_err = array();

        //validation
        foreach ($_POST as $key => $value) {
                $value = mysqli_real_escape_string($conn,$value);
                $value = test_input($value);
        }
        extract($_POST);
        if (!empty($_FILES['member_profile']['name'])) {
            $target = "assets/images/member/";
            $profile = get_image($_FILES['member_profile'], $target);
        } elseif (!empty($id)) {
            $query = "SELECT team_member.member_profile FROM team_member WHERE id ={$id}";
            $select_profile = $conn->query($query);
            $row = $select_profile->fetch_assoc();
            $profile = $row['member_profile'];
        }else{
            $input_err = "Please Select an image";
        }
        //insert data
        if(count($input_err)==0){
            $sql = "INSERT INTO team_member (id, member_name, member_position, member_expertise, member_experience, member_orderid, member_profile )
            VALUES ('$id','$member_name' , '$member_position' , '$member_expertise','$member_experience','$member_orderid','$profile')
            ON DUPLICATE KEY 
            UPDATE member_name='$member_name' , member_position = '$member_position', member_experience = '$member_experience', member_orderid='$member_orderid' , member_profile='$profile'";
            if($conn -> query($sql) === TRUE){
                echo "<div class='alert alert-success' role='alert'>
                             Data saved Successfully
                     </div>";
            }else{
                echo "<div class='alert alert-danger text-center' role='alert'>
                                    Data is not recorded , something went wrong
                       </div>";
            }
        }
    }

}

function delete_team_member(){
    global $conn;
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $delete = "DELETE FROM team_member WHERE team_member.id ={$id}";
        if ($conn->query($delete)) {
            header("location:team_member.php");
        } else {
            echo "Something went wrong, Please try again";
        }
    }
}


//Testimonial

function save_testimonial(){
    global $conn;
    if($_SERVER["REQUEST_METHOD"]=="POST") {
        foreach ($_POST as $key => $value) {
            if (!empty($value)) {
                $value = mysqli_real_escape_string($conn,$value);
                $key = test_input($key);
                $value = test_input($value);
            }
        }
        extract($_POST);
        //validation of Image file
        if (!empty($_FILES["client_photo"]['name'])) {
            $client_photo = get_image($_FILES["client_photo"], "assets/images/testimonial/");
        } elseif (!empty($id)) {
            $query = "SELECT testimonial.client_photo FROM testimonial WHERE id ={$id}";
            $select_client_photo = $conn->query($query);
            while ($row = $select_client_photo->fetch_assoc()) {
                $client_photo = $row['client_photo'];
            }
        }
        $sql = "INSERT INTO testimonial (id, client_name , client_photo, client_website , client_position) 
                     VALUES ('$id','$client_name' , '$client_photo' , '$client_website' , '$client_position')
                     ON DUPLICATE KEY 
                     UPDATE
                     client_name = '$client_name' , client_photo = '$client_photo' , client_website='$client_website', client_position='$client_position'";
        if ($conn->query($sql) === TRUE) {
            echo "Data recorded successfully";
            header("location:testimonials.php");
        } else {
            echo "Failed to insert the data";
        }
    }
}

function delete_testimonial(){
    global $conn;
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $delete = "DELETE FROM testimonial WHERE testimonial.id ={$id}";
        if ($conn->query($delete)) {
            header("location:testimonials.php");
        } else {
            echo "Something went wrong, Please try again";
        }
    }
}

//Image Gallery
function save_image_gallery(){
       global $conn;
    if($_SERVER['REQUEST_METHOD'] =='POST'){
        $input_err = array();
        foreach ($_POST as $key => $value) {
            $key = test_input($value);
            $value = mysqli_real_escape_string($conn,$value);
            $value = test_input($value);
        }
        $id = $_POST['id'];
        $title = $_POST['gallery_title'];
        $thumb = $_FILES['image_thumb'];

        if (!empty($_FILES["image_thumb"]['name'])) {
            $thumb = get_image($thumb,"assets/images/gallery/thumbnail/");
        } elseif (!empty($id)) {
            $query = "SELECT gallery.thumb FROM gallery WHERE id ={$id}";
            $select_image = $conn->query($query);
            while ($row = $select_image->fetch_assoc()) {
                $thumb = $row['thumb'];
            }
        }

        //move to folder

        if ($thumb){
            $sql = "INSERT INTO gallery (id, title,thumb )
            VALUES ('$id','$title' , '$thumb')
            ON DUPLICATE KEY 
            UPDATE title='$title' , thumb='$thumb'";
            if ($conn->query($sql) === TRUE) {
            if(!empty($id)){
                $gallery_id = $id;
            }else{
                $gallery_id = $conn->insert_id;
            }

                    // print_r($_FILES);
                  if(!empty($_FILES['image_gallery'])) {
                      $image_file = $_FILES['image_gallery'];
                      //insert data
                      foreach ($image_file['name'] as $key => $value) {
                          $image_name = date("y-m-d-h-i-s").'-'. $image_file['name'][$key];
                          $target_file = "assets/images/gallery/images/" . $image_name;
                          move_uploaded_file($image_file['tmp_name'][$key], $target_file);

                          $gallery = "INSERT INTO images(gallery_id,images)
                    VALUES ('$gallery_id','$image_name')";
                          if($conn -> query($gallery) === true){
                              echo "Images recorded";
                              header("location:image_gallery.php");
                          }
                      }
                  }

            }
        }


    }
}

function delete_image_gallery(){
    global $conn;
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $select = "SELECT images FROM images WHERE images.gallery_id ='$id' ";
        $query = $conn->query($select);
        if ($query->num_rows > 0) {
            while ($row = $query->fetch_assoc()) {
                if (file_exists("assets/images/gallery/images/" . $row['images'])) {
                    unlink("assets/images/gallery/images/" . $row['images']);
                }
            }
            $delete = "DELETE FROM   images  WHERE images.gallery_id ='$id'";
            if($conn -> query($delete)){
                echo "All images deleted";
            }
        }

        $thumb = "SELECT * FROM gallery WHERE gallery.id = '$id'";
        $thumb_res = $conn -> query($thumb);
        print_r($thumb_res);
        if ($thumb_res -> num_rows > 0) {
            while ($thumb_row = $thumb_res->fetch_assoc()) {
                            if (file_exists("assets/images/gallery/thumbnail/" . $thumb_row['thumb'])) {
                                unlink("assets/images/gallery/thumbnail/" . $thumb_row['thumb']);
                            }
                        }
            $delete_gallery = "DELETE FROM `gallery` WHERE gallery.id ='$id'";
            if($conn -> query($delete_gallery)){
                header("location:image_gallery.php");
            }
                    }
    }
   }
function delete_single_image(){
       global $conn;
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $select = "SELECT images FROM images WHERE images.gallery_id ='$id' ";
        $query = $conn->query($select);
        if ($query -> num_rows > 0) {
            while ($row = $query->fetch_assoc()) {
                if(file_exists("assets/images/gallery/images/". $row['images'])){
                    unlink("assets/images/gallery/images/". $row['images']);
                }

            }
            $delete = "DELETE FROM   images  WHERE images.image_id ='$id'";
            if ($conn -> query($delete)){
                echo "Selected Image is deleted";
            }
        }
    }
}

//Video Gallery
function save_video(){
    global $conn;
    $input_err = array();

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        foreach ($_POST as $key => $value) {
                $value = test_input($value);

        }
        $id = $_POST['id'];
        $title = $_POST['video_title'];
        $video_id = $_POST['video_id'];
        if(count($input_err)==0) {
            $sql = "INSERT INTO video_gallery ( id, video_title, video_id) VALUES ('$id','$title','$video_id')
                    ON DUPLICATE KEY 
                   UPDATE video_title ='$title' , video_id='$video_id'";
            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success' role='alert'>
                       Data saved successfully
                  </div>";
                header("location:video-gallery.php");
            } else {
                echo "<div class='alert alert-danger' role='alert'>
                     Data is not save , something went wrong
                 </div>";
            }
        }
    }
}

function delete_video(){
    global $conn;
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $delete = "DELETE FROM video_gallery WHERE video_gallery.id ={$id}";
        if ($conn->query($delete)) {
            header("location:video-gallery.php");
        } else {
            echo "Something went wrong, Please try again";
        }
    }
}

//faq

function save_faq(){
    global $conn;
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $id = $_POST['id'];
        $type_id = $_POST['type_id'];
        $group_a = $_POST['group_a'];
        foreach ($group_a as $key => $value){
            $question =  test_input($value['faq_question']);
            $answer = test_input($value['faq_answer']);
            $sql = "INSERT INTO faq ( id, question, answer, type_id ) VALUES ('$id','$question','$answer','$type_id')
                    ON DUPLICATE KEY 
                   UPDATE question ='$question' , answer='$answer', type_id='$type_id'";
            if($conn -> query($sql) === TRUE){
                echo "data save successfully";
                header("location:faq.php");
            }
        }
    }
}

function delete_faq(){
    global $conn;
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $delete = "DELETE FROM faq WHERE faq.id ={$id}";
        if ($conn->query($delete)) {
            header("location:faq.php");
        } else {
            echo "Something went wrong, Please try again";
        }
    }
}







function confirmQuery($result)
{
    global $conn;
    if (!$result) {
        die("QUERY FAILED:" . mysqli_error($conn));
    }
}




?>