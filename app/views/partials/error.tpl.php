<?php
if (!empty($errorList)):
    foreach ($errorList as $error) :
?>
        <div class='alert alert-danger'>
            <?=$error?>
        </div>
<?php
    endforeach;
endif;
?>