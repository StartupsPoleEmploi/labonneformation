From: "La Bonne Formation" <<?php _T($from);?>>
To: <<?php _T($to);?>>
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:31.0) Gecko/20100101 Thunderbird/31.8.0
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Subject: Immersion professionnelle <?php _T($demande['locationLabel']);?> 


Lieu : <?php _T($demande['locationLabel']);?> 
Path : <?php _T($demande['locationpath']);?> 
ROME : <?php _T($demande['rome']);?> 
Métier : <?php _T($demande['romeLabel']);?> 
Date début : <?php _T(date('d-m-Y',$demande['debut']));?> 
Durée : <?php _T($demande['duree']);?> 
Nom : <?php _T($demande['nom']);?> 
Prénom : <?php _T($demande['prenom']);?> 
Statut : <?php _T($demande['statut']);?> 
Identifiant : xxxxxxxx 
Email : <?php _T($demande['email']);?> 

<?php $i=1;?>
<?php foreach($entreprises as $e): ?>

-----
Entreprise <?php _T($i++);?>


id : <?php _T($e['id']);?> 
Latitude : <?php _T($e['lat']);?> 
Longitude : <?php _T($e['lng']);?> 
Adresse : <?php _T($e['adresse']);?> 
Code postal : <?php _T($e['codepostal']);?> 
Commune : <?php _T($e['commune']);?> 
Enseigne : <?php _T($e['enseigne']);?> 
Nom correspondant : <?php _T($e['nomprenomcorrespondant']);?> 
Email : <?php _T($e['email']);?> 
Téléphone : <?php _T($e['telephonecorresppondant']);?> 
Siret : <?php _T($e['siretetablissement']);?> 
ROME : <?php _T($e['rome']);?> 
Secteur large : <?php _T($e['secteurlarge']);?> 
Distance : <?php _T($e['distance']);?>km 

<?php endforeach; ?>
