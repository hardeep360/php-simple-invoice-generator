<?php
namespace root\appcode;
use PDO;
/**
 * Created by PhpStorm.
 * User: hardeepsingh
 * Date: 23/01/18
 * Time: 4:43 PM
 */

class MyPDO extends PDO
{

    var $lastStatment = null;
    var $_lastResults = null;

    public function query( $sql, $params = array() )
    {
        //insert into tbl (a,b,c) values ( ?,?,? )


 
        $st = $this->prepare($sql);
         
       // $st->bindParam(1, $var1 );
     //   $st->bindParam(2, $var2 );
        $x = 1;
        if( is_array($params)  ) {
            if (count($params) > 0) {
                foreach ($params as $key => &$param) {

                    if (is_numeric($key)) {

                        $st->bindParam($x, $param);
                    } else {
                        $st->bindParam($key, $param);
                    }
                    $x++;
                }
            }
        }
        else if( $params != '' )
        {

            $st->bindParam($x, $params);

        }



        $this->lastStatment = $st;



        $res = $this->lastStatment->execute();

        

        if(  stripos ($sql,'select') > -1 && stripos ($sql,'select') <= 20 )
        {
//               echo " SELECT * FROM tbluser WHERE username = 'manpreetsingh' and password = '202cb962ac59075b964b07152d234b700'";

            $this->_lastResults = $this->lastStatment->fetchAll(PDO::FETCH_ASSOC);
        }
        else 
        {
            $this->_lastResults = null;
        }

        return $res;

    }

    public function getErrorMsg()
    {
        if( $this->lastStatment )
        {
            return $this->lastStatment->errorInfo();
        }
        return '';
    }

    public function insertID()
    {
        return $this->lastInsertId();
    }


    public function fetchRows()
    {
        if( $this->_lastResults != null )
        {
            return $this->_lastResults;
        }


        return $this->lastStatment->fetchAll(PDO::FETCH_ASSOC);

    }

    public function fetchRow()
    {

 
        if( $this->_lastResults != null )
        {
            return $this->_lastResults[0];
        }

        
        return $this->lastStatment->fetch(PDO::FETCH_ASSOC);

    }

    public function affectedRows()
    {
        return $this->lastStatment->rowCount();
    }

    public function getErrorInfo()
    {

        return  implode("<br />", $this->lastStatment->errorInfo());
    }


    public function numRows()
    {

        if( $this->_lastResults != null && is_array($this->_lastResults) )
        {
// echo " SELECT * FROM tbluser WHERE username = 'manpreetsingh' and password = '202cb962ac59075b964b07152d234b700'";

            return count($this->_lastResults);
        }

        return '0';

    }


    public function DbeSelectBox ($query,$comboValue,$comboDisplayvalue,$selected,$attribute=NULL,$comboName=NULL)
	{
		$str='';

		 $this->query( $query);
         $rows = $this->fetchRows();

         if ( !is_array($rows)   ) {
			
			return "DbeSelectBox Query cannot be executed";
		}
		if($comboName!=NULL){ $str= "<select name='".$comboName."' id='".$comboName."' $attribute>"; }
		$str.= '<option value="" selected="selected">Select</option>';

        foreach( $rows as $rowData )
        {
            if($rowData[$comboValue]!==NULL and $rowData[$comboDisplayvalue]!==NULL)
			{ 
				if($selected==$rowData[$comboValue]){
					$str.= '<option value="'.$rowData[$comboValue].'" selected="selected">'.ucwords($rowData[$comboDisplayvalue]).'</option>';
				}
				else
				{
					$str.='<option value="'.$rowData[$comboValue].'">'.ucwords($rowData[$comboDisplayvalue]).'</option>';
				}
			}
			else 
			{
				return "Column cannot be found";
			} 
        }

		if($comboName!=NULL){ $str.= "</select>"; }
		return $str;
}//End of DbeSelectBox

    
public function DbeSelectBoxMultiple ($query,$comboValue,$comboDisplayvalue,$selected=array(),$attribute=NULL,$comboName=NULL,$IsMultiple=false)
	{
		 $this->query($query);
         /*if (!$this->_queryResult) {
			$this->_debug();
			return "DbeSelectBox Query cannot be executed";
		} */

         $rows = $this->fetchRows();

         if ( !is_array($rows) || count($rows) <= 0  ) {
			
			return "DbeSelectBox Query cannot be executed";
		}
         
		if($comboName!=NULL){ $str= "<select name='".$comboName."' $attribute ".($IsMultiple==true?'multiple="multiple"':'').">"; }

		 foreach( $rows as $rowData )
        {

            if($rowData[$comboValue]!==NULL and $rowData[$comboDisplayvalue]!==NULL)
			{ 
				if(in_array($rowData[$comboValue],$selected)){
					$str.= '<option value="'.$rowData[$comboValue].'" selected="selected">'.$rowData[$comboDisplayvalue].'</option>';
				}
				else
				{
					$str.= '<option value="'.$rowData[$comboValue].'">'.$rowData[$comboDisplayvalue].'</option>';
				}
			}
			else 
			{
				return "Column cannot be found";
			} 
		}//end while
		if($comboName!=NULL){ $str.= "</select>"; }
		return $str;
}//End of DbeSelectBoxMultiple
}