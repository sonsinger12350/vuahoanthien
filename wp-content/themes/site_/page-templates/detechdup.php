<?php
/*
 * Template Name: Duplicate SKUs Finder
 */

get_header(); // This includes your theme header

global $wpdb;

// Query to find duplicate SKUs
$sql = "SELECT meta_value AS sku, COUNT(*) AS count
        FROM {$wpdb->postmeta}
        WHERE meta_key = '_sku'
        GROUP BY meta_value
        HAVING count > 1";

$results = $wpdb->get_results($sql);
?>
<div class="container">
    <h1>Duplicate SKUs</h1>
    <?php if (!empty($results)): ?>
        <ul>
            <?php foreach ($results as $result): ?>
                <li>SKU: <?php echo esc_html($result->sku); ?>, Count: <?php echo esc_html($result->count); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No duplicate SKUs found.</p>
    <?php endif; ?>
</div>
<?php
get_footer(); // This includes your theme footer
?>
