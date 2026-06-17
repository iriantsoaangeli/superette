## Commandes migration et seed 


### Initialiser le fichier .db
`
php spark
`
### Creer tables si elles n'existent pas

Generer un fichier script
`
php spark make:migration maMigration
php spark make:seed monSeed
`
Lancer le script
`
php spark migrate --all
`
