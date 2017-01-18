<?php
$va_page = $this->getVar("page");
?>
<H1><?php print $va_page["title"]; ?></H1>
<h3><?php print $va_page["date"]; ?></h3>
<?php print $va_page["content"]; ?>