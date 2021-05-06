<h2>Register</h2>
<form method="post" action="index.php?controller=user&action=register">
    <div class="form-row" style="height: fit-content">
        <div class="form-group col-md-4 offset-md-2 mb-3 mr-3">
            <label for="username">Nom d'utilisateur</label>
            <input class="form-control" type="text" name="username">
            <?php
                if (isset($errorUsername) && $errorUsername)
                {
                    echo '<span class="logMSG">username non valide</span>';
                }
            ?>
        </div>
    </div>

    <div class="form-row" style="height: fit-content">
        <div class="form-group col-md-4 offset-md-2 mb-3 mr-3">
            <label for="firstName">Prénom</label>
            <input class="form-control" type="text" name="firstName">
        </div>

        <div class="form-group col-md-4 mb-3 mr-3">
            <label for="lastName">Nom</label>
            <input class="form-control" type="text" name="lastName">
        </div>
    </div>

    <div class="form-row" style="height: fit-content">
        <div class="form-group col-md-4 offset-md-2 mb-3 mr-3">
            <label for="password1">Mot de Passe</label>
            <input class="form-control" type="password" name="password1">
        </div>
        
        <div class="form-group col-md-4 mb-3 mr-3">
            <label for="password2">Répétez le Mot de Passe</label>
            <input class="form-control" type="password" name="password2">
            <?php
                if (isset($errorPassword) && $errorPassword)
                {
                    echo '<span class="logMSG">mots de pass différents ou non conformes</span>';
                }
            ?>
        </div>
    </div>

    <div class="form-row" style="height: fit-content">
        <div class="form-group col-md-4 offset-md-2 mb-3 mt-4 mr-3">
            <button class="form-control btn btn-success" type="submit">S&apos;inscrire</button>
            
            <?php
                if (isset($errorRegister) && $errorRegister)
                {
                    echo '<span class="logMSG">Information(s) d&apos;enregistrement erronée(s)</span>';
                }
            ?>
        </div>
    </div>
</form>