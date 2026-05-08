<?php
$files = glob('*.php');
$skip = ['_extract.php', '_update_index.php', '_update_nav.php', '_clean_pages.php', '_extract_nav_assets.php', '_inject_assets.php', '_clean_inline.php', '_clean_remnants.php', 'index.php', 'rdoc_db.sql'];

foreach ($files as $file) {
    if (in_array($file, $skip)) continue;

    $c = file_get_contents($file);

    // Remove Mobile Bottom Navigation HTML completely (it's between the comment and the next <!-- comment --> or </div>)
    $c = preg_replace('/<!-- Mobile Bottom Navigation -->\s*<div\s*class="fixed bottom-0 left-0 right-0 lg:hidden.*?(<!-- Add padding bottom for mobile to account for bottom nav -->|<!-- Main Content -->|<main)/s', '$1', $c);
    
    // Some files might not have the comments, let's remove the block if it starts with <div class="fixed bottom-0 left-0 right-0 lg:hidden
    // We can use a simpler approach: explode and reconstruct
    
    // Remove .nav-pill-bar CSS from inline styles
    $c = preg_replace('/\.nav-pill-bar\s*\{[^}]+\}/s', '', $c);
    $c = preg_replace('/\.nav-pill-link\s*\{[^}]+\}/s', '', $c);
    $c = preg_replace('/\.nav-pill-link\.active\s*\{[^}]+\}/s', '', $c);
    $c = preg_replace('/\.nav-pill-link:hover\s*\{[^}]+\}/s', '', $c);
    $c = preg_replace('/\.nav-corner-panel\s*\{[^}]+\}/s', '', $c);
    $c = preg_replace('/\.left-panel\s*\{[^}]+\}/s', '', $c);
    $c = preg_replace('/\.right-panel\s*\{[^}]+\}/s', '', $c);

    // Remove any remaining nav-pill-bar HTML
    $c = preg_replace('/<div class="nav-pill-bar">.*?<\/div>\s*<\/div>\s*<\/div>/s', '', $c);

    file_put_contents($file, $c);
    echo "Cleaned remnants in $file\n";
}
?>
