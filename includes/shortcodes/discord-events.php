<?php

// [discord-events]
function anthony_shortcode_discord_events() {

    $sid = 'anthony-discord-events';
    $t = get_transient( $sid );

    if($t) {
        return $t;
    }

    $token = get_field('discord_bot_token', 'option');
    $guild = get_field('discord_guild_id', 'option');

    if(!$token || !$guild) {
        return "<div class='discord-events error'><p>Le token de bot et l'ID du serveur sont requis.</p></div>";
    }

    $ctx = stream_context_create([
        'http' => [
            'header' => "Authorization: Bot $token\r\n"
        ]
    ]);

    $events = @file_get_contents("https://discord.com/api/v8/guilds/$guild/scheduled-events", false, $ctx);

    if(!$events) {
        $html = "<div class='discord-events error'><p>Aucun événement programmé pour le moment...</p></div>";
        set_transient($sid, $html, MINUTE_IN_SECONDS * 15);
        return $html;
    }

    ob_start();

    echo "<div class='discord-events'>";

    $events = json_decode($events);
    foreach($events as $e) {
        ?>

            <div class="event">
                <div class="thumbnail">
                    <img src="https://cdn.discordapp.com/guild-events/<?= $e->id ?>/<?= $e->image ?>.png?size=1024" alt="">
                </div>
                <div class="title"><?= $e->name ?></div>
                <div class="dates"><?= wp_date('j F', strtotime($e->scheduled_start_time)) ?> &bull; <?= wp_date('H:i', strtotime($e->scheduled_start_time)) ?> &rarr; <?= wp_date('H:i', strtotime($e->scheduled_end_time)) ?></div>
                <div class="description"><?= $e->description ?></div>
            </div>

        <?php
    }

    echo "</div><!-- .discord-events -->";

    $html = ob_get_contents();
    ob_end_clean();

    set_transient($sid, $html, MINUTE_IN_SECONDS * 15);
    return $html;

}

add_shortcode( 'discord-events', 'anthony_shortcode_discord_events' );
