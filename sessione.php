<?php

$htmlPage = file_get_contents("html/sessione.html");

$footer = file_get_contents("html/components/footer.html");

$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);

echo $htmlPage;

?>