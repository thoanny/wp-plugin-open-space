<?php

// [jeu-decouvre-vod]
function anthony_jeu_decouvre_vod() {
    $vod = get_field('jeu_decouvre_vod', get_the_ID());

    if(!$vod) {
        return false;
    }

    $vodSort = function($a, $b) {
        if($a['published_at'] < $b['published_at']) return 1;
        else if($a['published_at'] > $b['published_at']) return -1;
        else return 0;
    };

    usort($vod, $vodSort);

    ob_start();

    echo "<div class=\"jeu-decouvre-vod\">";

    foreach($vod as $g) {

        ?>

            <div class="game">
                <a href="https://youtu.be/<?= $g['youtube_id'] ?>" target="_blank" rel="nofollow">

                    <span class="fa-stack fa-2x">
                        <i class="fas fa-circle fa-stack-2x"></i>
                        <i class="fas fa-play fa-stack-1x fa-inverse"></i>
                    </span>

                    <img src="https://i.ytimg.com/vi/<?= $g['youtube_id'] ?>/maxresdefault.jpg" alt="">
                </a>
                <a href="<?= $g['steam_link'] ?>" target="_blank" rel="nofollow" class="elementor-button-link elementor-button elementor-size-xs" role="button">
                    <span class="elementor-button-content-wrapper">
                        <span class="elementor-button-icon elementor-align-icon-left">
                            <i aria-hidden="true" class="fab fa-steam"></i>
                        </span>
                        <span class="elementor-button-text">Voir sur Steam</span>
                    </span>
                </a>
            </div>

        <?php

    }

    echo "</div>";

    $html = ob_get_contents();
    ob_end_clean();

    return $html;

}
add_shortcode( 'jeu-decouvre-vod', 'anthony_jeu_decouvre_vod' );
