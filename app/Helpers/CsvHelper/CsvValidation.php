<?php


namespace App\Helpers\CsvHelper;


class CsvValidation
{
    public function ThaiNationalID(Array $data, $column ,$line, $type, $status) {
        $message=array();
        $id=str_replace('-','',$data[$column]);

        if($id){
            if(is_numeric($id)){
                if ($id == null || strlen($id) !== 13 ) {
                    $success=false;
                    array_push($message,['The '.$type.' on line '.$line.' :'.$data[$column].' has insufficient characters.']);
                }else{
                    for ($i= 0, $sum = 0; $i< 12; $i++) {
                        $sum += (int)(substr($id, ($i),1)* (13 - $i));
                    }
                    $check = (11 - ($sum % 11)) % 10;
                    if (!$check === (int)(substr($id,12,1))) {
                        $status=false;
                        array_push($message,['The '.$type.' on line '.$line.' :'.$data[$column].' is not a valid.']);
                    }
                }
        
            }else{
                array_push($message,['The '.$type.' on line '.$line.' :'.$data[$column].' is not in numeric form.']);
            }
        }
        return array($message,$status);
    }

    // check symbol number
    public function checkCitizen($data, $user, $all_data ,$citizen_column, $line, $success, $type)
    {
        $message=array();
        // database
        $chkDatabase=$this->checkDuplicateDatabase($data,$user,$citizen_column, $line, $success, $type);
        $success=$chkDatabase[1];
        $message=array_merge($message,$chkDatabase[0]);

        // csv
        $chkCsv=$this->checkDuplicateCsv($data, $citizen_column, $all_data, $success, $line, $type);
        $success=$chkCsv[1];
        $message=array_merge($message,$chkCsv[0]);

        // thai validate
        $chkCsv=$this->ThaiNationalID($data, $citizen_column, $line, $type, $success);
        $success=$chkCsv[1];
        $message=array_merge($message,$chkCsv[0]);
        
        return array($message,$success);
    }
    
    // check symbol number
    public function checkSymbol($data, $user, $all_data ,$symbol_column, $line, $success, $type)
    {
        $message=array();
        // database
        $chkDatabase=$this->checkDuplicateDatabase($data,$user,$symbol_column, $line, $success, $type);
        $success=$chkDatabase[1];
        $message=array_merge($message,$chkDatabase[0]);

        // csv
        $chkCsv=$this->checkDuplicateCsv($data, $symbol_column, $all_data, $success, $line, $type);
        $success=$chkCsv[1];
        $message=array_merge($message,$chkCsv[0]);
        
        return array($message,$success);
    }

    // check email
    public function checkEmail($data, $user, $all_data ,$email_column, $line, $success, $type)
    {
        $message=array();
        // database
        $chkDatabase=$this->checkDuplicateDatabase($data,$user,$email_column, $line, $success, $type);
        $success=$chkDatabase[1];
        $message=array_merge($message,$chkDatabase[0]);

        // csv
        $chkCsv=$this->checkDuplicateCsv($data, $email_column, $all_data, $success, $line, $type);
        $success=$chkCsv[1];
        $message=array_merge($message,$chkCsv[0]);
        
        return array($message,$success);
    }


    public function checkDuplicateDatabase(Array $data, $user, $column, $line, $success,  $type)
    {
        $message=array();

        if($data[$column]){
            if(in_array($data[$column],$user)){
                $success=false;
                array_push($message,['The '.$type.' on line '.$line.' : '.$data[$column].' already exists']);
            };
        }
        return array($message,$success);
    }

    public function checkDuplicateCsv(Array $data, $column, $all_data, $success, $line, $type)
    {
        $message=array();
        $line_collections=array();
        if($data[$column]){
            
            foreach($all_data as $key=>$tmp){
                if($data[$column]===$tmp[$column]){
                    array_push($line_collections,$key+1);
                    $success=false;
                 
                }
            }

            // if($key=array_search($data[$column],array_map(function($tmp) use ($column){ return $tmp[$column]; },$all_data))!==false){
            //     $success=false;
    
            //     array_push($message,['The '.$type.' on line '.$line.' : '.$data[$column].' is duplicate with line: '.($key+1)]);
            // };
            if($line_collections){
                array_push($message,['The '.$type.' on line '.$line.' : "'.$data[$column].'" is duplicate with line: '.(implode(',',$line_collections))]);
            }
        }
        return array($message,$success);
    }
    
    public function prepareData($file)
    {
        
        $file = fopen($file,'r');

        $prepare_data=array();
        
        while (($data = fgetcsv($file, ",")) !==FALSE ){
            array_push($prepare_data,$data);
        }
        fclose($file);
        
        return $prepare_data;
    }

}