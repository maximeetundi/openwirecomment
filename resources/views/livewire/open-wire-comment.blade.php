<!-- resources/views/livewire/open-wire-comment.blade.php -->
<style>
    /* Container global */
    .owc_container {
        max-width: 800px;
        margin: 0 auto;
        padding: 1rem;
        font-family: Arial, sans-serif;
    }

    .owc_title {
        font-size: 1.75rem;
        margin-bottom: 1rem;
        text-align: center;
    }

    /* Formulaire de commentaire */
    .owc_form {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .owc_textarea {
        width: 100%;
        min-height: 80px;
        padding: 0.75rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        resize: vertical;
        font-size: 1rem;
        margin-bottom: 0.75rem;
    }

    .owc_emoji-picker {
        margin-bottom: 0.75rem;
    }

    .owc_emoji {
        cursor: pointer;
        font-size: 1.5rem;
        margin-right: 0.5rem;
        transition: transform 0.1s;
    }

    .owc_emoji:hover {
        transform: scale(1.2);
    }

    .owc_file-input {
        margin-bottom: 0.75rem;
    }

    .owc_input {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-bottom: 0.75rem;
        font-size: 1rem;
    }

    .owc_button {
        background-color: #2563eb;
        color: #fff;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .owc_button:hover {
        background-color: #1e40af;
    }

    /* Affichage des commentaires */
    .owc_comments-list {
        margin-top: 1.5rem;
    }

    .owc_comment-card {
        background-color: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .owc_comment-content {
        white-space: pre-wrap;
        margin-bottom: 0.5rem;
    }

    .owc_comment-meta {
        font-size: 0.875rem;
        color: #555;
        margin-bottom: 0.5rem;
    }

    .owc_comment-actions {
        display: flex;
        align-items: center;
    }

    .owc_like-button {
        background: none;
        border: none;
        display: flex;
        align-items: center;
        cursor: pointer;
        color: #2563eb;
        font-size: 1rem;
        padding: 0.25rem 0.5rem;
        transition: color 0.2s;
    }

    .owc_like-button:hover {
        color: #1e40af;
    }

    .owc_like-icon {
        width: 20px;
        height: 20px;
        margin-right: 0.25rem;
        fill: currentColor;
    }

    /* Responsive */
    @media (max-width: 600px) {
        .owc_container {
            padding: 0.5rem;
        }
        .owc_textarea, .owc_input {
            font-size: 0.9rem;
        }
        .owc_title {
            font-size: 1.5rem;
        }
    }
</style>

<div class="owc_container" x-data="{ comment: @entangle('newComment') }">
    <h3 class="owc_title">Commentaires</h3>

    <!-- Formulaire d'ajout de commentaire -->
    <form wire:submit.prevent="submitComment" class="owc_form">
        <!-- Zone de texte avec liaison AlpineJS pour gérer les emojis -->
        <textarea x-model="comment" wire:model.defer="newComment" placeholder="Votre commentaire..." class="owc_textarea"></textarea>

        <!-- Sélecteur d'emojis -->
        <div class="owc_emoji-picker">
            <span class="owc_emoji" @click="comment += ' 😀'">😀</span>
            <span class="owc_emoji" @click="comment += ' 😃'">😃</span>
            <span class="owc_emoji" @click="comment += ' 😄'">😄</span>
            <span class="owc_emoji" @click="comment += ' 😁'">😁</span>
            <span class="owc_emoji" @click="comment += ' 😂'">😂</span>
            <span class="owc_emoji" @click="comment += ' 😎'">😎</span>
        </div>

        <!-- Upload de fichier -->
        <div class="owc_file-input">
            <input type="file" wire:model="attachment" class="owc_input">
            @error('attachment') <span class="owc_error" style="color: #e11d48;">{{ $message }}</span> @enderror
        </div>

        @if(!Auth::check())
            <input type="text" wire:model.defer="guestName" placeholder="Votre nom" class="owc_input">
            <input type="email" wire:model.defer="guestEmail" placeholder="Votre email" class="owc_input">
        @endif

        <button type="submit" class="owc_button">Envoyer</button>
    </form>

    @if (session()->has('message'))
        <div class="owc_message" style="margin-top: 0.75rem; color: #16a34a;">
            {{ session('message') }}
        </div>
    @endif

    <!-- Affichage des commentaires -->
    <div class="owc_comments-list">
        @foreach($comments as $comment)
            <div class="owc_comment-card">
                <div class="owc_comment-content">{!! nl2br(e($comment->content)) !!}</div>

                @if($comment->attachment)
                    <div class="owc_attachment" style="margin-bottom: 0.5rem;">
                        <a href="{{ asset('storage/' . $comment->attachment) }}" target="_blank" class="owc_attachment-link" style="color: #2563eb; text-decoration: underline;">
                            Voir la pièce jointe
                        </a>
                    </div>
                @endif

                <div class="owc_comment-meta">
                    Par {{ $comment->user ? $comment->user->name : $comment->guest_name }} - {{ $comment->created_at->diffForHumans() }}
                </div>
                <div class="owc_comment-actions">
                    <button wire:click="likeComment({{ $comment->id }})" class="owc_like-button">
                        <!-- Icône SVG pour le like -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="owc_like-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.8 4.6c-1.5-1.5-3.9-1.5-5.4 0L12 8.0l-3.4-3.4c-1.5-1.5-3.9-1.5-5.4 0s-1.5 3.9 0 5.4L12 20.8l8.8-8.8c1.5-1.5 1.5-3.9 0-5.4z"></path>
                        </svg>
                        Like ({{ $comment->likes->count() }})
                    </button>
                    <!-- Vous pouvez ajouter ici un bouton pour répondre -->
                </div>

                <!-- Affichage des réponses -->
                @if($comment->replies->count() > 0)
                    <div class="owc_replies" style="margin-top: 1rem; margin-left: 1rem; border-left: 2px solid #eee; padding-left: 1rem;">
                        @foreach($comment->replies as $reply)
                            <div class="owc_reply-card" style="margin-bottom: 0.75rem;">
                                <div class="owc_comment-content">{!! nl2br(e($reply->content)) !!}</div>
                                <div class="owc_comment-meta">
                                    Par {{ $reply->user ? $reply->user->name : $reply->guest_name }} - {{ $reply->created_at->diffForHumans() }}
                                </div>
                                <div class="owc_comment-actions">
                                    <button wire:click="likeComment({{ $reply->id }})" class="owc_like-button">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="owc_like-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20.8 4.6c-1.5-1.5-3.9-1.5-5.4 0L12 8.0l-3.4-3.4c-1.5-1.5-3.9-1.5-5.4 0s-1.5 3.9 0 5.4L12 20.8l8.8-8.8c1.5-1.5 1.5-3.9 0-5.4z"></path>
                                        </svg>
                                        Like ({{ $reply->likes->count() }})
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
