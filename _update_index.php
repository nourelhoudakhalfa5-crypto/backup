<?php
$c = file_get_contents('index.php');

// 1. Remove all <style>...</style> blocks
$c = preg_replace('/<style>.*?<\/style>\s*/s', '', $c);

// 2. Remove the big inline <script> block (starts with "const lenis")
$c = preg_replace('/<script>\s*const lenis.*?<\/script>/s', '', $c);

// 3. Add index.css link after layout-refresh.css
$c = str_replace(
    '<link rel="stylesheet" href="assets/css/layout-refresh.css">',
    '<link rel="stylesheet" href="assets/css/layout-refresh.css">' . "\n" . '    <link rel="stylesheet" href="assets/css/index.css">',
    $c
);

// 4. Replace nav HTML with PHP include
$navPattern = '/<div class="menu">.*?<\/nav>\s*/s';
$c = preg_replace($navPattern, "<?php include 'includes/nav_index.php'; ?>\n\n", $c);

// 5. Replace footer + chatbot btn with PHP include
$footerPattern = '/<footer class="site-footer">.*?<\/footer>\s*<a href="chatbot\.php"[^>]*>.*?<\/a>/s';
$c = preg_replace($footerPattern, "<?php include 'includes/footer.php'; ?>", $c);

// 6. Add external index.js before main.js
$c = str_replace(
    '<script src="assets/js/main.js"></script>',
    '<script src="assets/js/index.js"></script>' . "\n" . '    <script src="assets/js/main.js"></script>',
    $c
);

file_put_contents('index.php', $c);
echo "index.php updated: " . strlen($c) . " bytes\n";

// Count remaining style/script tags
preg_match_all('/<style>/', $c, $m1);
preg_match_all('/<script>/', $c, $m2);
echo "Remaining <style> tags: " . count($m1[0]) . "\n";
echo "Remaining inline <script> tags: " . count($m2[0]) . "\n";
?>
