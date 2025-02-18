# openwirecomment

**openwirecomment** est un package Laravel open source développé par **Maxime ETUNDI** (maximeetundi@gmail.com) qui offre un système de commentaires complet intégrant :

- Des commentaires et réponses (imbriqués) via une relation polymorphique.
- La possibilité d'ajouter des likes aux commentaires.
- L’upload d’un fichier en pièce jointe (avec une taille maximale de 10MB).
- Le support des emojis dans le contenu des commentaires via AlpineJS.
- Une intégration avec Livewire pour une interactivité en temps réel.
- La gestion des utilisateurs authentifiés ET des visiteurs non authentifiés (saisie du nom et de l'email requise pour ces derniers).
- L'enregistrement de l'adresse IP et du user-agent à chaque commentaire.

## Fonctionnalités

- **Relation Polymorphique & Trait Personnalisé :**  
  Associez le système de commentaires à n'importe quel modèle Eloquent en utilisant le trait dédié `HasOpenWireComments`.

- **Système de Like :**  
  Chaque commentaire peut être liké une seule fois par utilisateur ou par adresse IP pour les visiteurs.

- **Upload de Fichier :**  
  Possibilité d'attacher un fichier à un commentaire (taille maximale : 10MB).

- **Support Emoji :**  
  Ajoutez des emojis facilement dans votre commentaire grâce à un sélecteur intégré (basé sur AlpineJS).

- **Livewire & Réactivité :**  
  Mise à jour en temps réel sans rechargement de page.

## Installation

1. **Via Composer**

   Ajoutez le package à votre projet Laravel :

   ```bash
   composer require maximeetundi/openwirecomment
   ```

2. **Publication des Assets**

   Publiez les vues (et éventuellement d'autres fichiers comme la config si vous en ajoutez) :

   ```bash
   php artisan vendor:publish --provider="MaximeEtundi\OpenWireComment\OpenWireCommentServiceProvider" --tag=views
   ```

3. **Exécutez les Migrations**

   Pour créer les tables nécessaires dans votre base de données :

   ```bash
   php artisan migrate
   ```

## Utilisation

### 1. Associer le Trait à un Modèle

Pour intégrer le système de commentaires à n'importe quel modèle Eloquent (par exemple, un modèle `Article`), ajoutez le trait `HasOpenWireComments` à votre modèle.

**Exemple :**

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MaximeEtundi\OpenWireComment\Traits\HasOpenWireComments;

class Article extends Model
{
    use HasOpenWireComments;

    // Vos autres propriétés et méthodes...
}
```

Ce trait ajoute une méthode nommée `openwireComments()` qui permet d'accéder aux commentaires liés à l'instance du modèle :

```php
$article = Article::find(1);
$comments = $article->openwireComments; // Récupère tous les commentaires associés à cet article
```

### 2. Intégrer le Composant Livewire dans une Vue

Le package fournit un composant Livewire pour gérer l'affichage et la gestion des commentaires en temps réel.

**Exemple dans une vue Blade :**

```blade
<!-- resources/views/articles/show.blade.php -->
<h1>{{ $article->title }}</h1>
<p>{{ $article->content }}</p>

<!-- Intégration du composant Livewire en passant l'instance du modèle -->
<livewire:open-wire-comment :commentable="$article" />
```

Le composant se charge de :

- Afficher les commentaires existants (y compris les réponses imbriquées).
- Gérer l'ajout de nouveaux commentaires avec le support d'upload de fichier, d'emojis, et la gestion des utilisateurs authentifiés/non authentifiés.
- Permettre l'ajout de likes pour chaque commentaire.

### 3. Personnalisation et Extensibilité

- **Vues Personnalisées :**  
  Les vues se trouvent dans le dossier `resources/views/vendor/openwirecomment` (après publication). Vous pouvez les modifier pour adapter l'interface à vos besoins.

- **Configuration et Validation :**  
  Vous pouvez ajuster la taille maximale du fichier ou d'autres règles de validation directement dans le composant Livewire (`OpenWireComment.php`).

- **Réponses et Autres Fonctionnalités :**  
  Le package est conçu pour être extensible. Vous pouvez ajouter des fonctionnalités telles que la pagination, l'édition ou la suppression des commentaires en modifiant le composant ou en étendant le trait.

## Exemple Complet d'Utilisation

1. **Ajout du Trait dans le Modèle :**

   ```php
   // App/Models/Article.php
   namespace App\Models;

   use Illuminate\Database\Eloquent\Model;
   use MaximeEtundi\OpenWireComment\Traits\HasOpenWireComments;

   class Article extends Model
   {
       use HasOpenWireComments;

       // ...
   }
   ```

2. **Utilisation du Composant Livewire dans la Vue :**

   ```blade
   <!-- resources/views/articles/show.blade.php -->
   <h1>{{ $article->title }}</h1>
   <p>{{ $article->content }}</p>

   <livewire:open-wire-comment :commentable="$article" />
   ```

3. **Accès aux Commentaires dans le Code :**

   ```php
   $article = Article::find(1);
   // Accès aux commentaires via le trait
   $comments = $article->openwireComments()->with('replies')->get();
   ```

## Contribution

Les contributions sont les bienvenues !  
Si vous souhaitez contribuer à **openwirecomment**, merci de :

1. Forker le dépôt.
2. Créer une branche pour votre fonctionnalité (`git checkout -b feature/nom-de-la-fonctionnalite`).
3. Commiter vos changements.
4. Envoyer une Pull Request.

## Licence

Ce package est sous licence **MIT**.  
Pour plus d'informations, consultez le fichier [LICENSE](LICENSE).

---

N'hésitez pas à poser vos questions ou à signaler des bugs via [GitHub Issues](https://github.com/maximeetundi/openwirecomment/issues).
