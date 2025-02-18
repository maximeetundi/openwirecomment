# openwirecomment

**openwirecomment** est un package Laravel open source développé par **Maxime ETUNDI** (maximeetundi@gmail.com) qui offre un système de commentaires complet intégrant :

- Des commentaires et réponses (imbriqués) via une relation polymorphique.
- La possibilité d'ajouter des likes aux commentaires.
- L’upload d’un fichier en pièce jointe (avec une taille maximale de 10MB).
- Une intégration avec Livewire pour une interactivité en temps réel.
- Le support des emojis dans le contenu des commentaires.
- La gestion des utilisateurs authentifiés ET des visiteurs non authentifiés (saisie du nom et de l'email requise pour ces derniers).
- L'enregistrement de l'adresse IP et du user-agent à chaque commentaire.

## Fonctionnalités

- **Relation Polymorphique :** Associez le système de commentaires à n'importe quel modèle Eloquent.
- **Système de Like :** Chaque commentaire peut être liké une seule fois par utilisateur ou par adresse IP pour les visiteurs.
- **Upload de Fichier :** Possibilité d'attacher un fichier à un commentaire (taille maximale : 10MB).
- **Support Emoji :** Ajoutez facilement des emojis dans votre commentaire grâce à une interface intuitive basée sur AlpineJS.
- **Livewire & Réactivité :** Mise à jour en temps réel sans rechargement de la page.
- **Extensible & Personnalisable :** Publiez les vues et adaptez le style ou les fonctionnalités selon vos besoins.

## Installation

1. **Via Composer**

   Ajoutez le package à votre projet :

   ```bash
   composer require maximeetundi/openwirecomment
