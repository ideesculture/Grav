<?php
    $va_pages = $this->getVar("pages");
?>

<H1>Posts</H1>

<?php foreach ($va_pages as $content) : ?>
    <div class="actu-title"><?php print $content; ?></div>
<?php endforeach; ?>
