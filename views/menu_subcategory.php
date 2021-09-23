<?php foreach($subs as $sub): ?>

    <li>
        <a href="<?php echo BASE_URL . 'categories/enter/' . $sub['id'];    ?>      ">
            <?php 
            for($i = 0; $i < $level; $i++) echo '--- ';
                echo $sub['name'];
            ?>
        </a>
    </li>

    <?php 
        if(count($sub['subs']) > 0) {
            $this->loadView('menu_subcategory', array(
                'subs' => $sub['subs'],
                'level' => $level + 1
            ));

        }
    ?>
<?php endforeach ?>