<div class="row">
    <div class="col-sm-5">
        FOTOS
    </div>

    <div class="col-sm-7">
        <h2><?php echo $product_info['name']; ?></h2>
        <small><?php echo $product_info['brand_name']; ?></small><br>
        <?php if($product_info['rating'] != '0'):?>
            <?php for($i = 0; $i<intval($product_info['rating']); $i++): ?>
                <img src="<?php echo BASE_URL; ?>assets/images/star.png" border="0" height="15">
            <?php endfor; ?>
        <?php endif; ?>    
        <hr>
        <p><?php echo $product_info['description'];  ?></p>
    </div>
</div>