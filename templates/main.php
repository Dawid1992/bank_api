<?php require_once('partials/header.php'); ?>

<div class="container">
    <div class="c_page">
        <p>You don't have fresh data? Please <a href="/api" class="btn">click here</a></p>
        <p>Use <a href="/calculator" class="btn">calculator</a></p>
        <table>
            <thead>
                <tr>
                    <th>Currency name</th>
                    <th>Currency code</th>
                    <th>mid rate</th>
                    <th>date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($currencies as $currency): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($currency['currency']); ?></td>
                        <td><?php echo htmlspecialchars($currency['code']); ?></td>
                        <td><?php echo htmlspecialchars($currency['mid']); ?></td>
                        <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($currency['created_at']))); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once('partials/footer.php'); ?>
