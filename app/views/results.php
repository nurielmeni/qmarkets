<?php

/**
 * @results array of results
 */

?>
<style>
    table {
        border-collapse: collapse;
    }
    th,
    td {
        text-align: left;
        padding: 5px 10px;
        border: 1px solid #ccc;
    }
</style>
<section>
    <h2>Results</h2>
    <table>
        <thead>
            <tr>
                <th>uid</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Weight</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $result) : ?>
                <tr>
                    <td><?= $result['uid'] ?></td>
                    <td><?= $result['fullname'] ?></td>
                    <td><?= $result['mail'] ?></td>
                    <td><?= $result['username'] ?></td>
                    <td><?= $result['total_weight'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>