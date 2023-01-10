<?php if(isset($messages)) foreach($messages as $message) {
    if ($message['success']) { ?>
        <p><?= $message['content'] ?></p><br>
    <?php } else {?>
        <p><?= $message['content'] ?></p><br>
<?php } } ?>