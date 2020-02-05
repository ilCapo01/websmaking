<?php if ($b['sidebarVisibility'] == 1 ): ?>

<div class="sidebartxt">
    <?php echo $b['sidebar']; ?>
</div>
<?php endif; ?>

   <div class="inside-content" <?php echo ($b['sidebarVisibility'] == 1 ? 'style='.($b['language_code'] == 'en' ? 'float:right;' : 'float:left;').'"' : ''); ?>>
        
<?php global $q; 


echo $q['text'];

?>
        </div>
    </div>
    