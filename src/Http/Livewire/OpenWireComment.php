<?php

namespace MaximeEtundi\OpenWireComment\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use MaximeEtundi\OpenWireComment\Models\Comment;
use MaximeEtundi\OpenWireComment\Models\CommentLike;
use Illuminate\Support\Facades\Auth;

class OpenWireComment extends Component
{
    use WithFileUploads;

    // Le modèle auquel les commentaires seront associés
    public $commentable;
    public $newComment;
    public $guestName;
    public $guestEmail;
    // Upload de fichier attaché (optionnel)
    public $attachment;
    // Si on répond à un commentaire existant, on stocke son id
    public $parentId = null;

    protected $rules = [
        'newComment' => 'required|min:3',
        // Taille maximale de 10MB (10240 kilobytes)
        'attachment' => 'nullable|file|max:10240',
    ];

    public function mount($commentable)
    {
        $this->commentable = $commentable;
    }

    public function submitComment()
    {
        // Validation du commentaire et du fichier
        $this->validate();

        // Pour les visiteurs non authentifiés, valider nom et email
        if (!Auth::check()) {
            $this->validate([
                'guestName'  => 'required',
                'guestEmail' => 'required|email',
            ]);
        }

        $comment = new Comment();
        $comment->content = $this->newComment;
        // Association polymorphique
        $comment->commentable()->associate($this->commentable);
        $comment->parent_id = $this->parentId;

        if (Auth::check()) {
            $comment->user_id = Auth::id();
        } else {
            $comment->guest_name  = $this->guestName;
            $comment->guest_email = $this->guestEmail;
        }

        // Traitement de l'upload de fichier s'il existe
        if ($this->attachment) {
            // Stockage dans "storage/app/public/comments"
            $path = $this->attachment->store('comments', 'public');
            $comment->attachment = $path;
        }

        // Enregistrement des informations de l'utilisateur
        $comment->ip_address = request()->ip();
        $comment->user_agent = request()->header('User-Agent');
        $comment->save();

        // Réinitialiser les champs du formulaire
        $this->newComment = '';
        $this->guestName = '';
        $this->guestEmail = '';
        $this->attachment = null;
        $this->parentId = null;

        session()->flash('message', 'Commentaire ajouté.');
    }

    public function likeComment($commentId)
    {
        $comment = Comment::find($commentId);
        if (!$comment) {
            return;
        }

        // Vérification si l'utilisateur (ou l'IP pour les visiteurs) a déjà liké
        if (Auth::check()) {
            $exists = $comment->likes()->where('user_id', Auth::id())->exists();
        } else {
            $exists = $comment->likes()->where('ip_address', request()->ip())->exists();
        }

        if (!$exists) {
            $like = new CommentLike();
            $like->comment_id = $comment->id;
            if (Auth::check()) {
                $like->user_id = Auth::id();
            } else {
                $like->ip_address = request()->ip();
            }
            $like->save();
        }
    }

    public function render()
    {
        // Récupérer les commentaires racines (sans parent) pour le modèle associé
        $comments = Comment::where('commentable_id', $this->commentable->id)
            ->where('commentable_type', get_class($this->commentable))
            ->whereNull('parent_id')
            ->with(['replies', 'likes', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('openwirecomment::livewire.open-wire-comment', compact('comments'));
    }
}
