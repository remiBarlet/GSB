<?php

/**
 * Acces à la base de données dédié à l'application android
 * 
 * @category PPE
 * @author   Remi Barlet <rbarlet@protonmail.com>
 */

 
/**
 * Etablit une connexion à la base de données
 * 
 * @return un pdo object
 */
function connexionPDO() 
{
    $login='userGsb';
    $mdp= 'L2100fm$';
    $bd='gsb_frais';
    $serveur='localhost';
    try {
        $conn = new PDO("mysql:host=$serveur;dbname=$bd", $login, $mdp);
        return $conn;
    } catch (PDOException $e) {
        print "Erreur de connexion PDO ";
        die();
    }
}

/**
 * A la reception d'un message d'authentification, 
 * Si le login et le mot de passe reçu correspondent à un visiteur enregistré
 * Envoie une chaine au format JSON contenant le login et le mot de passe
 * Sinon, envoie la chaine "echec"
 * 
 * @param $cnx
 * 
 * @return null
 */
function authentification($cnx, $idVisiteur, $userAttempt, $pwdAttempt)
{
    // traitement de la chaine de caractères reçue pour utilisation
    print $idVisiteur . '%';
    //requete
    $req = $cnx->prepare(
        'SELECT * '
        . 'FROM `visiteur` '
        . 'WHERE `visiteur`.`login` = :unLogin '
        . 'AND `visiteur`.`mdp` = :unMdp;'
    );
    $req->bindParam(':unLogin', $userAttempt, PDO::PARAM_STR);
    $req->bindParam(':unMdp', $pwdAttempt, PDO::PARAM_STR);
    $req->execute();
    $result = $req->fetch(PDO::FETCH_ASSOC);
    if (is_array($result)) {
        print(json_encode($result));
    } else {
        print "echec";
    }
}

/** 
 * Recupere la quantité de frais forfait correspondant à la catégorie en paramètre
 * Envoie cette quantité au format JSON
 * 
 * @param $cnx        la connexion à la base de données
 * @param $idVisiteur l'identifiant du visiteur
 * @param $mois       le mois concerné
 * @param $unIdFrais  l'id du frais forfait dont on recherche la quantité
 
 * @return null
 */
function recupQte($cnx, $idVisiteur, $mois, $unIdFrais) 
{
    //requete récupérant les kms pour cette combinaison idVisiteur/mois
    $req = $cnx->prepare(
        'SELECT `quantite` '
        . 'FROM `lignefraisforfait` '
        . 'WHERE `idvisiteur` = :unLogin '
        . 'AND `mois` = :unMois '
        . 'AND `idfraisforfait` = :unIdFrais;'
    );
    $req->bindParam(':unLogin', $idVisiteur, PDO::PARAM_STR);
    $req->bindParam(':unMois', $mois, PDO::PARAM_INT);
    $req->bindParam(':unIdFrais', $unIdFrais, PDO::PARAM_STR);        
    $req->execute();
    $result = $req->fetch(PDO::FETCH_ASSOC);
    print(json_encode($result));
}

/**
 * Met à jour la quantité d'un Frais forfait pour un mois donné
 * La fiche doit être en mode 'saisie' (non clotûrée)
 * 
 * @param $cnx        la connexion à la base de données
 * @param $idVisiteur l'identifiant du visiteur
 * @param $mois       le mois concerné
 * @param $unIdFrais  l'identifiant du frais à actualiser
 * @param $qte        la valeur à enregistrer
 * 
 * @return null
 */
function setQte($cnx, $idVisiteur, $mois, $unIdFrais, $qte) 
{
    //ETAPE 1
    //verification de l'existence d'une fiche disponible pour la saisie
    //première requête: recherche d'une fiche en saisie
    $req1A = $cnx->prepare(
        'SELECT * '
        . 'FROM `fichefrais` '
        . 'WHERE `idVisiteur` = :unIdVisiteur '
        . 'AND `mois` = :unMois '
        . "AND `idetat` IN ('CL', 'VA', 'RB');"
    );
    $req1A->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
    $req1A->bindParam(':unMois', $mois, PDO::PARAM_INT); 
    $req1A->execute();
    $result1A = $req1A->fetchAll(PDO::FETCH_ASSOC);
    print (json_encode($result1A));
    print '%';
    //seconde requête: recherche d'une fiche quelque soit son état
    $req1B = $cnx->prepare(
        'SELECT * '
        . 'FROM `fichefrais` '
        . 'WHERE `idVisiteur` = :unIdVisiteur '
        . 'AND `mois` = :unMois;'
    );
    $req1B->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
    $req1B->bindParam(':unMois', $mois, PDO::PARAM_INT); 
    $req1B->execute();
    $result1B = $req1B->fetchAll(PDO::FETCH_ASSOC);
    print (json_encode($result1B));
    print '%';
    //sinon creation: aucune fiche en saisie n'existe pour ce mois
    if (empty($result1A) && empty($result1B)) {
        $req2 = $cnx->prepare(
            'INSERT INTO fichefrais (idvisiteur,mois,nbjustificatifs,'
            . 'montantvalide,datemodif,idetat) '
            . "VALUES (:unIdVisiteur,:unMois,0,0,now(),'CR')"
        );
        $req2->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $req2->bindParam(':unMois', $mois, PDO::PARAM_INT);
        $req2->execute();
    }
    //ETAPE 2: à condition que la fiche ne soit pas cloturée, validée ou remboursée
    if (empty($result1A)) {
            //Ici on teste si la ligne existe, si oui, update, sinon, creation
        $req3 = $cnx->prepare(
            'SELECT * '
            . 'FROM `lignefraisforfait` '
            . 'WHERE `idvisiteur` = :unIdVisiteur AND `mois` = :unMois '
            . "AND `idfraisforfait` = :unIDFrais;"
        );
        $req3->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $req3->bindParam(':unMois', $mois, PDO::PARAM_INT);
        $req3->bindParam(':unIDFrais', $unIdFrais, PDO::PARAM_STR);
        $req3->execute();
        $result = $req3->fetchAll(PDO::FETCH_ASSOC);
        print (json_encode($result));
        if (empty($result)) {    
            //insertion des nouvelles valeurs pour ce frais
            $req4 = $cnx->prepare(
                'INSERT INTO `lignefraisforfait` (`idvisiteur`,`mois`,'
                . '`idfraisforfait`,`quantite`) '
                . "VALUES(:unIdVisiteur, :unMois, :unIDFrais, :uneQte)"
            );
            $req4->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $req4->bindParam(':unMois', $mois, PDO::PARAM_INT);
            $req4->bindParam(':uneQte', $qte, PDO::PARAM_INT);
            $req4->bindParam(':unIDFrais', $unIdFrais, PDO::PARAM_STR);
            $req4->execute();
        } else {
            //MAJ de la valeur du frais
            $req5 = $cnx->prepare(
                'UPDATE `lignefraisforfait` '
                . 'SET `quantite`= :uneQte '
                . 'WHERE `idvisiteur` = :unIdVisiteur AND `mois` = :unMois '
                . "AND `idfraisforfait` = :unIDFrais"
            );
            $req5->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $req5->bindParam(':unMois', $mois, PDO::PARAM_INT);
            $req5->bindParam(':uneQte', $qte, PDO::PARAM_INT);
            $req5->bindParam(':unIDFrais', $unIdFrais, PDO::PARAM_STR);
            $req5->execute();
            print "update envoyé, erreur sur la requête";
        }
    }
}

/**
 * Créé un nouveau frais hors forfait pour un mois donné
 * La fiche doit être en mode 'saisie'
 * 
 * @param $cnx        la connexion à la base de données
 * @param $idVisiteur l'identifiant du visiteur
 * @param $mois       le mois concerné
 * @param $libelle    le motif du frais hors forfait
 * @param $montant    son montant
 * @param $date       sa date
 * 
 * @return null
 */
function setHf($cnx, $idVisiteur, $mois, $libelle, $montant, $date)
{
    //ETAPE 1
    //verification de l'existence d'une fiche disponible pour la saisie
    //première requête: recherche d'une fiche en saisie
    $req1A = $cnx->prepare(
        'SELECT * '
        . 'FROM `fichefrais` '
        . 'WHERE `idVisiteur` = :unIdVisiteur '
        . 'AND `mois` = :unMois '
        . "AND `idetat` IN ('CL', 'VA', 'RB');"
    );
    $req1A->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
    $req1A->bindParam(':unMois', $mois, PDO::PARAM_INT); 
    $req1A->execute();
    $result1A = $req1A->fetchAll(PDO::FETCH_ASSOC);
    print (json_encode($result1A));
    print '%';
    //seconde requête: recherche d'une fiche quelque soit son état
    $req1B = $cnx->prepare(
        'SELECT * '
        . 'FROM `fichefrais` '
        . 'WHERE `idVisiteur` = :unIdVisiteur '
        . 'AND `mois` = :unMois;'
    );
    $req1B->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
    $req1B->bindParam(':unMois', $mois, PDO::PARAM_INT); 
    $req1B->execute();
    $result1B = $req1B->fetchAll(PDO::FETCH_ASSOC);
    print (json_encode($result1B));
    print '%';
    //sinon creation: aucune fiche en saisie n'existe pour ce mois
    if (empty($result1A) && empty($result1B)) {
        $req2 = $cnx->prepare(
            'INSERT INTO fichefrais (idvisiteur,mois,nbjustificatifs,'
            . 'montantvalide,datemodif,idetat) '
            . "VALUES (:unIdVisiteur,:unMois,0,0,now(),'CR')"
        );
        $req2->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $req2->bindParam(':unMois', $mois, PDO::PARAM_INT);
        $req2->execute();
    }
    //enregistrement du frais hors forfait
    if (empty($result1A)) {
        $req3 = $cnx->prepare(
            'INSERT INTO `lignefraishorsforfait` '
            . '(`idvisiteur`, `mois`, `libelle`, `date`, `montant`) '
            . 'VALUES (:unIdVisiteur , :unMois, :unLibelle, :uneDate, :unMontant)'
        );
        $req3->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $req3->bindParam(':unMois', $mois, PDO::PARAM_INT);
        $req3->bindParam(':unLibelle', $libelle, PDO::PARAM_STR);
        $req3->bindParam(':uneDate', $date, PDO::PARAM_STR);
        $req3->bindParam(':unMontant', $montant, PDO::PARAM_STR);
        $req3->execute();
    }
}

/**
 * Recupere la liste des frais hors forfait pour un mois et un visiteur donné
 * 
 * @param $cnx        la connexion à la base de données
 * @param $idVisiteur l'identifiant du visiteur
 * @param $mois       le mois concerné
 * 
 * @return null
 */
function recupListeHf($cnx, $idVisiteur, $mois)
{
    $req = $cnx->prepare(
        'SELECT `id`, `libelle`, `montant`, `date` '
        . 'FROM `lignefraishorsforfait` '
        . 'WHERE `idvisiteur` = :unIdVisiteur AND `mois` = :unMois;'
    );
    $req->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
    $req->bindParam(':unMois', $mois, PDO::PARAM_STR);
    $req->execute();
    $result = $req->fetchAll(PDO::FETCH_ASSOC);
    print(json_encode($result));
}

/**
 * Supprime un frais hors forfait en fonction de son id
 * 
 * @param $cnx        la connexion à la base de données
 * @param $idUnique   l'identifiant du frais à supprimer
 * @param $idVisiteur l'id du visiteur
 * 
 * @return null
 */
function deleteHf($cnx, $idUnique, $idVisiteur)
{
    $req = $cnx->prepare(
        'DELETE FROM `lignefraishorsforfait` '
        . 'WHERE `id` = :idUnique '
        . 'AND `mois` IN '
        . '( SELECT `fichefrais`.`mois` '
        . 'FROM `fichefrais` '
        . "WHERE `idvisiteur` = :unIdVisiteur AND `idetat` = 'CR');"
    );
    $req->bindParam(':idUnique', $idUnique, PDO::PARAM_STR);
    $req->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
    $req->execute();
}


session_start();


if (isset($_POST['operation'])) {
    $cnx = connexionPDO();
    if ($_POST['operation'] == 'authentification') {
        print 'authentification%';
        $message = json_decode($_POST['message'], true); 
        $userAttempt = $message[0];
        $pwdAttempt = $message[1];
        $idVisiteur = $_POST['id'];
        authentification($cnx, $idVisiteur, $userAttempt, $pwdAttempt);
    } else if ($_POST['operation'] == 'recupKm') {
        print 'recupKm%';
        $idVisiteur = $_POST['id']; 
        $mois = $_POST['message']; 
        $unIdFrais = 'KM';
        recupQte($cnx, $idVisiteur, $mois, $unIdFrais);
    } else if ($_POST['operation'] == 'setKm') {
        $idVisiteur = $_POST['id'];
        $unIdFrais = 'KM';
        //traitement du message pour utilisation
        $message = json_decode($_POST['message'], true);
        $mois = $message[0];
        $qte = $message[1];
        setQte($cnx, $idVisiteur, $mois, $unIdFrais, $qte);
    } else if ($_POST['operation'] == 'recupNuitee') {
        print 'recupNuitee%';
        $idVisiteur = $_POST['id']; 
        $mois = $_POST['message']; 
        $unIdFrais = 'NUI';
        recupQte($cnx, $idVisiteur, $mois, $unIdFrais);
    } else if ($_POST['operation'] == 'setNuitee') {
        $idVisiteur = $_POST['id'];
        $unIdFrais = 'NUI';
        //traitement du message pour utilisation
        $message = json_decode($_POST['message'], true);
        $mois = $message[0];
        $qte = $message[1];
        setQte($cnx, $idVisiteur, $mois, $unIdFrais, $qte);
    } else if ($_POST['operation'] == 'recupRepas') {
        print 'recupRepas%';
        $idVisiteur = $_POST['id']; 
        $mois = $_POST['message']; 
        $unIdFrais = 'REP';
        recupQte($cnx, $idVisiteur, $mois, $unIdFrais);
    } else if ($_POST['operation'] == 'setRepas') {
        $idVisiteur = $_POST['id'];
        $unIdFrais = 'REP';
        //traitement du message pour utilisation
        $message = json_decode($_POST['message'], true);
        $mois = $message[0];
        $qte = $message[1];
        setQte($cnx, $idVisiteur, $mois, $unIdFrais, $qte);
    } else if ($_POST['operation'] == 'recupEtape') {
        print 'recupEtape%';
        $idVisiteur = $_POST['id']; 
        $mois = $_POST['message']; 
        $unIdFrais = 'ETP';
        recupQte($cnx, $idVisiteur, $mois, $unIdFrais);
    } else if ($_POST['operation'] == 'setEtape') {
        $idVisiteur = $_POST['id'];
        $unIdFrais = 'ETP';
        //traitement du message pour utilisation
        $message = json_decode($_POST['message'], true);
        $mois = $message[0];
        $qte = $message[1];
        setQte($cnx, $idVisiteur, $mois, $unIdFrais, $qte);
    } else if ($_POST['operation'] == 'setHf') {
        print 'setHf%';
        $idVisiteur = $_POST['id'];
        $message = json_decode($_POST['message'], true);
        $mois = $message[0];
        $libelle = $message[1];
        $date = $message[2];
        $montant = $message[3];
        setHf($cnx, $idVisiteur, $mois, $libelle, $montant, $date);
    } else if ($_POST['operation'] == 'recupListeHf') {
        print 'listeHf%';
        $idVisiteur = $_POST['id'];
        $message = json_decode($_POST['message'], true);
        $mois = $message[0];
        recupListeHf($cnx, $idVisiteur, $mois);
    } else if ($_POST['operation'] == 'deleteHf') {
        $idUnique = $_POST['message'];
        $idVisiteur = $_POST['id'];
        deleteHf($cnx, $idUnique, $idVisiteur);
    }
}
?>