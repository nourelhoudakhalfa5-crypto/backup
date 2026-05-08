<?php
// Extract CSS from index.php
$content = file_get_contents('index.php');

// Extract all <style> blocks
preg_match_all('/<style>(.*?)<\/style>/s', $content, $matches);
$css = implode("\n\n", $matches[1]);
file_put_contents('assets/css/index.css', trim($css));
echo "CSS extracted: " . strlen($css) . " bytes\n";

// Extract the main inline <script> block (not src= ones)
// The inline script is between <script> and </script> tags that don't have src=
preg_match_all('/<script>([^<]*(?:<(?!\/script>)[^<]*)*)<\/script>/s', $content, $jsMatches);

// The big inline script is the largest one - find it
$biggestJs = '';
foreach ($jsMatches[1] as $js) {
    $trimmed = trim($js);
    // Skip tiny/config scripts and tailwind config
    if (strlen($trimmed) > 1000 && strpos($trimmed, 'tailwind.config') === false) {
        $biggestJs = $trimmed;
    }
}

if ($biggestJs) {
    file_put_contents('assets/js/index.js', $biggestJs);
    echo "JS extracted: " . strlen($biggestJs) . " bytes\n";
} else {
    echo "No large JS block found\n";
}
?>
