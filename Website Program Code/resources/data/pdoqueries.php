<?php

class GeneratePDOQuery {
    
    /***** Column Names *****/
    public $insert_ColumnNames = array();
    public $insert_ColumnNamesStr;
    /***** Column Types in form of i,s,d *****/
    public $insert_ColumnDataTypes = array();
    public $insert_ColumnDataTypesStr;
    /***** Column Values in  *****/
    public $insert_ColumnValues = array();
    public $insert_ColumnValuesStr;
    /***** Number of form Column Inputs in terms of ? for binding params  *****/
    public $insert_ColumnInstance = array();
    public $insert_ColumnInstanceStr;
    
    /***** Columns to be updated in the format of `columnName`=?  *****/
    public $update_ColumnNameInstancePair = array();
    public $update_ColumnNameInstancePairStr;
    /***** Column Types in form of i,s,d *****/
    public $update_ColumnDataTypes = array();
    public $update_ColumnDataTypesStr;
    /***** Column Values in  *****/
    public $update_ColumnValues = array();
    public $update_ColumnValuesStr;


    public function parseInsertForm($formObject = array()){
        // echo json_encode($formObject) . "\r\n\r\n";
        foreach($formObject as $key => $val) {
            $this->insert_ColumnNames[] = "`" . $key ."`";
            $this->insert_ColumnInstance[] = "?";
            // echo $val  . "\r\n\r\n";
            foreach($val as $_key => $_val) {
                if ($_key == "'value'"){
                    if($key =="password"){
                        $this->insert_ColumnValues[] = password_hash($_val, PASSWORD_DEFAULT);
                    }
                    else{
                        $this->insert_ColumnValues[] = $_val;
                    }
                }
                if ($_key == "'type'"){
                    $this->insert_ColumnDataTypes[] = $_val;
                }
            }
        }
    }

    public function getInsertFormInputStr(){
        /***** Column Names *****/
        $this->insert_ColumnNamesStr = implode(",",$this->insert_ColumnNames);
        /***** Column Types in form of i,s,d *****/
        $this->insert_ColumnDataTypesStr = implode("",$this->insert_ColumnDataTypes);
        /***** Column Values in  *****/
        $this->insert_ColumnValuesStr = implode(",",$this->insert_ColumnValues);
        /***** Number of form Column Inputs in terms of ? for binding params  *****/
        $this->insert_ColumnInstanceStr = implode(",",$this->insert_ColumnInstance);
    }

    
    public function parseUpdateForm($formObject = array()){
        foreach($formObject as $key => $val) {
            $this->update_ColumnNameInstancePair[] = "`" . $key ."` = ?";
            $this->insert_ColumnInstance[] = "?";
            foreach($val as $_key => $_val) {
                if ($_key == "'value'"){
                    if($key =="password"){
                        $this->update_ColumnValues[] = password_hash($_val, PASSWORD_DEFAULT);
                    }
                    else{
                        $this->update_ColumnValues[] = $_val;
                    }
                }
                if ($_key == "'type'"){
                    $this->update_ColumnDataTypes[] = $_val;
                }
            }
        }
    }

    public function getUpdateFormInputStr(){
        /***** Column Names *****/
        $this->update_ColumnNameInstancePairStr = implode(",",$this->update_ColumnNameInstancePair);
        /***** Column Types in form of i,s,d *****/
        $this->update_ColumnDataTypesStr = implode("",$this->update_ColumnDataTypes);
        /***** Column Values in  *****/
        $this->update_ColumnValuesStr = implode(",",$this->update_ColumnValues);
    }
}


?>