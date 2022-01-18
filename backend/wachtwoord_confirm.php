<?php
include "functions.php";
$token = $_GET["token"];
?>
<!DOCTYPE html>
<html class="loading" lang="en">
<!-- BEGIN : Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description"
          content="Apex admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
          content="admin template, Apex admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Lock Screen Page - Apex responsive bootstrap 4 admin template</title>
    <link rel="shortcut icon" type="image/x-icon" href="../app-assets/img/ico/favicon.ico">
    <link rel="shortcut icon" type="image/png" href="../app-assets/img/ico/favicon-32.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700,900%7CMontserrat:300,400,500,600,700,800,900"
          rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <!-- font icons-->
    <link rel="stylesheet" type="text/css" href="../app-assets/fonts/feather/style.min.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/fonts/simple-line-icons/style.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/fonts/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/perfect-scrollbar.min.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/prism.min.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/switchery.min.css">
    <!-- END VENDOR CSS-->
    <!-- BEGIN APEX CSS-->
    <link rel="stylesheet" type="text/css" href="../app-assets/css-rtl/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css-rtl/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css-rtl/colors.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css-rtl/components.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css-rtl/themes/layout-dark.css">
    <link rel="stylesheet" href="../app-assets/css-rtl/plugins/switchery.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css-rtl/custom-rtl.css">
    <!-- END APEX CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="../app-assets/css-rtl/core/menu/horizontal-menu.css">
    <link rel="stylesheet" href="../app-assets/css-rtl/pages/authentication.css">
    <!-- END Page Level CSS-->
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../assets2/css/style-rtl.css">
    <!-- END: Custom CSS-->
</head>
<!-- END : Head-->

<!-- BEGIN : Body-->

<body class="horizontal-layout horizontal-menu horizontal-menu-padding navbar-static 1-column auth-page navbar-static layout-dark layout-transparent bg-glass-2 blank-page"
      data-bg-img="bg-glass-2" data-open="hover" data-menu="horizontal-menu" data-col="1-column">
<!-- ////////////////////////////////////////////////////////////////////////////-->
<div class="wrapper">
    <div class="main-panel">
        <!-- BEGIN : Main Content-->
        <div class="main-content">
            <div class="content-overlay"></div>
            <div class="content-wrapper">
                <!--Lock Screen Starts-->
                <section id="lock-screen" class="auth-height">
                    <div class="row full-height-vh m-0">
                        <div class="col-12 d-flex align-items-center justify-content-center">
                            <div class="card overflow-hidden">
                                <div class="card-content">
                                    <div class="card-body auth-img">
                                        <div class="row m-0">
                                            <div class="col-lg-6 d-lg-flex justify-content-center align-items-center d-none text-center auth-img-bg p-3">
                                                <img src="../app-assets/img/gallery/lock.png" alt="" class="img-fluid"
                                                     height="230" width="310">
                                            </div>
                                            <div class="col-lg-6 col-md-12 px-4 py-3">
                                                <h4 class="mb-2 card-title">New Password</h4>
                                                <p class="card-text mb-3">Please enter your new password</p>
                                                <form method="post" action="wachtwoord_new.php">
                                                    <input name="token" type="hidden" value="<?= $token ?>">
                                                    <input name="wachtwoord" type="password" class="form-control mb-3"
                                                           placeholder="Password">
                                                    <input name="wachtwoord_herhaal" type="password"
                                                           class="form-control mb-3" placeholder="Confirm password">
                                                    <div class="d-flex flex-sm-row flex-column justify-content-between">
                                                        <a href="https://relatiebeheer.qccstest.nl/backend/wachtwoord_vergeten.php"
                                                           class="btn bg-light-primary mb-2 mb-sm-0">Back To Login</a>
                                                        <button type='submit' name="wachtwoord_veranderen"
                                                                class="btn btn-primary ml-sm-1">Change
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>
            </div>
        </div>
    </div>
</div>

<!-- ////////////////////////////////////////////////////////////////////////////-->

<!-- BEGIN VENDOR JS-->
<script src="../app-assets/vendors/js/vendors.min.js"></script>
<script src="../app-assets/vendors/js/switchery.min.js"></script>
<!-- BEGIN VENDOR JS-->
<!-- BEGIN PAGE VENDOR JS-->
<!-- END PAGE VENDOR JS-->
<!-- BEGIN APEX JS-->
<script src="../app-assets/js/core/app-menu.js"></script>
<script src="../app-assets/js/core/app.js"></script>
<script src="../app-assets/js/notification-sidebar.js"></script>
<script src="../app-assets/js/customizer.js"></script>
<script src="../app-assets/js/scroll-top.js"></script>
<!-- END APEX JS-->
<!-- BEGIN PAGE LEVEL JS-->
<!-- END PAGE LEVEL JS-->
<!-- BEGIN: Custom CSS-->
<script src="../assets2/js/scripts.js"></script>
<!-- END: Custom CSS-->
</body>
<!-- END : Body-->

</html>