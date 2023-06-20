<?php require_once('partials/header.php'); ?>

<div class="container">
    <div class="c_page">
        <p><a href="/" class="btn">Go back to main page</a></p>
        <table>
            <thead>
                <tr>
                    <th>Source code</th>
                    <th>Source name</th>
                    <th>Source amount</th>
                    <th>Target code</th>
                    <th>Target name</th>
                    <th>Target amount</th>
                    <th>date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($history_list as $history): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($history['source_code']); ?></td>
                        <td><?php echo htmlspecialchars($history['source_currency']); ?></td>
                        <td><?php echo htmlspecialchars($history['source_amount']); ?></td>
                        <td><?php echo htmlspecialchars($history['target_code']); ?></td>
                        <td><?php echo htmlspecialchars($history['target_currency']); ?></td>
                        <td><?php echo htmlspecialchars($history['target_amount']); ?></td>
                        <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($history['created_at']))); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once('partials/footer.php'); ?>
