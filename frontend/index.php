<?php include "../admin/include/db.php"?>
<!DOCTYPE html>
<html lang="zxx">


<head>
    <?php include "include/css.php" ?>
</head>

<body>

<!-- Theme Switcher Start -->
<div class="switch-theme-mode">
    <label id="switch" class="switch">
        <input type="checkbox" onchange="toggleTheme()" id="slider">
        <span class="slider round"></span>
    </label>
</div>
<!-- Theme Switcher End -->

<!-- Page Wrapper Start -->
<div class="page-wrapper">

    <?php include "include/header.php";

     include "include/slider.php";

    include "include/about_section.php";

    include "include/service.php";

    include "include/product.php";
    include "include/team.php";

    include "include/testimonial.php";
    include "include/appointment.php";

    include "include/blog.php";

    include "include/instagram.php";

    include "include/footer.php"?>

</div>
<!-- Page Wrapper End -->


<?php include "include/js.php"?>

</body>
</html>