<?php
$stud_date = $this->Setting->getfieldname('date_format');
$current_date = date('Y-m-d');
$current_date = date($stud_date,strtotime($current_date));
?>

<html>
<head>
</head>
<body>
   <?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'returnbook']]);?>

    <h4><?php echo $student_name.'  Date: '.$current_date; ?></h4>
    <input type="hidden" value="<?php echo $current_date;?>" name="returndate">

    <table class="table">
        <thead>
        <tr>
            <th><?php echo '';?></th>
            <th><?php echo __('Book Name');?></th>
            <th><?php echo __('Overdue By')?></th>
            <th><?php echo __('Fine')?></th>
        </tr>
        </thead>
        
          <tbody>
        <?php
            foreach($get_book_wise as $get_issue){
                
                    foreach($get_book_in as $book_info){
                        
                        if($get_issue['book_id'] == $book_info['id']){
        ?>
      
            <tr>
                <td><input type="checkbox" name="book_return[]" value="<?php echo $get_issue['id'] ?>" id="return_<?php echo $get_issue['id']?>" class="ret"></td>
                <td><?php echo $book_info['book_name']; ?></td>
                <td><?php
						$c_date=time();
						$i_date=strtotime($get_issue['end_date']);	
						// var_dump($c_date." ".$i_date);		
						if($i_date < $c_date)
							$day_between=ceil(abs($c_date-$i_date)/86400);
						else
							$day_between=0;
						// $diff = $c_date - $i_date;
						// $day_between=round($diff / (60 * 60 * 24));
						echo $day_between.' Days';                   
                    ?></td>
                <td><input type="text" name="fine[]" value="" id="fine_<?php echo $get_issue['id'];?>"></td>
            </tr>
              
              
			<script>
              $(function(){
                  
                $('#submit').attr('disabled','disabled');
                 $('#return_'+<?php echo $get_issue['id']; ?>).click(function(){
                    if($(this).is(':checked')){
                        $('#fine_'+<?php echo $get_issue['id'];?>).attr('value','0');
                         $('#submit').removeAttr('disabled');
                    }else{
                        $('#fine_'+<?php echo $get_issue['id'];?>).attr('value','');
                    
                    }
                 }); 
                  
                  
              });
              </script>
             
    <?php
                        }
            }
            }
    ?>
              
        </tbody>
    </table>
    
    <center>
	<?php echo $this->Form->input(__('Submit Book'),array('type'=>'submit','disabled'=>'disabled','class'=>'btn btn-success','id'=>'submit'));?>
	<!--<input type="submit" value="Submit Book" id="submit" class="btn btn-success" disabled="disabled">---></center>
  <?php echo  $this->Form->end(); ?>
    
</body>
</html>