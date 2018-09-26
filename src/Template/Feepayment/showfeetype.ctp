
<select class="form-control" id="ftypeid" name="fees_id">
	<option value=""><?php echo __('---Select Fee Type---'); ?> </option>



<?php 
					   $fees=array();
                       $cat=array();
                       $c=array();

                       foreach ($getcategory_id as $cat_id) {
                           foreach($getfeestype_id as $getfees){
                                if($cat_id['category_id']== $getfees['fees_title_id']){
                                 ?>
                                 <option value="<?php echo $cat_id['category_id']; ?>"><?php echo $cat_id['category_type'];?></option>
                                 <?php   
                                }
                          }

                       }

                      
?>

</select>