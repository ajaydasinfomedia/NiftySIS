<script>
	
		  function PrintElem(elem)
			{
					Popup($(elem).html());
			}

    function Popup(data) 
    {
        var mywindow = window.open('', 'Print Expense Invoice', 'height=600,width=800');
       
        mywindow.document.write(data);
       

        mywindow.document.close();
        mywindow.focus();

        mywindow.print();
        mywindow.close();

        return true;
    }

	
	</script>

	<?php

	

	$admin_user=$admin_info['first_name'].' '.$admin_info['last_name'];

	?>

<div class="print_show">


<table class="table" width="100%" style="border:1px solid #ddd">