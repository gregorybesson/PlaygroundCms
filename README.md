AdfabCms
========

Cette librairie propose un menu de création de pages et blocs de contenu côté back-office. L'affichage en admin repose sur le module zf-commons/zfc-admin
en attendant mieux ;)

Dépendances :
- "doctrine/doctrine-orm-module" : "*",
- "doctrine/data-fixtures" : "dev-master",
- "zf-commons/zfc-base" : "0.*",
- "zf-commons/zfc-admin" : "0.*",
- "adfab/adfab-core" : "dev-master"

Cette librairie met à disposition un helper de vue afin d'afficher les blocs de contenu. Par exemple un bloc dont l'identifier serait "links" pourra être affiché :
<?php echo $this->adfabBlock('links'); ?>

