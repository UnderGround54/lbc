<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Gestion de stock">
    <meta name="author" content="Chikara">
    <meta name="keywords" content="inscription">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

    <!-- Title Page-->
    <title>Onclebot</title>

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">
    <link rel="stylesheet" media="all" href="vendor/datatables/datatables.css">
    <!-- Vendor CSS-->
    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">
    <link rel="stylesheet" type="text/css" href="css/custom.css">

</head>

<body class="animsition" data-animsition-in-class="fade-in"
  data-animsition-in-duration="500"
  data-animsition-out-class="fade-out"
  data-animsition-out-duration="50">
    <div class="page-wrapper">
        <?php 

            session_start();

            if(isset($_SESSION['userId'])) :

        ?>

        <!-- HEADER MOBILE-->
        <header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="">
                        <img src="images/icon/verified.svg" alt="Secure" class="main-logo" />
                        </a>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        <li>
                            <a href="users.php">
                                <i class="fas fa-chart-bar"></i> Utilisateur </a>
                        </li>
                        <li class="active">
                            <a href="logs.php">
                                <i class="fas fa-chart-bar"></i> Historique </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" onclick="logout()">
                                 <i class="zmdi zmdi-power"></i> Déconnexion </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="">
                    <img src="images/icon/onclebot.png" alt="Leboncoin" style="height: 2.5em;">
                </a>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li>
                            <a href="users.php">
                                <i class="fas fa-user"></i> Utilisateur </a>
                        </li>
                        <li class="active">
                            <a href="logs.php">
                                <i class="fas fa-chart-bar"></i> Historique </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" onclick="logout()">
                                 <i class="zmdi zmdi-power"></i> Déconnexion </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            <form class="form-header" action="" method="POST">
                                
                            </form>
                            <div class="header-button">
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="image">
                                            <img src="images/icon/avatar-01.jpg" alt="John Doe" />
                                        </div>
                                        <div class="content">
                                            <a class="js-acc-btn" href="#">Matt Pat</a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
                                                <div class="image">
                                                    <a href="#">
                                                        <img src="images/icon/avatar-01.jpg" alt="John Doe" />
                                                    </a>
                                                </div>
                                                <div class="content">
                                                    <h5 class="name">
                                                        <a href="#">matthieu</a>
                                                    </h5>
                                                    <span class="email">rifonomby@gmail.com</span>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__footer">
                                                <a href="javascript:void(0)" onclick="logout()">
                                                    <i class="zmdi zmdi-power"></i>Logout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- END HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- DATA TABLE -->
                                <h3 class="title-4 m-b-35"> Historiques des utilisateurs </h3>
                                <div class="table-data__tool">
                                    <form>
                                        <div class="table-data__tool-left">
                                            <input type="text" id="search_item" name="search_item" placeholder="Rechercher" class="au-input">
                                            <button class="btn bg-color-lbc btn-sm">
                                                <i class="zmdi zmdi-hc-lg zmdi-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                    <div class="table-data__tool-right">
                                        <button id="clear_log" class="au-btn au-btn-icon bg-color-lbc au-btn--small" onclick="clearItem()">
                                            <i class="fas fa-trash"></i>Vider les historiques</button>
                                    </div>
                                </div>
                                <div class="table-responsive table-responsive-data2">
                                    <table id="data" class="table table-data2">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <!-- <label class="au-checkbox">
                                                        <input id="selectall" type="checkbox">
                                                        <span class="au-checkmark"></span>
                                                    </label> -->
                                                </th>
                                                <th>ID </th>
                                                <th>Nom d'utilisateur</th>
                                                <th>Adresse IP</th>
                                                <th>ID Machine</th>
                                                <th>Dernière connexion</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        
                                    </table>
                                </div>
                                <!-- END DATA TABLE -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="copyright">
                            <p>Copyright © 2022 Colorlib. All rights reserved. Template by <a href="">Make Lucid</a>.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?php else : ?>
            <?php  echo ' <pre> Vous êtes déconnectée, veuillez reconnecter! </pre>'; ?>
        <?php endif; ?>
    </div>
    <div class="modalw"><!-- Place at bottom of page --></div>
    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <script src="vendor/datatables/datatables.js"></script>
    <script src="vendor/quicksearch/jquery.quicksearch.min.js"></script>
    <script src="vendor/moment-with-locales.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="vendor/animsition/animsition.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <!-- Main JS-->
    <script src="js/main.js"></script>
    <script src="js/logs.js"></script>

</body>

</html>
<!-- end document-->
