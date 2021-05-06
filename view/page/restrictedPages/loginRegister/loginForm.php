<h2>Login</h2>
<form method="post" action="index.php?controller=user&action=login">
    <div class="form-row" style="height: fit-content">
        <div class="form-group col-md-4 mb-3">
            <label for="username">Nom d'utilisateur</label>
            <input class="form-control" type="text" name="username">
        </div>

        <div class="form-group col-md-4 mb-3">
            <label for="password">Mot de Passe</label>
            <input class="form-control" type="password" name="password">
        </div>
    </div>

    <div class="form-row" style="height: fit-content">
        <div class="form-group col-md-4 mb-3">
            <button class="form-control btn btn-success" type="submit" name="submit">Connexion</button>
        </div>
    </div>

    <div class="form-row" style="height: fit-content">
        <div class="form-group col-md-4 mb-3">
            <?php
            if (isset($_SESSION['errorLogin']))
            {
                if ($_SESSION['errorLogin'] == true)
                {
                    echo '<span class="logMSG">Information(s) de login erronée(s)</span>';
                    $_SESSION['errorLogin'] = false;
                }
            }
            ?>
        </div>
    </div>
</form>