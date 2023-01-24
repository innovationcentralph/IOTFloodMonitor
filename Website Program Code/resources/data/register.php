    <?php
    session_start();
    date_default_timezone_set('Asia/Singapore');
    $date=date('Y-m-d H:i');
    include('config.php');
    include('registration_utils.php');
    include('pdoqueries.php');
    if(isset($_POST)){ 
        // echo $_POST["Name['value']"];

        /***** Uncomment and follow structure if you want to perform insert query  *****/
        // $parseRegisterUserForm = new GeneratePODQuery();
        // $parseRegisterUserForm->parseInsertForm($_POST);
        //
        /***** Uncomment and follow structure if you want to append derived fields  *****/
        // $userID = generateID(3,3);
        // $parseRegisterUserForm->insert_ColumnNames[] = "`userID`";
        // $parseRegisterUserForm->insert_ColumnDataTypes[] = "s";
        // $parseRegisterUserForm->insert_ColumnValues[] = $userID;
        // $parseRegisterUserForm->insert_ColumnInstance[] = "?";
        /***** Uncomment and follow structure if you want to append derived fields  *****/
        //
        // $parseRegisterUserForm->getInsertFormInputStr();
        /***** Uncomment and follow structure if you want to perform insert query  *****/


        /***** Uncomment and follow structure if you want to perform update query  *****/
        $parseUpdateUserForm = new GeneratePDOQuery();
        $parseUpdateUserForm->parseUpdateForm($_POST);
        //
        /***** Uncomment and follow structure if you want to append derived fields  *****/
        // $userID = generateID(3,3);
        // $parseRegisterUserForm->insert_ColumnNames[] = "`userID`";
        // $parseRegisterUserForm->insert_ColumnDataTypes[] = "s";
        // $parseRegisterUserForm->insert_ColumnValues[] = $userID;
        // $parseRegisterUserForm->insert_ColumnInstance[] = "?";
        /***** Uncomment and follow structure if you want to append derived fields  *****/
        //
        $parseUpdateUserForm->getUpdateFormInputStr();
        /***** Uncomment and follow structure if you want to perform insert query  *****/
        try{
            
            /***** Query for insert  *****/
            // $sql = "INSERT INTO `users` ($parseRegisterUserForm->insert_ColumnNamesStr) VALUES ($parseRegisterUserForm->insert_ColumnInstanceStr)";
            /***** Query for insert  *****/

            
            /***** Query for update  *****/
            $sql = "UPDATE `users` SET $parseUpdateUserForm->update_ColumnNameInstancePairStr";
            /***** Query for insert  *****/
           
            
            $register = $conn->prepare($sql);
            $register->bind_param($parseUpdateUserForm->update_ColumnDataTypesStr,...$parseUpdateUserForm->update_ColumnValues); 

            if(!$register->execute()){
                echo json_encode(array("response"=>"error","data"=>': User registration failed.  ' . $register->error . '.'));
                $register->close();
                exit;
            }
            else{
                // $registerDevice_sql = "INSERT INTO `monitoring` (`userID`,`devID`) VALUES (?,?)";
            
                // $registerDevice = $conn->prepare($registerDevice_sql);
                // $registerDevice->bind_param("ss",$userID, $devID); 
                // if(!$registerDevice->execute()){
                //     echo json_encode(array("response"=>"error","data"=>': Device registration failed.  ' . $registerDevice->error . '.'));
                //     $registerDevice->close();
                //     exit;
                // }
                // else{
                //     $registerDevice->close();
                //     echo json_encode(array("response"=>"success","data"=> "Congratulations! You have been successfully registered." ));
                // }


                $register->close();
                echo json_encode(array("response"=>"success","data"=> "Congratulations! You have been successfully registered." ));
            }
        } 
        catch(Exception $e) {
            echo json_encode(array("response"=>"error","data"=>$e->getMessage() ));
        } 
    }
    else{
        echo json_encode(array("response"=>"error","data"=>"No parameters set." ));
    }



    ?>