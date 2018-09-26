<script>
		$(document).ready(function() {
		$('#examlist').DataTable({
				responsive: true,
				"order":[[0,"desc"]],
			});
		} );
	</script>

<div class="row schooltitle">
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					  <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Member List'),array('controller'=>'comman','action' => 'memberlist'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>

					   <li class="active">

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
						<th><?php echo __('ISBN'); ?></th>
						<th><?php echo __('Book Name'); ?></th>
						<th><?php echo __('Author Name'); ?></th>
						<th><?php echo __('Rack Location'); ?></th>
						<th><?php echo __('Description'); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><?php echo __('ISBN'); ?></th>
						<th><?php echo __('Book Name'); ?></th>
						<th><?php echo __('Author Name'); ?></th>
						<th><?php echo __('Rack Location'); ?></th>
                        <th><?php echo __('Description'); ?></th>
					</tr>
				</tfoot>
				<tbody>
				<?php
				foreach($book_info as $get_book_info):
				
					foreach($rack_data as $rack):
				
						if($rack['category_id']== $get_book_info['rack_location']):
				?>
						<tr>
							<td>
							<p style='display:none;'><?php echo $get_book_info['id'];?></p>
							<?php echo $get_book_info['ISBN']; ?></td>
							<td><?php echo $get_book_info['book_name']; ?></td>
							<td><?php echo $get_book_info['author_name']; ?></td>
							<td><?php echo $rack['category_type']; ?></td>
							<td><?php echo $get_book_info['description']; ?></td>
						</tr>
				<?php
						endif;
					endforeach;
				endforeach;
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
