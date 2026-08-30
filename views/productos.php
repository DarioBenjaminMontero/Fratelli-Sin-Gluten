<?php 
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : 'default';
?>

<div id = "Productos"> 

 

</div>
<script>

const categoria = <?php echo json_encode($categoria) ?>

</script> 
<script src = "<?= $script ?>">

</script>