<div class="form-zone">
    <div class="form-body">
        <form method="POST" action="<?= URL ?>?url=login&submit=login">
            <h1>Welcome on Camagru !</h1>
            <div class="info" id="login-info">
                <?= $info ?>
            </div>
            <div class="error" id="login-error">
                <?= $err ?>
            </div>
            <input type="text" class="input-box" name="login" placeholder="Login" required>
            <input type="password" class="input-box" name="passwd" id="passwd" placeholder="Mot de passe" required>
            <button type="submit" class="btn btn-primary">Log in</button>
        </form>
        <button style="margin-bottom: 20px" onclick="toggleForm()" class="btn-blue">I've lost my password</button>
        <div class="reset">
            <input style="display: none" type="text" class="input-box" id="email" placeholder="Enter your email address" required>
            <button style="display:none" id="reset" onclick="resetPasswd()" class="btn-blue">Reset</button>
        </div>
    </div>
</div>
<script src="<?= URL ?>scripts/login.js"></script>