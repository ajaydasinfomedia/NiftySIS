<h4><?php echo $name;?></h4>

<hr>
<table class="table table-bordered">
    <thead>
    <tr>
        <th><?php echo __('Book name');?></th>
        <th><?php echo __('Issue Date');?></th>
        <th><?php echo __('Return Date');?></th>
        <th><?php echo __('Period');?></th>
        <th><?php echo __('Overdue By');?></th>
    </tr>
    </thead>
    
    <?php 
        foreach($get_book as $lib):
        
           foreach($cat_info as $cate):
    
                if($cate['category_id'] == $lib['period']){
					
					$stud_date = $this->Setting->getfieldname('date_format');
					// var_dump($lib['end_date']);
    ?>
    
    <tbody>
        <Tr>
            <Td><?php 
                    $get_book_id=$lib['book_id'];
                                $trav=explode(",",$get_book_id);
                                            
                                            $get_book_arr=array();
                                                for($i=0;$i<count($trav);$i++){
                                        
                                                        foreach($gb_info as $get_book):
                                            
                                                            if($get_book['id'] == $trav[$i]){
                                                                    $get_book_arr[]=$get_book['book_name'];
                                                            }
                                        
                                        endforeach;
           
                                    }        
                                            $arrtostr=implode(",",$get_book_arr);
                                            echo $arrtostr;
                
                
                
                ?></Td>
             <Td><?php echo date($stud_date,strtotime($lib['issue_date'])); ?></Td>
             <Td><?php echo date($stud_date,strtotime($lib['end_date'])); ?></Td>
             <Td><?php echo $cate['category_type'].' '.__('Day'); ?></Td>
             <Td><?php 
                        $overdue="";
                    
                        if($lib['status'] == 'Submitted'){
                            /* $e_date=strtotime($lib['actual_return_date']);
                            $i_date=strtotime($lib['issue_date']);
							// var_dump($e_date."  ".$i_date);
                            $day_between=ceil(abs($e_date-$i_date)/86400); */
							
							$c_date=time();
							$i_date=strtotime($lib['end_date']);	
							// var_dump($c_date." ".$i_date);		
							if($i_date < $c_date)
								$day_between=ceil(abs($c_date-$i_date)/86400);
							else
								$day_between=0;
							
                            echo $overdue=$day_between.' Days';
                        }else{
                            echo $overdue="No Return";
                        }
                    
                    
                   
                 
                 ?></Td>
        </Tr>
        <?php
                }
            endforeach;
        endforeach;
        ?>
    </tbody>


</table>