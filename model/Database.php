<?php

/**
 * Auteur : Jeremiah Steiner
 * Date: 26.04.2021
 * contient les méthodes permettant d'accèder à la database
 */

include './data/config.php';

class Database {

    // Variable de classe
    private $connector;
    
    /**
     * Connexion à la DB par PDO
     */
    public function __construct()
    {
        $dbName = $GLOBALS['MM_CONFIG']['database']['dbName'];
        $user = $GLOBALS['MM_CONFIG']['database']['username'];
        $password = $GLOBALS['MM_CONFIG']['database']['password'];
        $charset = $GLOBALS['MM_CONFIG']['database']['charset'];
        $host = $GLOBALS['MM_CONFIG']['database']['host'];
        $port = $GLOBALS['MM_CONFIG']['database']['port'];

        $this->connector = new PDO("mysql:host=$host;port=$port;dbname=$dbName;charset=$charset" , $user, $password);
    }

    /**
     * Execute une requête simple
     *
     * @param [type] $query
     * @return void
     */
    private function querySimpleExecute($query)
    {
        $req = $this->connector->query($query);

        return $req;
    }

    /**
     * Execute une requête
     *
     * @param string $query
     * @param string $binds
     * @return PDOStatement
     */
    private function queryPrepareExecute($query, $binds)
    {
        $req = $this->connector->prepare($query);

        if(isset($binds))
        {
            foreach($binds as $bind)
            {
                $req->bindValue($bind['marker'], $bind['input'], $bind['type']);
            }
        }

        $req->execute();

        return $req;
    }

    /**
     * Transforme en tableau associatif les données
     *
     * @param PDOStatement $req
     * @return PDOStatement
     */
    private function formatData($req)
    {
        return $req->fetchALL(PDO::FETCH_ASSOC);
    }

    /**
     * vider la requete
     *
     * @param PDOStatement $req
     * @return void
     */
    private function unsetData($req)
    {
        $req->closeCursor();
    }

    /**
     * ferme la connexion
     *
     * @param PDOStatement $req
     * @return void
     */
    private function closeConnection($req)
    {
        $this->connector = null; // NOTE : à checker si les deux lignes sont nécessaires => ba merci pour le module 151.... 
    }








    /**
     * compte les appartements
     *
     * @return int
     */
    public function CountAppartements()
    {
        $req = $this->queryPrepareExecute('SELECT Count(idAppartement) FROM t_appartement', null);// appeler la méthode pour executer la requète
        $appartements = $this->formatData($req);
        return $appartements[0]['Count(idAppartement)'];
    }

    /**
     * vérifie si la recette existe
     *
     * @param int $idAppartement
     * @return bool
     */
    public function AppartementExist($idAppartement)
    {
        $req = $this->queryPrepareExecute('SELECT * FROM t_appartement', null);// appeler la méthode pour executer la requète

        $appartements = $this->formatData($req);// appeler la méthode pour avoir le résultat sous forme de tableau

        foreach($appartements as $appartement)
        {
            if ($appartement["idAppartement"] == $idAppartement)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * récupère tous les recettes de la database
     *
     * @param int $start
     * @param int $length
     * @return array
     */
    public function getAllAppartements($start = 0, $length = 5)
    { 
        $req = $this->queryPrepareExecute('SELECT * FROM t_appartement LIMIT '.  $start . ', ' . $length , null);// appeler la méthode pour executer la requète

        $appartements = $this->formatData($req);// appeler la méthode pour avoir le résultat sous forme de tableau

        $this->unsetData($req);

        return $appartements;// retour tous les recettes
    }

    /**
     * récupère tous les appartements de la wishlist (dans la database)
     *
     * @param int $start
     * @param int $length
     * @return array
     */
    public function getAllWishAppartements($start = 0, $length = 5, $idUser)
    { 
        $req = $this->queryPrepareExecute('SELECT * FROM t_appartementswishlist INNER JOIN t_appartement ON t_appartement.idAppartement = t_appartementswishlist.idAppartement 
                                            WHERE t_appartementswishlist.idUser = ' . $idUser . ' LIMIT '.  $start . ', ' . $length, null);// appeler la méthode pour executer la requète

        $appartements = $this->formatData($req);// appeler la méthode pour avoir le résultat sous forme de tableau

        $this->unsetData($req);

        return $appartements;// retour tous les appartement dans la wishlist
    }

    /**
     * permet d'obtenir une recette spécifique
     *
     * @param int $id
     * @return array
     */
    public function getOneAppartement($id)
    {
        $req = $this->queryPrepareExecute('SELECT * FROM t_appartement WHERE idAppartement = ' . $id, null); // appeler la méthode pour executer la requète

        $appartements = $this->formatData($req);// appeler la méthode pour avoir le résultat sous forme de tableau

        $this->unsetData($req);

        return $appartements[0];// retour la première valeur du tableau (il ne contient qu'une recette)
    }

    /**
     * retourn la recette la plus récente
     *
     * @return array
     */
    public function getLastAppartement()
    {
        //example : SELECT ChampDate FROM table ORDER BY ChampDate DESC LIMIT 1
        $querry = 'SELECT * FROM t_appartement ORDER BY idAppartement DESC LIMIT 1';

        $req = $this->queryPrepareExecute($querry, null);

        $appartements = $this->formatData($req);

        $this->unsetData($req);

        return $appartements[0];
    }

    /**
     * Retourne la recette avec la meilleure note
     *
     * @return array
     */
    public function getBestAppartement() // TODO : modifier pour aller chercher le rating pour calculer la note !
    {
        $querry = 'SELECT * FROM t_appartement ORDER BY appRate DESC LIMIT 1';

        $req = $this->queryPrepareExecute($querry, null);

        $appartements = $this->formatData($req);

        $this->unsetData($req);

        return $appartements[0];
    }

    public function getCheapestAppartement()
    {
        $querry = 'SELECT * FROM t_appartement ORDER BY appPrix LIMIT 1';

        $req = $this->queryPrepareExecute($querry, null);

        $appartements = $this->formatData($req);

        $this->unsetData($req);

        return $appartements[0];
    }

    /**
     * ajoute une recette a la base de donnée
     *
     * @param array $appartement
     * @return void
     */
    public function insertAppartement($appartement)
    {
        $values = array(
            1 => array(
                'marker' => ':appName',
                'input' => $appartement["appName"],
                'type' => PDO::PARAM_STR
            ),
            2 => array(
                'marker' => ':appDescription',
                'input' => $appartement["appDescription"],
                'type' => PDO::PARAM_STR
            ),
            3 => array(
                'marker' => ':appCategory',
                'input' => $appartement["appCategory"],
                'type' => PDO::PARAM_STR
            ),
            4 => array(
                'marker' => ':appImage',
                'input' => 'defaultAppartementPicture.jpg',
                'type' => PDO::PARAM_STR
            ),
            5 => array(
                'marker' => ':appSurface',
                'input' => $appartement["appSurface"],
                'type' => PDO::PARAM_INT
            ),
            6 => array(
                'marker' => ':appPrix',
                'input' => $appartement["appPrix"],
                'type' => PDO::PARAM_INT
            ),
            7 => array(
                'marker' => ':appDate',
                'input' => $appartement["appDate"],
                'type' => PDO::PARAM_STR
            ),
            8 => array(
                'marker' => ':appRate',
                'input' => $appartement["appRate"],
                'type' => PDO::PARAM_STR
            ),
            9 => array(
                'marker' => ':idUser',
                'input' => (int)$appartement["idUser"],
                'type' => PDO::PARAM_INT
            )
        );

        $query =   'INSERT INTO t_appartement (appName, appCategory, appDescription, appImage, appSurface, appPrix, appDate, appRate, idUser)
                    VALUES (:appName, :appCategory, :appDescription, :appImage, :appSurface, :appPrix, :appDate, :appRate, :idUser)';

        $req = $this->queryPrepareExecute($query, $values);

        $this->unsetData($req);
    }

    /**
     * permet d'obtenir la date et l'heure du moment
     *
     * @return string
     */
    public function getDate()
    {
        $query = 'SELECT DATE_FORMAT(now(), "%Y-%m-%d-%H-%i-%s") AS currentTime';
        $req = $this->queryPrepareExecute($query, null);
        $date = $this->formatData($req);
        return $date[0];
    }

    /**
     * permet d'obtenir les recette liées a un utilisateur
     *
     * @param int $userId
     * @return array
     */
    public function getAppartementsByUserId($userId)
    {
        $req = $this->queryPrepareExecute('SELECT * FROM t_appartement WHERE idUser = '. $userId , null);// appeler la méthode pour executer la requète

        $appartements = $this->formatData($req);// appeler la méthode pour avoir le résultat sous forme de tableau

        $this->unsetData($req);

        return $appartements;// retour tous les recettes
    }

    /**
     * permet de modifier une recette
     *
     * @param array $appartement
     * @return void
     */
    public function editAppartement($appartement)
    {
        $values = array(
            1 => array(
                'marker' => ':appName',
                'input' => $appartement["appName"],
                'type' => PDO::PARAM_STR
            ),
            2 => array(
                'marker' => ':appDescription',
                'input' => $appartement["appDescription"],
                'type' => PDO::PARAM_STR
            ),
            3 => array(
                'marker' => ':appCategory',
                'input' => $appartement["appCategory"],
                'type' => PDO::PARAM_STR
            ),
            4 => array(
                'marker' => ':appImage',
                'input' => $appartement["appImage"],
                'type' => PDO::PARAM_STR
            ),
            5 => array(
                'marker' => ':appSurface',
                'input' => $appartement["appSurface"],
                'type' => PDO::PARAM_INT
            ),
            6 => array(
                'marker' => ':appPrix',
                'input' => $appartement["appPrix"],
                'type' => PDO::PARAM_INT
            ),
            7 => array(
                'marker' => ':appDate',
                'input' => $appartement["appDate"],
                'type' => PDO::PARAM_STR
            ),
            8 => array(
                'marker' => ':appRate',
                'input' => $appartement["appRate"],
                'type' => PDO::PARAM_INT
            ),
            9 => array(
                'marker' => ':idUser',
                'input' => $appartement["idUser"],
                'type' => PDO::PARAM_INT
            )
        );

        $query =   'UPDATE t_appartement SET 
                    appName = :appName, appDescription = :appDescription, appCategory = :appCategory,
                    appImage = :appImage, appSurface = :appSurface, appDate = :appDate, appPrix = :appPrix,
                    appRate = :appRate, idUser = :idUser
                    WHERE idAppartement = ' . $appartement["idAppartement"];

        $req = $this->queryPrepareExecute($query, $values);

        $this->unsetData($req);
    }

    /**
     * supprime une recette
     *
     * @param int $idappartement
     * @return void
     */
    public function deleteAppartement($idAppartement)
    {
        $values = array(
            1 => array(
                'marker' => ':idAppartement',
                'input' => $idAppartement,
                'type' => PDO::PARAM_STR
            )
        );

        $query = 'DELETE FROM t_appartement WHERE t_appartement.idAppartement = :idAppartement';

        $req = $this->queryPrepareExecute($query, $values);

        $this->unsetData($req);
    }

    /**
     * permet d'obtenir toutes les notations d'une recette
     *
     * @param int $idappartement
     * @return array
     */
    public function getAllRatingsForThisAppartement($idAppartement)
    {
        $req = $this->queryPrepareExecute('SELECT * FROM t_rating LEFT JOIN t_user ON t_rating.idUser = t_user.idUser WHERE t_rating.idAppartement = ' . $idAppartement, null);// appeler la méthode pour executer la requète

        $ratings = $this->formatData($req);// appeler la méthode pour avoir le résultat sous forme de tableau

        $this->unsetData($req);

        return $ratings;// retour toute les évaluations de la recette
    }

    /**
     * permet de savoir si un utilisateur a déjà noté la recette
     *
     * @param int $idUser
     * @param int $idAppartement
     * @return bool
     */
    public function userAlreadyRateThisAppartement($idUser, $idAppartement)
    {
        $ratings = $this->getAllRatingsForThisappartement($idAppartement);

        foreach($ratings as $rating)
        {
            if ($rating["idUser"] == $idUser)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * ajoute une évaluation
     *
     * @param int $idUser
     * @param int $idAppartement
     * @return void
     */
    public function insertRating($idUser, $idAppartement)
    {
        $query = "INSERT INTO t_rating (idUser, idAppartement) VALUES (:idUser, :idAppartement)";

        //Binds des valeurs
        $values = array(
            0 => array(
                'marker' => ':idUser',
                'input' => $idUser,
                'type' => PDO::PARAM_STR
            ),
            1 => array(
                'marker' => ':idAppartement',
                'input' => $idAppartement,
                'type' => PDO::PARAM_STR
            )
        );

        $req = $this->queryPrepareExecute($query, $values);
        $this->unsetData($req);
    }

    /**
     * efface une évaluation
     *
     * @param int $idUser
     * @param int $idAppartement
     * @return void
     */
    public function removeRating($idUser, $idAppartement)
    {
        $query = $query = 'DELETE FROM t_rating WHERE t_rating.idUser = :idUser AND t_rating.idAppartement = :idAppartement';

        //Binds des valeurs
        $values = array(
            0 => array(
                'marker' => ':idUser',
                'input' => $idUser,
                'type' => PDO::PARAM_INT
            ),
            1 => array(
                'marker' => ':idAppartement',
                'input' => $idAppartement,
                'type' => PDO::PARAM_INT
            )
        );

        $req = $this->queryPrepareExecute($query, $values);
        $this->unsetData($req);
    }

    /**
     * vérifie si la recette existe
     *
     * @param int $idUser
     * @param int $idAppartement
     * @return bool
     */
    public function isRateExist($idUser, $idAppartement)
    {
        $req = $this->queryPrepareExecute('SELECT * FROM t_rating WHERE t_rating.idUser = ' . $idUser . ' AND t_rating.idAppartement = ' . $idAppartement, null);// appeler la méthode pour executer la requète

        $appartements = $this->formatData($req);// appeler la méthode pour avoir le résultat sous forme de tableau
        return sizeof($appartements) == 0 ? false : true;
    }


    public function insertWish($idUser, $idAppartement)
    {
        $query = "INSERT INTO t_appartementswishlist (idUser, idAppartement, appVisited, appRated) VALUES (:idUser, :idAppartement, :appVisited, :appRated)";

        //Binds des valeurs
        $values = array(
            0 => array(
                'marker' => ':idUser',
                'input' => $idUser,
                'type' => PDO::PARAM_INT
            ),
            1 => array(
                'marker' => ':idAppartement',
                'input' => $idAppartement,
                'type' => PDO::PARAM_INT
            ),
            2 => array(
                'marker' => ':appVisited',
                'input' => 0,
                'type' => PDO::PARAM_BOOL
            ),
            3 => array(
                'marker' => ':appRated',
                'input' => $this->isRateExist($idUser, $idAppartement),
                'type' => PDO::PARAM_BOOL
            )
        );

        $req = $this->queryPrepareExecute($query, $values);
        $this->unsetData($req);
    }

    public function updateWish($idUser, $idAppartement, $appVisited, $appRated)
    {
        $query = "UPDATE t_appartementswishlist SET idUser = :idUser, idAppartement = :idAppartement, appVisited = :appVisited, appRated = :appRated
                  WHERE idAppartement = :idAppartement AND idUser = :idUser";

        //Binds des valeurs
        $values = array(
            0 => array(
                'marker' => ':idUser',
                'input' => $idUser,
                'type' => PDO::PARAM_INT
            ),
            1 => array(
                'marker' => ':idAppartement',
                'input' => $idAppartement,
                'type' => PDO::PARAM_INT
            ),
            2 => array(
                'marker' => ':appVisited',
                'input' => $appVisited,
                'type' => PDO::PARAM_INT
            ),
            3 => array(
                'marker' => ':appRated',
                'input' => $appRated,
                'type' => PDO::PARAM_INT
            )
        );

        $req = $this->queryPrepareExecute($query, $values);
        $this->unsetData($req);
    }

    public function removeWish($idUser, $idAppartement)
    {
        $query = $query = 'DELETE FROM t_appartementswishlist WHERE t_appartementswishlist.idUser = :idUser AND t_appartementswishlist.idAppartement = :idAppartement';

        //Binds des valeurs
        $values = array(
            0 => array(
                'marker' => ':idUser',
                'input' => $idUser,
                'type' => PDO::PARAM_INT
            ),
            1 => array(
                'marker' => ':idAppartement',
                'input' => $idAppartement,
                'type' => PDO::PARAM_INT
            )
        );

        $req = $this->queryPrepareExecute($query, $values);
        $this->unsetData($req);
    }

    public function wishExtist($idUser, $idAppartement)
    {
        $req = $this->queryPrepareExecute('SELECT * FROM t_appartementswishlist WHERE t_appartementswishlist.idUser = ' . $idUser . ' AND t_appartementswishlist.idAppartement = ' . $idAppartement, null);// appeler la méthode pour executer la requète

        $wishappartement = $this->formatData($req);// appeler la méthode pour avoir le résultat sous forme de tableau
        return sizeof($wishappartement) == 0 ? false : true;
    }

    public function GetVisitedStateForWish($idUser, $idAppartement)
    {
        $values = array(
            1 => array(
                'marker' => ':idUser',
                'input' => $idUser,
                'type' => PDO::PARAM_INT
            ),
            2 => array(
                'marker' => ':idAppartement',
                'input' => $idAppartement,
                'type' => PDO::PARAM_INT
            )
        );

        $query = 'SELECT appVisited FROM t_appartementswishlist WHERE idUser = :idUser AND idAppartement = :idAppartement';
        $req = $this->queryPrepareExecute($query, $values);
        $appVisited = $this->formatData($req);
        $this->unsetData($req);

        return $appVisited[0]["appVisited"];
    }

    public function updateAppartementRate($idAppartement)
    {
        $newappRate = $this->GetAppartementRate($idAppartement);


        $query = "UPDATE t_appartement SET appRate = :newappRate
                  WHERE idAppartement = :idAppartement";

        //Binds des valeurs
        $values = array(
            1 => array(
                'marker' => ':idAppartement',
                'input' => $idAppartement,
                'type' => PDO::PARAM_INT
            ),
            2 => array(
                'marker' => ':newappRate',
                'input' => $newappRate,
                'type' => PDO::PARAM_INT
            )

        );

        $req = $this->queryPrepareExecute($query, $values);
        $this->unsetData($req);
    }

    public function GetAppartementRate($idAppartement)
    {
        $values = array(
            1 => array(
                'marker' => ':idAppartement',
                'input' => $idAppartement,
                'type' => PDO::PARAM_INT
            )
        );

        $query = 'SELECT COUNT(idUser) AS "tmp" FROM t_rating WHERE idAppartement = :idAppartement';
        $req = $this->queryPrepareExecute($query, $values);
        $appRates = $this->formatData($req);
        $this->unsetData($req);

        return $appRates[0]["tmp"];
    }

    /**
     * permet de modifier une évaluation
     *
     * @param int $idUser
     * @param int $idAppartement
     * @param int $ratGrade
     * @param string $ratComment
     * @return void
     */
    public function editRating($idUser, $idAppartement, $ratGrade, $ratComment)
    {
        $idRating = -1;
        $values = array();
        $query = "";

        if ($ratGrade < 1)
        {
            $idRating = $this->getRatingIdByAppartementIdAndUserId($idAppartement, $idUser);

            $values = array(
                1 => array(
                    'marker' => ':idRating',
                    'input' => $idRating,
                    'type' => PDO::PARAM_INT
                )
            );
            
            $query = 'DELETE FROM t_rating WHERE t_rating.idRating = :idRating';
        }
        else
        {
            $values = array(
                1 => array(
                    'marker' => ':idUser',
                    'input' => $idUser,
                    'type' => PDO::PARAM_INT
                ),
                2 => array(
                    'marker' => ':idAppartement',
                    'input' => $idAppartement,
                    'type' => PDO::PARAM_INT
                ),
                3 => array(
                    'marker' => ':ratGrade',
                    'input' => $ratGrade,
                    'type' => PDO::PARAM_INT
                ),
                4 => array(
                    'marker' => ':ratComment',
                    'input' => $ratComment,
                    'type' => PDO::PARAM_STR
                )
            );
            
            $query =   'UPDATE t_rating SET 
                idUser = :idUser, idAppartement = :idAppartement, ratGrade = :ratGrade, ratComment = :ratComment
                WHERE idUser = :idUser AND idAppartement = :idAppartement';
        }
        
        $req = $this->queryPrepareExecute($query, $values);

        $this->unsetData($req);
    }

    /**
     * Undocumented function
     *
     * @param int $idAppartement
     * @param int $idUser
     * @return int
     */
    public function getRatingIdByAppartementIdAndUserId($idAppartement, $idUser)
    {
        $values = array(
            1 => array(
                'marker' => ':idUser',
                'input' => $idUser,
                'type' => PDO::PARAM_INT
            ),
            2 => array(
                'marker' => ':idAppartement',
                'input' => $idAppartement,
                'type' => PDO::PARAM_INT
            )
        );

        $query = 'SELECT idRating FROM t_rating WHERE idUser = :idUser AND idAppartement = :idAppartement';
        $req = $this->queryPrepareExecute($query, $values);
        $idRating = $this->formatData($req);
        $this->unsetData($req);

        return $idRating[0]["idRating"];
    }







    /**
     * Récupère tous les noms d'utilisateur
     * @return array
     */
    public function getAllUsernames(){

        $query = "SELECT usePseudo FROM t_user";
        $req = $this->queryPrepareExecute($query, null);
        $usernames = $this->formatData($req);
        $this->unsetData($req);
        return $usernames;
    }

    /**
     * Récupère les données d'un utilisateur par l'username
     * @param $username
     * @return array
     */
    public function getOneUser($username){

        $query = "SELECT * FROM t_user WHERE usePseudo = :username";
        
        $values = array(
            0 => array(
                'marker' => ':username',
                'input' => $username,
                'type' => PDO::PARAM_STR
            )
        );

        $req = $this->queryPrepareExecute($query, $values);
        $user = $this->formatData($req);

        $this->unsetData($req);

        return $user;
    }

    /**
     * Récupère les données d'un utilisateur par son id
     * @param $username
     * @return array
     */
    public function getOneUserById($userId){

        $query = "SELECT * FROM t_user WHERE idUser = '$userId'";
        $req = $this->queryPrepareExecute($query, null);
        $userArray = $this->formatData($req);
        $user = $userArray[0];
        $this->unsetData($req);
        return $user;
    }

    /**
     * Ajoute un utilisateur
     * @param $username
     */
    public function insertUser($username, $firstName, $lastName, $password){

        $query = "INSERT INTO t_user (usePseudo, useFirstName, useName, usePassword) VALUES (:username, :firstName, :lastName, :setPassword)";

        //Binds des valeurs
        $values = array(
            0 => array(
                'marker' => ':username',
                'input' => $username,
                'type' => PDO::PARAM_STR
            ),
            1 => array(
                'marker' => ':setPassword',
                'input' => $password,
                'type' => PDO::PARAM_STR
            ),
            2 => array(
                'marker' => ':firstName',
                'input' => $firstName,
                'type' => PDO::PARAM_STR
            ),
            3 => array(
                'marker' => ':lastName',
                'input' => $lastName,
                'type' => PDO::PARAM_STR
            )
        );

        $req = $this->queryPrepareExecute($query, $values);
        $this->unsetData($req);
    }

    /**
     * vérifie dans la database si l'id utilisateur existe
     *
     * @param int $idUser
     * @return bool
     */
    public function userExist($idUser)
    {
        $req = $this->queryPrepareExecute('SELECT * FROM t_user', null);// appeler la méthode pour executer la requète

        $users = $this->formatData($req);// appeler la méthode pour avoir le résultat sous forme de tableau

        foreach($users as $user)
        {
            if ($user["idUser"] == $idUser)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * permet de modifier un utilisateur
     *
     * @param array $user
     * @return void
     */
    public function updateUser($user){

        if (isset($user["useImage"]))
        {
            $values = array(
                1 => array(
                    'marker' => ':useImage',
                    'input' => $user["useImage"],
                    'type' => PDO::PARAM_STR
                ),
                2 => array(
                    'marker' => ':id',
                    'input' => $user["idUser"],
                    'type' => PDO::PARAM_INT
                )
            );

            $query = 'UPDATE t_user SET useImage = :useImage WHERE idUser = :id';
        }
        else if (isset($user["usePassword"]))
        {
            $values = array(
                1 => array(
                    'marker' => ':usePassword',
                    'input' => $user["usePassword"],
                    'type' => PDO::PARAM_STR
                ),
                2 => array(
                    'marker' => ':id',
                    'input' => $user["idUser"],
                    'type' => PDO::PARAM_INT
                )
            );

            $query = 'UPDATE t_user SET usePassword = :usePassword WHERE idUser = :id';
        }
        else
        {
            $values = array(
                1 => array(
                    'marker' => ':usePseudo',
                    'input' => $user["usePseudo"],
                    'type' => PDO::PARAM_STR
                ),
                2 => array(
                    'marker' => ':useFirstname',
                    'input' => $user["useFirstname"],
                    'type' => PDO::PARAM_STR
                ),
                3 => array(
                    'marker' => ':useName',
                    'input' => $user["useName"],
                    'type' => PDO::PARAM_STR
                ),
                4 => array(
                    'marker' => ':useMail',
                    'input' => $user["useMail"],
                    'type' => PDO::PARAM_STR
                ),
                5 => array(
                    'marker' => ':useTelephone',
                    'input' => $user["useTelephone"],
                    'type' => PDO::PARAM_STR
                ),
                6 => array(
                    'marker' => ':id',
                    'input' => $user["idUser"],
                    'type' => PDO::PARAM_INT
                )
            ); 

            $query =   'UPDATE t_user SET 
                        usePseudo = :usePseudo, useFirstname = :useFirstname, useName = :useName, useMail = :useMail, useTelephone = :useTelephone
                        WHERE idUser = :id';
        }

        $req = $this->queryPrepareExecute($query, $values);

        $this->unsetData($req);
    }
}
?>