<div class="row">
    <?php
    $a = 0;
    ?>
    <?php foreach ($list as $product_item) : ?>
        <div class="col-sm-4">
            <?php $this->loadView('product_item', $product_item); ?>
        </div>
        <?php
        if ($a >= 2) {
            $a = 0;
            echo '</div> <div class="row">';
        } else {
            $a++;
        }
        ?>
    <?php endforeach; ?>
</div>
<div class="paginationArea">

    <?php for ($i = 1; $i <= $numberOfPages; $i++) : ?>
        <div class="paginationItem <?php echo ($currentPage == $i) ? 'pag_active' : ''; ?>"><a href="<?php echo BASE_URL; ?>?p=<?php echo $i ?>"> <?php echo $i ?> </a></div>
    <?php endfor ?>

</div>