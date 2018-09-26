<script>
		$(document).ready(function() {
		$('#examlist').DataTable({responsive: true});
		} );
</script>

<script>

$(document).ready(function(){
$('body').on('click', '.viewmodal', function() {
     var stud_id=$(this).attr('id');

       $.ajax({
		              type: 'POST',
		              url: '<?php echo $this->Url->build(["controller" => "Library","action" => "studentviewbook"]);?>',
		              data : {
		              		student_id:stud_id,

							},
						success: function (data)
		                  {

				            $('#modal-view').html(data);
		   				 },
                            beforeSend:function(){
                                 $('#modal-view').html('<center><h4>Loading...</h4></center>');
                            },
		                     error: function(e) {
		                     console.log(e);
		                 }

		        });
   });


	$('body').on('click', '.returnmodal', function() {	
     var stud_ident=$(this).attr('id');

       $.ajax({
		              type: 'POST',
		              url: '<?php echo $this->Url->build(["controller" => "Library","action" => "returnbook"]);?>',
		              data : {
		              		student_id:stud_ident
							},
						success: function (data)
		                  {

				            $('#modal-return').html(data);
		   				 },
                            beforeSend:function(){
                                 $('#modal-return').html('<center><h4>Loading...</h4></center>');
                            },
		                     error: function(e) {
		                     console.log(e);
		                 }

		        });
   });

});


</script>



    <div class="modal fade " id="myModalview" role="dialog">
    <div class="modal-dialog modal-md"  >

      <div class="modal-content">
        <div class="modal-header" >
          <a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
		 <h4> <?php echo __('Student Library History'); ?> </h4>
        </div>
        <div class="modal-body" id="modal-view">


        </div>
        <div class="modal-footer">

          <button type="button" class="btn btn-default" data-dismiss="modal"> <?php echo __('Close'); ?> </button>
		</div>

        </div>
      </div>

    </div>



    <div class="modal fade " id="myModalreturn" role="dialog">
    <div class="modal-dialog modal-md"  >

      <div class="modal-content">
        <div class="modal-header" >
          <a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
		 <h4> <?php echo __('Student Library History'); ?> </h4>
        </div>
        <div class="modal-body" id="modal-return">


        </div>
        <div class="modal-footer">


		</div>

        </div>
      </div>

    </div>


<div class="row schooltitle">
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					  <li class="active">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Member List'),array('controller'=>'comman','action' => 'memberlist'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>

					   <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Book List'),array('controller'=>'comman','action' => 'booklist'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>


				</ul>
</div>


<div class="panel-body">
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th><?php echo __('Photo');?></th>
						<th><?php echo __('Student ID');?></th>
						<th><?php echo __('Student Name');?></th>
						<th><?php echo __('Class');?></th>
                        <th><?php echo __('Roll No');?></th>
                        <th><?php echo __('Student Email');?></th>
                        <th><?php echo __('Action');?></th>

					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><?php echo __('Photo');?></th>
						<th><?php echo __('Student ID');?></th>
						<th><?php echo __('Student Name');?></th>
						<th><?php echo __('Class');?></th>
                        <th><?php echo __('Roll No');?></th>
                        <th><?php echo __('Student Email');?></th>
                        <th><?php echo __('Action');?></th>
					</tr>
				</tfoot>
				<tbody>

                    <?php
						if($role=='student')
						{
							foreach($query as $issue_info)
							{                   
								foreach($get_student as $stud_info)
								{
									if($issue_info['student_id'] == $stud_info['user_id'])
									{
									?>
									<tr>
										<td><?php echo $this->Html->image($this->Setting->get_user_image($issue_info['student_id']),array('height'=>'50px','width'=>'50px','class'=>'profileimg')) ;?></td>
										<td><?php echo $this->Setting->get_studentID($issue_info['student_id']); ?></td>
										<td><?php echo $this->Setting->get_user_id($issue_info['student_id']);?></td>
										<td><?php echo $this->Setting->get_class_id($issue_info['class_id']);?></td>
										<td><?php echo $this->Setting->get_user_roll_no($issue_info['student_id']);?></td>
										<td><?php echo $this->Setting->get_user_email_id($issue_info['student_id']);?></td>
										<td>
										<?php
										if($issue_info['student_id']==$user_session_id)
										{ 
										?>
											<button type="button" id="<?php echo $issue_info['student_id'];?>" data-toggle="modal" data-target="#myModalview" class="btn btn-info viewmodal" style=""> <?php echo __('View'); ?> </button>
										<?php
										}
										else{}
										?>
										</td>
									</tr>
								<?php
								}
							}
						}
					}       
					else
					{					
                        foreach($query as $issue_info)						
						{       
							 foreach($get_student as $stud_info)
							 {
                                if($issue_info['student_id'] == $stud_info['user_id'])
								{
								?>
									<tr>
										<td><?php echo $this->Html->image($this->Setting->get_user_image($issue_info['student_id']),array('height'=>'50px','width'=>'50px','class'=>'profileimg')) ;?></td>
										<td><?php echo $this->Setting->get_studentID($issue_info['student_id']); ?></td>
										<td><?php echo $this->Setting->get_user_id($issue_info['student_id']);?></td>
										<td><?php echo $this->Setting->get_class_id($issue_info['class_id']);?></td>
										<td><?php echo $this->Setting->get_user_roll_no($issue_info['student_id']);?></td>
										<td><?php echo $this->Setting->get_user_email_id($issue_info['student_id']);?></td>
										<td>
											<button type="button" id="<?php echo $issue_info['student_id'];?>" data-toggle="modal" data-target="#myModalview" class="btn btn-info viewmodal" style=""> <?php echo __('View'); ?> </button>
											<button type="button" id="<?php echo $issue_info['student_id'];?>" data-toggle="modal" data-target="#myModalreturn" class="btn btn-success returnmodal" style=""> <?php echo __('Accept Return'); ?> </button>
										</td>
									</tr>
								<?php                             
								}
							}
						}
                     }
                   ?>
				</tbody>
				</table>
		</div>
	</div>
</div>
