<?php

/**
 * @messages array of messages
 */

?>

<section>
    <h2>Messages</h2>
    <ul class="messages-wrapper">
        <?php foreach ($messages as $message) : ?>
                <li><?= $message ?></li>
        <?php endforeach; ?>
        </ul>
</section>