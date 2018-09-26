
			<select name="book_id[]" id="example-dropRight" multiple class="form-control">
  						<?php
                			foreach ($lib_info as $lib) {
                 		?>
  						<option value="<?php echo $lib['id']; ?>"><?php echo $lib['book_name']; ?></option>
                 <?php
             	 }
					?>
				</select>

<script>
    $(function(){
      
            $('#example-dropRight').multiselect({
                buttonWidth: '180px',
                dropRight: true
            });
        });
 
</script>
