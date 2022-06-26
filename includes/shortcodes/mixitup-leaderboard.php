<?php

// [mixitup-leaderboard]
function anthony_shortcode_mixitup_leaderboard() {

    $leaderboard = get_field('mixitup_leaderboard', 'option');
    if(!$leaderboard) {
        return "<div class='mixitup error'><p>Aucun classement pour le moment...</p></div>";
    }

    $leaderboard = @file_get_contents( $leaderboard );
    if(!$leaderboard) {
        return "<div class='mixitup error'><p>Impossible d'accéder au classement pour le moment...</p></div>";
    }

    $leaderboardSort = function($a, $b) {

        if($a['OnlineViewingMinutes'] < $b['OnlineViewingMinutes']) return 1;
        else if($a['OnlineViewingMinutes'] > $b['OnlineViewingMinutes']) return -1;

        if($a['TwitchUsername'] > $b['TwitchUsername']) return 1;
        else if($a['TwitchUsername'] < $b['TwitchUsername']) return -1;

        else return 0;

    };

    $leaderboard = json_decode($leaderboard, true);
    $data = $leaderboard['data'];
    usort($data, $leaderboardSort);
    $data = array_slice($data, 0, 10);
    $leaderboard['data'] = $data;

    ob_start();

    ?>

    <div class="mixitup">
        <div class="leaderboard">
            <?php foreach($leaderboard['data'] as $i => $user): ?>
                <div>
                    <div class="user">
                        <span class="top"><?= $i+1 ?></span>
                        <img src="<?= $user['TwitchAvatarLink'] ?>" alt="<?= $user['TwitchUsername'] ?>">
                        <a href="https://twitch.tv/<?= $user['TwitchUsername'] ?>" target="_blank"><?= $user['TwitchDisplayName'] ?></a>
                    </div>
                    <div class="time">
                        <?php if(round($user['OnlineViewingMinutes']/60) <= 0): ?>
                            &nbsp;
                        <?php else: ?>
                            <?= round($user['OnlineViewingMinutes']/60) ?>&nbsp;h
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="updated-at">Classement mis à jour <?= wp_date ("l j F Y \à H:i", strtotime($leaderboard['updatedAt'])) ?>.</div>
    </div>

    <?php

    $html = ob_get_contents();
    ob_end_clean();

    return $html;
}
add_shortcode( 'mixitup-leaderboard', 'anthony_shortcode_mixitup_leaderboard' );
