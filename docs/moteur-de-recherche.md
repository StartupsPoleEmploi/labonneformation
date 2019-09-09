Moteur de recherche du site la bonne formation
=============================================

## Autocomplétion

1. Autocomplétion du champs "Je chercher une formation de"

Les libellés proposés à l'autocompletion sont issus de la liste des appellations du code ROME. Nous avons également une table de correspondances ROME => FORMACODE. Lorsque l'internaute va choisir un mot clé présent la liste des appellations du ROME, l'élément cliqué dans la liste suggérée représente un code ROME qui permet de déduire les FORMACODE associés qui vont nous permettre de rechercher les formations concernées.

Il est possible également de saisir un mot clé qui n'est pas présent dans la liste d'autocomplétion. (Cf. règles de gestion)

2. Autocomplétion du champs "Où"

Il existe quelques règles simples pour prendre en compte les différentes appellations d'un lieu. Comme les déclinaisons de "Saint => St", "Sainte => Ste", "Saintes => ...", etc.. Mais aussi des alias tels que "paca" qui sera reconnu et proposera la région "Provence Alpe Côte d'Azur".

Pour un lieu choisi, c'est ensuite le code insee correspondant qui sera fourni au moteur de recherche.

## Règle de gestion

* Si le mot clé est un mot clé provenant de la liste de l'autocomplétion alors la recherche se fera avec un formacode
* Si le mot clé ne fait pas partie de la liste alors le mot clé sera recherché dans le titre de la formation et le nom de l'organisme

### Liste des filtres pris en compte ###

* Lieu (via code insee donné par l'autocomplétion)
* Niveau de sortie
* Formation certifiante
* [Financée pour le DE](filtre-de-recherche.md)
* [Financée pour un salarié](filtre-de-recherche.md)
* Financement PIC
* Contrat d'apprentissage
* Contrat de professionalisation
* Modalité : à distance, en organisme


### Tri des résultats ###

Pour les formations dont le mot clé match:

Si le lieu est fourni :
1. Date de début de session
2. Lieu
3. Type de convention (financée PE)
4. Ordre de classement

Si le lieu n'est pas fourni:
1. Date de début de session
2. Type de convention (financée PE)
3. Ordre de classement

### Note sur le match par lieu ###

Si le lieu est fourni à la précision de la ville: la recherche est géolocalisée dans un rayon de 30 km (valeur par défaut).
Dans les autres cas, la recherche se fait sur l'appartenance du lieu au département, la région voire la France entière (selon le lieu saisi dans le formulaire de recherche).

Toutes formations dont le lieux de formations se trouvent à 30km au plus du lieux de recherche est éligible à figurer dans la liste de résultats.

Autrement dit selon les règles du tri des résultats pour un lieu donnée, une formation dont lieux serait à 20km du lieu recherché mais qui commencerait avant une formation à moins de 5 km de lieu recherché sera présenté en premier devant la formation plus proche du lieu recherché.


