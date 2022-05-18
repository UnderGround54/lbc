<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Bot LBC">
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

    <!-- Vendor CSS-->
    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
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
                        <li class="active">
                            <a href="users.php">
                                <i class="fas fa-chart-bar"></i> Utilisateur </a>
                        </li>
                        <li>
                            <a href="logs.php">
                                <i class="fas fa-chart-bar"></i> Historique </a>
                        </li>
                        <li>
                            <a   onclick="logout()">
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
                        <li  class="active">
                            <a href="users.php">
                                <i class="fas fa-user"></i> Utilisateur </a>
                        </li>
                        <li>
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
                                <div class="noti-wrap">
                                </div>
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="image">
                                            <img src="images/icon/avatar-01.jpg" alt="John Doe" />
                                        </div>
                                        <div class="content">
                                            <a class="js-acc-btn" href="#">Matt Patt</a>
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
                                                    <i class="zmdi zmdi-power"></i>Déconnexion</a>
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
                                <h3 class="title-4 m-b-35"> Liste des utilisateurs </h3>
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
                                        <button class="au-btn au-btn-icon bg-color-lbc au-btn--small" onclick="addModal()">
                                            <i class="zmdi zmdi-plus"></i>ajouter un utilisateur</button>
                                        <!-- <button class="au-btn au-btn-icon btn-secondary au-btn--small" onclick="removeItemMultiple()">
                                            <i class="fas fa-trash"></i> Supprimer </button> -->
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
                                                <th>ID</th>
                                                <th>Nom d'utilisateur</th>
                                                <th>Numéro de série</th>
                                                <th>Date début</th>
                                                <th>Date fin</th>
                                                <th>Statut</th>
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

            <!-- modal static -->
			<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true"
            data-backdrop="static">
               <div class="modal-dialog" role="document">
                   <div class="modal-content">
                   	<form id="add_user" action="" method="post" class="">
                       <div class="modal-header">
                           <h5 class="modal-title" id="staticModalLabel">Ajouter un utilisateur</h5>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                               <span aria-hidden="true">&times;</span>
                           </button>
                       </div>
                       <div class="modal-body">
                            <div class="form-group">
                                <label for="username" class="form-control-label">Nom d'utilisateur</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" id="username" name="username" placeholder="Nom d'utilisateur" class="form-control form-control-sm" value="" required>
                                </div>
                                <!-- <small class="form-text text-muted text-danger">This is a help text</small> -->
                            </div>
                            <div class="form-group">
                                <label for="date_debut" class="form-control-label">Début de licence</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input id="date_debut" name="date_debut" type="date" class="form-control form-control-sm" required>
                                </div>
                                <!-- <small class="form-text text-muted text-danger">This is a help text</small> -->
                            </div>
                            <div class="form-group">
                                <label for="date_fin" class="form-control-label">Fin de licence</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input id="date_fin" name="date_fin" type="date" class="form-control form-control-sm" required>
                                </div>
                                <!-- <small class="form-text text-muted text-danger">This is a help text</small> -->
                            </div>
                            <div class="form-group m-t-30">
                                <div class="form-check-inline form-check">
                                    <label for="status" class="form-check-label mr-2"> Status : </label>
                                    <input type="checkbox" id="status" name="status" class="form-check-input"> Activé   
                                </div>
                                <div class="form-check-inline form-check float-right">
                                    <input type="checkbox" id="g_num_serie" name="g_num_serie" class="form-check-input mr-2" checked>
                                    <label for="g_num_serie" class="form-check-label"> Génerer un numéro de série </label>
                                </div>
                            </div>
                       </div>
                       <div class="modal-footer">
                       	   <input type="hidden" id="action" name="action" value="">
                       	   <input type="hidden" id="identity" name="identity" value="0">
                           <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Annuler</button>
                           <button type="submit" class="btn bg-color-lbc btn-sm"> Sauvegarder </button>
                       </div>
                     </form>
                   </div>
               </div>
           </div>
           <!-- end modal static -->

        </div>

    <?php else : ?>
        <?php  echo ' <pre> Vous êtes déconnectée, veuillez reconnecter! </pre>'; ?>
    <?php endif; ?>
    </div>

    <div class="modalw"><!-- Place at bottom of page --></div>
    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
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
    <script src="js/users.js"></script>

</body>

</html>
<!-- end document-->
