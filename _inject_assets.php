<?php
$files = glob('*.php');
$skip = ['_extract.php', '_update_index.php', '_update_nav.php', '_clean_pages.php', '_extract_nav_assets.php', '_inject_assets.php', 'index.php'];

foreach ($files as $file) {
    if (in_array($file, $skip)) continue;

    $c = file_get_contents($file);

    // Inject CSS before </head>
    // Remove existing nav-footer.css if any
    $c = str_replace('<link rel="stylesheet" href="assets/css/nav-footer.css" />', '', $c);
    $c = str_replace('<link rel="stylesheet" href="assets/css/nav-footer.css">', '', $c);
    // Remove index.css if any
    $c = str_replace('<link rel="stylesheet" href="assets/css/index.css" />', '', $c);
    $c = str_replace('<link rel="stylesheet" href="assets/css/index.css">', '', $c);
    
    $c = str_replace('</head>', "  <link rel=\"stylesheet\" href=\"assets/css/nav-footer.css\" />\n</head>", $c);

    // Inject JS before </body>
    // Remove existing nav.js if any
    $c = str_replace('<script src="assets/js/nav.js"></script>', '', $c);
    $c = str_replace('</body>', "<script src=\"assets/js/nav.js\"></script>\n</body>", $c);
    
    // Check if there are other inline things like the coverflow carousel script in product-*.php
    // We keep those.
    
    file_put_contents($file, $c);
    echo "Injected assets into $file\n";
}
?>
