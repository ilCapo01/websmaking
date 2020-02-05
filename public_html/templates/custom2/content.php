
<div id="content">
<div class="inside-content">
        
<?php global $q; 


echo $q['text'];

?>
        </div>
    </div>
    
<?php if ($b['sidebarVisibility'] == 1 ): ?>
<aside id="sidebar">
<section class="widget">
<?php echo $b['sidebar']; ?>
</section>
</aside>
<?php endif; ?>