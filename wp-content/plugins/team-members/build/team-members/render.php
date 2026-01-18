<?php
$genres = get_terms([
    'taxonomy' => 'genre',
    'hide_empty' => true,
]);
?>

<div class="team-block" data-team-block>

    <div class="team-filters">
        <button data-genre="">All</button>

        <?php foreach ($genres as $genre): ?>
            <button data-genre="<?php echo esc_attr($genre->slug); ?>">
                <?php echo esc_html($genre->name); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <div class="team-results"></div>

</div>
