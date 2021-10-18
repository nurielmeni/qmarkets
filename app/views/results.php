<?php

/**
 * @results array of results
 */

?>

<section>
    <h2>Results</h2>
    <table>
        <thead>
            <th>
            <td>uid</td>
            <td>Full Name</td>
            <td>Email</td>
            <td>Username</td>
            <td>Weight</td>
            </th>
        </thead>
        <tbody>
            <?php foreach ($results as $result) : ?>
                <tr>
                    <td><?= $result['uis'] ?></td>
                    <td><?= $result['fullname'] ?></td>
                    <td><?= $result['mail'] ?></td>
                    <td><?= $result['username'] ?></td>
                    <td><?= $result['total_weight'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>