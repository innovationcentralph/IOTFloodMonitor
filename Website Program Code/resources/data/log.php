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
        <!-- <script> -->
        <?php
            include('config.php');
            include('verifyUser.php');
            
            date_default_timezone_set('Asia/Singapore');
            $tStamp=date('U');
            // session_start();
            if(isset($_POST["username"]) && isset($_POST["password"])){
                $uname = $_POST["username"];
                $pw = $_POST["password"];
                $query = "SELECT * from users WHERE username='$uname'";

                // echo $query;
                $result = mysqli_query($conn, $query);
                $scan_numrows = mysqli_num_rows($result);
                if ($scan_numrows > 0){
                    while($row = mysqli_fetch_assoc($result)) {
                        // $userAccess = $row["Access"];
                        $userIndex = $row["Index"];
                        // $userDevice = $row["DeviceID"];
                        $FirstName = $row["FirstName"];
                        $LastName = $row["LastName"];
                        $ContactNumber = $row["ContactNumber"];
                        $username = $row["username"];
                        $hashed_password = $row["password"];
                        // $tries = $row["tries"];
                        // $locktStamp = $row["locktStamp"];
                    }
                    // $tOffset = floor($tries/3) * 60*2;
                    $UserLogin = new UserLoginAuth();
                    $loginStatus = $UserLogin ->verifyUser($hashed_password, $pw);
                                     echo $loginStatus;
                    if($loginStatus == "ok"){
                        session_start();
                        $_SESSION[$sessionName]['name'] = "$FirstName $LastName" ;                        
                        $_SESSION[$sessionName]['userID'] = $userIndex;                         
                        $_SESSION[$sessionName]['ContactNumber'] = $ContactNumber;              
                        // $_SESSION[$sessionName]['devID'] = "ABC123";
                        $_SESSION[$sessionName][$userIndex] = "loggedIn";
                        
                        header("location:../../dashboard.php");
                    }
                    
                    else if($loginStatus == "timeout"){
                        echo "<script>Swal.fire({
                            title: 'Account locked!',
                            text: 'Due to multiple login attempts, your account has been locked. Please try again after "+$UserLogin->getRemainingTimeToLog()+" minute/s.',
                            icon: 'error'}).then((result) => {
                                if (result) {
                                window.location.href = '../../index.php';
                                }
                            });
                            </script>";
                    }
                    else if($loginStatus == "fail"){
                        echo 
                        "<script>
                            Swal.fire({
                                title: 'Oops! Something went wrong!',
                                text: 'Incorrect password. Please try again!',
                                icon: 'error'
                            })
                            .then((result) => {
                                if (result) {
                                    window.location.href = '../../index.php';
                                }
                            });
                        </script>";
                    }

                    
                }
                else{
                    echo "
                    <script>Swal.fire({
                        title: 'Oops! Something went wrong!',
                        text: 'Username doesn\'t exist. Please try again!',
                        icon: 'error'}).then((result) => {
                            if (result) {
                                window.location.href = '../../index.php';
                            }
                        });
                    </script>";
                }

            }
            else{
                echo "Swal.fire({
                    title: 'Missing username/password',
                    text: 'Please make sure all fields are filled.',
                    icon: 'error'}).then((result) => {
                        if (result) {
                        window.location.href = '../../index.php';
                        }
                      });
                      </script>";
            }

        ?>
        
    </body>
</html>
