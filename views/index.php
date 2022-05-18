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

    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">
    <title>Onclebot - Connexion</title>
</head>
<body class="animsition" data-animsition-in-class="fade-in"
  data-animsition-in-duration="500"
  data-animsition-out-class="fade-out"
  data-animsition-out-duration="50">

    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content" style="width: 450px;">
                        <div class="login-logo">
                            <b class="lbc-title"> <i class="fas fa-lock mr-2"></i> Administrateur</b>
                        </div>
                        <div class="login-form">
                            <form id="auth" method="post">
                                <div class="form-group m-b-30">
                                    <label style="font-size: 0.9rem"> Login </label>
                                    <input class="au-input au-input--full" type="text" id="login" name="login" placeholder="Login" value="" required>
                                </div>
                                <div class="form-group m-b-30">
                                    <label style="font-size: 0.9rem"> Mot de passe </label>
                                    <div class="input-group">
                                        <input class="au-input au-input--full form-control" type="password" id="password" name="password" autocomplete="off" placeholder="Mot de passe" value="" required>
                                        <div class="input-group-text" role="button" onClick="showPassword()"><i id="show_pass" class="fas fa-eye"></i></div>
                                    </div>
                                </div>
                                <div class="social-login-content text-center">
                                    <button type="submit" class="btn btn-lg bg-color-lbc m-b-20" style="width: 100%; font-size: 1rem;"> Se connecter </button>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="vendor/animsition/animsition.min.js"></script>

    <!-- Main JS-->
    <script src="js/main.js"></script>

    <script>
        $(document).ready(()=>{
            $('button[type="submit"]').button();
            $('#auth').on('submit',(e)=>{
                e.preventDefault();
                $('button[type="submit"]').html(` <i class="fa fa-spinner fa-spin mr-2"></i> Connexion `);
                var data = {
                    login  : $('#login').val(),
                    password : $('#password').val()
                }

                $.ajax({
                    type : "POST",  //type of method
                    url  : "http://localhost/lbc/api/admin.php",  //your page
                    dataType: "json",
                    data : data ,  // passing the values
                    success: function(data) {
                        if (typeof data['error'] !== 'undefined') {
                            localStorage.clear();
                            alert('Erreur: ' + data['error']);
                            $('button[type="submit"]').html(` Se connecter `);

                        } else {
                            localStorage.token = data['token'];
                            window.open('http://localhost/lbc/views/users.php', '_self');
                        }
                    },
                    error: function() {
                        alert("Erreur: Erreur de connexion au server");
                        $('button[type="submit"]').html(` Se connecter `);
                    }
                });
            });
        });

        function showPassword() {
          var x = document.getElementById("password");
          var y = document.getElementById("show_pass");
          if (x.type === "password") {
            y.classList.remove("fa-eye");
            y.classList.add("fa-eye-slash");
            x.type = "text";
          } else {
            y.classList.remove("fa-eye-slash");
            y.classList.add("fa-eye");
            x.type = "password";
          }
        }
    </script>
</body>
</html>