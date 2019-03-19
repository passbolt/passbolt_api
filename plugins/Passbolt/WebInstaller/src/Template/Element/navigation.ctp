<div class="navigation wizard">
    <ul>
        <?php
        if (!isset($selectedSection)) {
            $selectedSection = 'system_check';
        }
        $disabled = false;
        $className = '';
        $i = 0;
        foreach($navigationSections as $slug => $name) {
            if($slug == $selectedSection)
                $className = 'selected';
            ?>
            <li class="<?php echo $className ?>">
                <?php echo $i + 1 ?>. <?php echo $name ?>
            </li>
            <?php
            if($slug == $selectedSection) {
                $className = 'disabled';
            }
            $i++;
        }
        ?>
    </ul>
</div>