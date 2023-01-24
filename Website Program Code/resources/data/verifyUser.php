<?php


date_default_timezone_set('Asia/Singapore');


class UserLoginAuth{
    public $userExists;
    public $Access;
    public $LoginFail;
    public $TimeToLogAgain;
    
    // public $tStamp=date('U');
    public $query;
    public $queryError;
    // public $hasTries;

    function verifyUser($userPassword, $InputPassword, $userIndex = "", $conn =null,  $allowTries = null, $maxTries = 0, $LoginTimerBuffer = null, $LastLoginFailTime = null){
        $tStamp = date('U');
        if($LoginTimerBuffer ){
            if ($LastLoginFailTime + $LoginTimerBuffer > $tStamp){
                $this->TimeToLogAgain = ceil(($LastLoginFailTime + $LoginTimerBuffer - $tStamp) / 60);
                return "timeout";
            }
        }
        if(password_verify($InputPassword, $userPassword)){
            
            if($allowTries){
                $this->LoginFail = 0;
                $query = "UPDATE `users` SET `tries`= 0, `locktStamp`= 0 WHERE userID = '$userIndex'";
                echo $query;

                $updateTries = $conn->prepare($query);
                $updateTries->execute();
                $updateTries->close();
                
                /******  Query for with timer  ******/
                /* $queryStr = "UPDATE `users` SET `tries`= 0, `locktStamp`= 0 WHERE userID = '$userIndex'";
                
                /******  Query for with timer  ******/
                
                /******  Query for with timer  ******/
                $queryStr = "UPDATE `users` SET `tries`= $this->LoginFail  WHERE userID = '$userIndex'";
                /******  Query for with timer  ******/

                $this->query = $conn->prepare($queryStr);
                if($this->query->execute()){
                    $this->queryError = "Connection to database failed. ".$this->query->error;         
                }
                else{
                    $this->queryError = null;
                }
                
                $this->query->close();
            }
            
            return "ok";
        
        }
        else{
 
            if($allowTries){
                $this->LoginFail = $this->LoginFail + 1 ;
                $queryStr = "UPDATE `users` SET `tries`= $this->LoginFail  WHERE userID = '$userIndex'";
                if($this->LoginFail % $maxTries == 0 && $this->LoginFail > 1){
                    
                    $queryStr = "UPDATE `users` SET `tries`= $this->LoginFail, `locktStamp`= $tStamp WHERE userID = '$userIndex'";
                }

                $this->query = $conn->prepare($queryStr);
                if($this->query->execute()){
                    $this->queryError = "Connection to database failed. ".$this->query->error;         
                }
                else{
                    $this->queryError = null;
                }
                
                $this->query->close();
            }
            return "fail";
        }
    }

    function getRemainingTimeToLog(){
        return $this->TimeToLogAgain;
    }
}

?>