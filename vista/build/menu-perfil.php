<div class="profile clearfix">
	<div class="profile_pic">
		<?php
			if ( $_SESSION["rpt"] == 1 ) {				
		?>
    			<img src="../../imagenes/perfil.png" alt="foto" class="img-circle profile_img">
    	<?php
    		} else {
    	?>
    			<img src="../../imagenes/sin-perfil.png" alt="foto" class="img-circle profile_img">
    	<?php
    		} 
    	?>
  	</div>    
    <div class="profile_info">
      <span>Bienvenido,</span>
      <h2><?php echo $objAcceso->getToken(); ?></h2>
    </div>
</div>  