<?php
$files = glob('*.php');
$skip = ['_extract.php', '_update_index.php', '_update_nav.php', '_clean_pages.php', '_extract_nav_assets.php', '_inject_assets.php', '_clean_inline.php', 'index.php', 'rdoc_db.sql'];

foreach ($files as $file) {
    if (in_array($file, $skip)) continue;

    $c = file_get_contents($file);

    // Remove inline <style> that contains .dropdown-enter
    $c = preg_replace('/<style>\s*@keyframes fadeSlideDown.*?<\/style>/s', '', $c);
    
    // Remove inline <style> that contains .site-footer
    $c = preg_replace('/<style>\s*\.site-footer.*?<\/style>/s', '', $c);

    // Remove inline <script> that contains menu toggle logic
    $c = preg_replace('/<script>\s*\/\/\s*========== MENU TOGGLE ==========.*?<\/script>/s', '', $c);
    
    // Some pages might have a different format for the script, let's catch the exact menu logic
    $c = preg_replace('/<script>\s*const menuBtn = document\.getElementById\(\'menu-toggle\'\);.*?<\/script>/s', '', $c);

    // Some pages might have the menu script combined with hero text animations (like produit-aihrus.php)
    // In produit-aihrus.php, the menu toggle and hero animations are in the same <script> block.
    // Let's remove the menu toggle part specifically.
    $c = preg_replace('/\/\/\s*========== MENU TOGGLE ==========.*?(\/\/\s*========== HERO TEXT ANIMATIONS ==========)/s', '$1', $c);
    
    // Check if the script block is empty after removing menu toggle
    $c = preg_replace('/<script>\s*<\/script>/s', '', $c);

    file_put_contents($file, $c);
    echo "Cleaned inline styles/scripts in $file\n";
}
?>
