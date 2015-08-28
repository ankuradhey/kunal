<?php
namespace Assessment\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Expression;
use Zend\Paginator\Paginator;
class TuploadsdownloadsTable
{
    protected $tableGateway;
    protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	
	//public function checkDetails-This function checks the user details userId,studentId,mentorId and subjectId based on userId
	public function checkDetailes($data,$files,$userId){
		if($data['hidpersonType']=='mentor'){
			$type=0;
			$mid=$userId;
			$sid=$data['hid_id'];
		}else{
			$mid=$data['hid_id'];
			$sid=$userId;
			$type=1;
		}
		
		$select = $this->tableGateway->getSql()->select();
		if($type==0){
			$select->where(array('student_id'=>$sid,'mentor_id'=>$mid));
			$select->where(array('type_downloaded'=>0));
		}else{
			$select->where(array('mentor_id'=>$mid,'student_id'=>$sid));
			$select->where(array('type_downloaded'=>1));
		}
		$select->where(array('subject_id'=>$data['uploadsubject'],'chapter_id'=>$data['uploadchapters'],'pdf_file_name'=>$files['myfile']['name']));
		
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	
	//public function addDetails-This function downloads file when downloading file stored details(studentId,mentorId,subjectId,chapterId,fileName,typeDownloadName,uploadedDate,status)
	public function addDetails($data,$files,$userId)
        {		 
		if($data['hidpersonType']=='mentor'){
			$type=0;
			$mid=$userId;
			$sid=$data['hid_id'];
		}else{
			$mid=$data['hid_id'];
			$sid=$userId;
			$type=1;
		}
		$data = array(
			'student_id'  		=> $sid,
			'mentor_id'  		=> $mid,
			'subject_id'  		=> $data['uploadsubject'],
			'chapter_id'  		=> $data['uploadchapters'],
			'pdf_file_name'  	=> $files,
			'type_downloaded'  	=> $type,
			'custom_board_rack_id'  => $data['customBoardRackID'],
		         'posted_date'  	=> date('Y-m-d H:i:s'),
			'status'  		=> 0,			
						
		);	
		$result=$this->tableGateway->insert($data);
		return $this->tableGateway->lastInsertValue;			        
        }
        
      public function addquesDetails($data,$userId,$relation_id='')
        {		 
            $sid  = $userId;
            $mid  = (!empty($data['stud_mentor_id']))?$data['stud_mentor_id']:'';
            $type = 1;
	    $data = array(
                        'student_id'        => $sid,
                        'mentor_id'         => $mid,
                        'subject_id'        => (isset($data['subjectId']) ?$data['subjectId']:''),
                        'chapter_id'        => (isset($data['chapter_id']) ?$data['chapter_id']:''),
                        'pdf_file_name'     => $data['image_file'],
                        'type_downloaded'   => $type,
                        'posted_date'       => date('Y-m-d H:i:s'),
                        'status'  	    => 0,
                        'download_for'      =>$data['for_ques'],
                        'custom_board_rack_id'  => $data['customBoardRackID'],
                        'download_relation_id'=>$relation_id,
                        'file_name'         =>$data['file_name'],
                    );	
            $result=$this->tableGateway->insert($data);
            return $this->tableGateway->lastInsertValue;			        
        }  
	//public function getAll-This function get all uploaded files list based on up_down_id
	public function getAll($type,$action,$id,$subjectId,$userId)
	{
	  $select = $this->tableGateway->getSql()->select();
        // $select->join('board_content_view', 'board_content_view.board_class_subject_chapter_id=t_uploads_downloads.chapter_id',array('board_class_parent_subject_id','parent_subject_name','subject_name','chapter_name'),'left');
           $select->join(array('r1' => 'resource_rack'), 'r1.rack_id=t_uploads_downloads.chapter_id', array("chepter_id"=>"rack_id"), 'left');
           $select->join(array('rn1' => 'rack_name'), 'rn1.rack_name_id=r1.rack_name_id', array('chepter_name' => 'name'), 'left');
           $select->join(array('r2' => 'resource_rack'), 'r2.rack_id=r1.rack_container_id', array("subject_id"=>'rack_id'), 'left');
           $select->join(array('rn2' => 'rack_name'), 'rn2.rack_name_id=r2.rack_name_id', array('subject_name' => 'name'), 'left');
           $select->join(array('r3' => 'resource_rack'), 'r3.rack_id=r2.rack_container_id', array("class_id"=>'rack_id'), 'left');
           $select->join(array('rn3' => 'rack_name'), 'rn3.rack_name_id=r3.rack_name_id', array('class_name' => 'name'), 'left');
                 

        if($type=='mentor' && $action=='up'){
               $select->where('mentor_id='.$userId);
               $select->where('student_id='.$id);
               $select->where('type_downloaded=0');	
        }else if($type=='student' && $action=='down'){
               $select->where('student_id='.$userId);
               $select->where('mentor_id='.$id);
               $select->where('type_downloaded=0');
        }
        else if($type=='mentor' && $action=='down'){
               $select->where('mentor_id='.$userId);
               $select->where('student_id='.$id);
               $select->where('type_downloaded=1');
        }else if($type=='student' && $action=='up'){
               $select->where('t_uploads_downloads.student_id='.$userId);
               $select->where('t_uploads_downloads.mentor_id='.$id);
               $select->where('type_downloaded=1');
        }
         $select->where(array('(t_uploads_downloads.subject_id IN ('.$subjectId.'))'));
         $select->group('up_down_id');
         $resultSet = $this->tableGateway->selectWith($select)->buffer();
          return $resultSet;
	}
	
	//public function getUploadAndDownloads-This function uploaded files are download stored in another table on db,here two tables joined  
	public function getUploadAndDownloads($id,$type)
	{
		$select = $this->tableGateway->getSql()->select();
		
		if($type=='Student'){
			$select	->join('user', 'user.user_id=t_uploads_downloads.mentor_id',array('*'),'left');
			$select->where(array('t_uploads_downloads.student_id'=>$id,'t_uploads_downloads.status'=>0,'t_uploads_downloads.type_downloaded'=>0));
		}else{
			$select	->join('user', 'user.user_id=t_uploads_downloads.student_id',array('*'),'left');
			$select->where(array('t_uploads_downloads.mentor_id'=>$id,'t_uploads_downloads.status'=>0,'t_uploads_downloads.type_downloaded'=>1));
		}
		
		$select->join('board_content_view', 'board_content_view.board_class_subject_chapter_id=t_uploads_downloads.chapter_id',array('board_id','board_name','class_name','subject_name','chapter_name','board_class_id','board_class_parent_subject_id','parent_subject_name','board_class_subject_id'),'left');
		
		 $select->group('up_down_id');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function changeStatus($id)
        {	
            $data = array(
                    'status'  					=> 	1,				
            );
            $result=$this->tableGateway->update($data, array('up_down_id' => $id));
            return $result;			        
        }
    
        public function getcurrentfilename($id){
            $select = $this->tableGateway->getSql()->select();
            $select->where(array('up_down_id'=>$id));
            $resultSet = $this->tableGateway->selectWith($select)->current();
            return $resultSet;
        } 
    
    /* This function is used to upload the files and image on target path */
    
    public function ftpFileUploaded($sourcePath, $targetPath, $ftpDetails){
        $conn_id = ftp_connect($ftpDetails['constants']['FTP_SERVER']);        // set up basic connection        
        $login_result = ftp_login($conn_id, $ftpDetails['constants']['FTP_USERNAME'], $ftpDetails['constants']['FTP_PASSWORD']); // ftp login     
        if($login_result){
            $upload = ftp_put($conn_id, $targetPath, $sourcePath, FTP_BINARY);  // upload the file
            if (!$upload) {  // check upload status
                $fileStatus = 'error';
            } else {
                $fileStatus = 'success';
            }
        }else{
            $fileStatus = 'error';
        }       
        ftp_close($conn_id); // close the FTP stream               
        return $fileStatus;
    }
    
   public function output_file($file, $name, $mime_type = '',$currentfile= '') {  
        if (!is_readable($file))
            die('File not found or inaccessible!');
        $size = filesize($file);
        
        $name = rawurldecode($name);        
        
        $known_mime_types = array(
            "pdf" => "application/pdf",
            "txt" => "text/plain",
            "html" => "text/html",
            "htm" => "text/html",
            "exe" => "application/octet-stream",
            "zip" => "application/zip",
            "doc" => "application/msword",
            "xls" => "application/vnd.ms-excel",
            "xlsx" => "application/vnd.ms-excel",
            "ppt" => "application/vnd.ms-powerpoint",
            "gif" => "image/gif",
            "png" => "image/png",
            "jpeg" => "image/jpg",
            "jpg" => "image/jpg",
            "php" => "text/plain"
        );
       
        if ($mime_type == '') {            
            $file_extension = strtolower(substr(strrchr($file, "."), 1));            
            $mime_type = $known_mime_types[$file_extension];
            if($mime_type != '')
            {
                $mime_type = "application/force-download";
             }
           if(!empty($currentfile)){  
                 $currentfile = rawurldecode($currentfile);
                 $name = ($currentfile !='')?$currentfile:$name;
           }
        @ob_end_clean();
        if (ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');

        header('Content-Type: ' . $mime_type);
        header('Content-Disposition: attachment; filename="' . $name . '"');
        header("Content-Transfer-Encoding: binary");
        header('Accept-Ranges: bytes');
        header("Cache-control: private");
        header('Pragma: private');
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        if (isset($_SERVER['HTTP_RANGE'])) {
            list($a, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);
            list($range) = explode(",", $range, 2);
            list($range, $range_end) = explode("-", $range);
            $range = intval($range);
            if (!$range_end) {
                $range_end = $size - 1;
            } else {
                $range_end = intval($range_end);
            }
            $new_length = $range_end - $range + 1;
            header("HTTP/1.1 206 Partial Content");
            header("Content-Length: $new_length");
            header("Content-Range: bytes $range-$range_end/$size");
        } else {
            $new_length = $size;
            header("Content-Length: " . $size);
        }
        $chunksize = 1 * (1024 * 1024);
        $bytes_send = 0;
        if ($file = fopen($file, 'r')) { 
            if (isset($_SERVER['HTTP_RANGE']))
                fseek($file, $range);

            while (!feof($file) &&
            (!connection_aborted()) &&
            ($bytes_send < $new_length)
            ) {
                $buffer = fread($file, $chunksize);
                print($buffer);
                flush();
                $bytes_send += strlen($buffer);
            }
           
            fclose($file);
        }
        else{
            die('Error - can not open file.');
        }
        
        
    }
   }
    
}