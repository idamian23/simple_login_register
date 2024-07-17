<?php include APPROOT . '/views/inc/header.php' ?>
    <div class="container">
        <div class="row">
            <form action="<?= URLROOT ?>/users/register" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="user[username]" value="<?php if(!$data['errors']['username']) echo $data['user']['username'] ?>">
                    <span><?= $data['errors']['username'] ?></span>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="user[email]" value="<?php if(!$data['errors']['email']) echo $data['user']['email'] ?>">
                    <span><?= $data['errors']['email'] ?></span>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="user[password]" required>
                    <span><?= $data['errors']['password'] ?></span>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="user[confirm_password]" required>
                    <span><?= $data['errors']['confirm_password'] ?></span>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <?php foreach ($data[1] as $key => $value): ?>
            <div><?= htmlspecialchars($key) . ": " . htmlspecialchars($value) ?></div>
        <?php endforeach; ?>
    </div>

<?php include APPROOT . '/views/inc/footer.php' ?>