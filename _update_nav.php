<?php
$navIndex = file_get_contents('includes/nav_index.php');
$mobileLogo = "<a href=\"index.php\" class=\"logo lg:hidden absolute top-5 left-6 z-50\">\n    <img src=\"assets/images/Plan de travail 1 1.png\" alt=\"RDOC\" class=\"h-10\" />\n</a>\n";

$combined = $mobileLogo . "\n" . $navIndex . "\n<?php include __DIR__ . '/nav_mobile.php'; ?>\n";
file_put_contents('includes/nav.php', $combined);

// Update index.php
$index = file_get_contents('index.php');
$index = str_replace("<?php include 'includes/nav_index.php'; ?>", "<?php include 'includes/nav.php'; ?>", $index);
file_put_contents('index.php', $index);

echo "nav.php updated\n";
?>
