    <!-- <div id="post-body" <?php echo ($b['sidebarVisibility'] == 1 ? 'style="width: 72%;'.($b['language_code'] == 'en' ? 'float:right;' : 'float:left;').'"' : ''); ?> -->
        
<?php global $q; 


echo $q['text'];

?>

</div>

<?php if ($b['sidebarVisibility'] == 1 ): ?>

<div class="sidebartxt">
  <!--   <?php echo $b['sidebar']; ?> -->
</div>
<?php endif; ?>
    </div>
<script src="https://static.codepen.io/assets/common/stopExecutionOnTimeout-de7e2ef6bfefd24b79a3f68b414b87b8db5b08439cac3f1012092b2290c719cd.js"></script>
<script id="rendered-js">
    </script>