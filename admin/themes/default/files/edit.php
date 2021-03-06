<?php
$fileTitle = metadata('file', 'display title');
if ($fileTitle != '') {
    $fileTitle = ': &quot;' . $fileTitle . '&quot; ';
} else {
    $fileTitle = '';
}
$fileTitle = __('Edit File #%s', metadata('file', 'id')) . $fileTitle;

queue_js_file(array('vendor/tiny_mce/tiny_mce', 'elements'));
echo head(array('title' => $fileTitle, 'bodyclass' => 'files edit'));
echo flash();
?>
<form method="post" action="">
    <section class="seven columns alpha" id="edit-form">
        <div id="file-metadata">
            <?php if (file_markup($file)): ?>
                <div id="item-images">
                    <?php echo file_markup($file, array('imageSize' => 'square_thumbnail'), array('class' => 'admin-thumb panel')); ?>
                </div>
            <?php endif; ?>    

            <?php foreach ($elementSets as $elementSet): ?>
            <fieldset>
                <h2><?php echo __($elementSet->name); ?></h2>
                <?php echo element_set_form($file, $elementSet->name); ?>
            </fieldset>
            <?php endforeach; ?>
        </div> <!-- end file-metadata div -->
        <?php fire_plugin_hook('admin_files_form', array('file' => $file, 'view' => $this)); ?>
    </section>

    <section class="three columns omega">
        <div id="save" class="panel">
            <input type="submit" name="submit" class="submit big green button" value="<?php echo __('Save Changes'); ?>" id="file_edit" /> 
            <?php if (is_allowed('Files', 'delete')): ?>
                <?php echo link_to($file, 'delete-confirm', __('Delete'), array('class' => 'big red button delete-confirm')); ?>
            <?php endif; ?>
        </div>
    </section>
</form>
<script type="text/javascript" charset="utf-8">
jQuery(window).load(function () {
    Omeka.wysiwyg({
        mode: "none",
        forced_root_block: ""
    });

    // Must run the element form scripts AFTER reseting textarea ids.
    jQuery(document).trigger('omeka:elementformload');
});

jQuery(document).bind('omeka:elementformload', function (event) {
    Omeka.Elements.makeElementControls(event.target, <?php echo js_escape(url('elements/element-form')); ?>,'File'<?php if ($id = metadata('file', 'id')) echo ', '.$id; ?>);
    Omeka.Elements.enableWysiwyg(event.target);
});
</script>
<?php echo foot(); ?>
