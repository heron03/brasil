<?php
echo $this->Html->scriptBlock(
    'document.location = "' . $this->Url->build('/') . '";'
);
