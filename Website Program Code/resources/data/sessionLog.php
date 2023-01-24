<!DOCTYPE html>
<html lang="en-us">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Customized CSS -->
        <!-- <link rel="stylesheet" type="text/css" href="resources/css/style.css?random=<?= uniqid() ?>"> -->
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
          
        <!--       ION ICONS -->
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule="" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>
      <!-- JQUERY -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
    
        <!-- Customized JS -->
        <!-- <script src="resources/js/java.js?random=<?= uniqid() ?>"></script> -->
    </head>
    <body>
  
        <?php
            include('config.php');
            function userLog($setSessionName, $userID =""){
            // function userLog($userID =""){
            
                if(!$userID){
                    echo "
                    <script>Swal.fire({
                        title: 'Oops! Something went wrong!',
                        text: 'Session not set! You must log in to access this page.',
                        icon: 'error', 
                        confirmButtonText: 'Back to login.'
                    }).then((result) => {
                          if (result.isConfirmed) {
                          window.location.href = 'index.php';
                          }
                        });
                        </script>";
                        exit;
                }
                else{
                    $sessionInstance = $_SESSION[$setSessionName][$userID];
                }
               
                if(isset($sessionInstance)){
                    if($_SESSION[$setSessionName][$userID] == "loggedOut"){
                    
                    echo "
                    <script>Swal.fire({
                        title: 'Ops! Something went wrong!',
                        text: 'You are required to log in! Please try again.',
                        icon: 'error', 
                        confirmButtonText: 'Back to login.'
                    }).then((result) => {
                          if (result.isConfirmed) {
                          window.location.href = 'index.php';
                          }
                        });
                        </script>";
                       
                    }
                }
                else{  
                    echo "
                    <script>Swal.fire({
                        title: 'Ops! Something went wrong!',
                        text: 'Login Credentials not set! Please try again.',
                        icon: 'error', 
                        confirmButtonText: 'Back to login.'
                    }).then((result) => {
                          if (result.isConfirmed) {
                          window.location.href = 'index.php';
                          }
                        });
                        </script>";
                    // header("location:index.php");
                }
    
             
            }

        ?>
        
    </body>
</html>
