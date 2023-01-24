<?php
    session_start();
    
    include('resources/data/sessionLog.php');
    if (isset($_SESSION[$sessionName]['userID'])){
        // echo $_SESSION[$sessionName]['access'];
        // if ($_SESSION[$sessionName]['access'] == $user2 || $_SESSION[$sessionName]['access'] == $user3 || $_SESSION[$sessionName]['access'] == $user4){
            header("location:dashboard.php");
        // }
        // else if ($_SESSION[$sessionName]['access'] == "Admin"){
        //     header("location:master_dashboard.php");
        // }
    }
    
    

?>

<!DOCTYPE html>
<html lang="en-us">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>IOT FLOOD MONITORING SYSTEM</title>
        <!-- Customized CSS -->
        <link rel="stylesheet" type="text/css" href="resources/css/style.css?random=<?= uniqid() ?>">
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300&display=swap" rel="stylesheet">
          
        <!--       ION ICONS -->
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule="" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>
        
        <!--       ION ICONS -->
        <script src="https://code.iconify.design/iconify-icon/1.0.0-beta.3/iconify-icon.min.js"></script>
    
    <style>
        .content {
    background: rgb(0 0 0 / 20%);
    height: 100vh;
    width: 100%;
}

input:focus , input:focus::placeholder {
    color: black;
}
.login-form label {
    color: white;
}

input, ::placeholder {
    color: white;
}
.ft-left {
    text-align: left;
    padding: 0 10%;
}
        </style>
    </head>
    <body class="login dark-theme">
        <div class="container">
            <!-- <div class="content"> -->
                
                <div class="login-form">
                  
                    <form method ="post" action="resources/data/log.php" autocomplete="off">
                        <img class="system-logo" src="resources/img/logo-clear.png" width="150" height="150">
                        <!-- <h2>WELCOME!</h2> -->
                        <div class="form-content">
                            <div class="input-with-icon">
                                <iconify-icon icon="bx:user" class="icon-inside">
                                </iconify-icon><input type="text" name="username" value="" placeholder="Username" required autocomplete="false">
                            </div>  
                        </div>
                        <div class="form-content">
                            <div class="input-with-icon">
                                <iconify-icon icon="carbon:password" class="icon-inside"></iconify-icon>
                                <input type="password" id="password" name="password" value="" placeholder="Password" required autocomplete="false">
                            </div>  
                        </div>
                        
                        <div class="form-content ft-left">
                            <input type="checkbox" onclick="togglePassword()" >
                            <label>Show Password</label>
                         </div>
                        
                      
                        <div class="submit-form">
                            <input type="submit" class="submit-login" value="Submit">
                        </div>
                        
                        <div class="form-link">
                            <a class="returnLink" href="register.php">Does not have an account yet? Register!
                                </a>
                        </div>

                    </form>

                      <script>

                        function togglePassword() {
                        
                        var y = document.getElementById("password");
                        if (y.type === "password") {
                            y.type = "text";
                        } else {
                            y.type = "password";
                        }
                        }
                    </script>
                   
                </div>
            <!-- </div> -->
        </div>
    </body>
</html>