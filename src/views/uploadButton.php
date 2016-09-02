<?php
/** @var \dosamigos\fileupload\FileUpload $this */
/** @var string $input the code for the input */
?>

<span class="btn btn-success fileinput-button">
   <i class="glyphicon glyphicon-plus"></i>
   <span><?= Yii::t('fileupload', 'Select file...') ?></span>
   <!-- The file input field used as target for the file upload widget -->
    <?= $input ?>
</span>
