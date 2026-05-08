<?php
$files = glob('*.php');
$skip = ['_extract.php', '_update_index.php', '_update_nav.php', '_clean_pages.php', 'rdoc_db.sql', 'task.md', 'implementation_plan.md'];

foreach ($files as $file) {
    if (in_array($file, $skip)) continue;

    $c = file_get_contents($file);

    // Remove legacy navs
    $c = preg_replace('/<div class="menu">.*?<\/nav>\s*/s', '', $c);
    $c = preg_replace('/<nav class="fixed.*?id="navbar">.*?<\/nav>\s*/s', '', $c);
    
    // Remove nav-pill-bar (there might be multiple wrappers, but the CSS is hidden, so removing the block is best)
    // Wait, let's just remove the main <nav> or <div> that holds nav-pill-bar
    $c = preg_replace('/<div class="nav-pill-bar.*?>.*?<\/div>\s*/s', '', $c);
    
    // Remove fixed desktop navbar
    $c = preg_replace('/<div class="fixed top-0 left-0 right-0 z-50.*?>.*?<\/div>\s*/s', '', $c);
    
    // Remove fixed mobile bottom navbar
    $c = preg_replace('/<div class="fixed bottom-0 left-0 right-0 lg:hidden.*?>.*?<\/div>\s*/s', '', $c);
    
    // Remove mobile logo
    $c = preg_replace('/<a href="index\.php" class="logo lg:hidden.*?>.*?<\/a>\s*/s', '', $c);

    // Remove old includes
    $c = str_replace("<?php include 'includes/nav_index.php'; ?>", "", $c);
    $c = str_replace("<?php include 'includes/nav.php'; ?>", "", $c);
    
    // Insert new nav include right after <body>
    $c = preg_replace('/(<body[^>]*>)/i', "$1\n<?php include 'includes/nav.php'; ?>\n", $c, 1);

    // Remove old footer and chatbot
    $c = preg_replace('/<footer[^>]*>.*?<\/footer>\s*/s', '', $c);
    $c = preg_replace('/<a href="chatbot\.php"[^>]*>.*?<\/a>\s*/s', '', $c);
    $c = preg_replace('/<a href="chatbot\.html"[^>]*>.*?<\/a>\s*/s', '', $c);
    
    // Remove old footer includes
    $c = str_replace("<?php include 'includes/footer.php'; ?>", "", $c);
    
    // Insert new footer include right before </body>
    $c = preg_replace('/(<\/body>)/i', "<?php include 'includes/footer.php'; ?>\n$1", $c, 1);

    file_put_contents($file, $c);
    echo "Cleaned $file\n";
}
?>
