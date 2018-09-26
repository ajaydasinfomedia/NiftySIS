<?php  
$session = $this->request->session();
echo $session->read('user_id');
?>