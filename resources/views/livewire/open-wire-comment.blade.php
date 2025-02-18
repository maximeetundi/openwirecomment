<div x-data="{ comment: @entangle('newComment') }">
    <h3>Commentaires</h3>

    <!-- Formulaire d'ajout de commentaire -->
    <form wire:submit.prevent="submitComment">
        <!-- Zone de texte avec liaison AlpineJS pour gérer les emojis -->
        <textarea x-model="comment" wire:model.defer="newComment" placeholder="Votre commentaire..." rows="3" class="w-full p-2 border"></textarea>

        <!-- Sélecteur d'emojis -->
        <div class="mt-2">
            <span class="cursor-pointer text-xl" @click="comment += ' 😀'">😀</span>
            <span class="cursor-pointer text-xl" @click="comment += ' 😃'">😃</span>
            <span class="cursor-pointer text-xl" @click="comment += ' 😄'">😄</span>
            <span class="cursor-pointer text-xl" @click="comment += ' 😁'">😁</span>
            <span class="cursor-pointer text-xl" @click="comment += ' 😂'">😂</span>
            <span class="cursor-pointer text-xl" @click="comment += ' 😎'">😎</span>
        </div>

        <!-- Upload de fichier -->
        <div class="mt-2">
            <input type="file" wire:model="attachment" class="border p-1">
            @error('attachment') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        @if(!Auth::check())
            <input type="text" wire:model.defer="guestName" placeholder="Votre nom" class="w-full p-2 border mt-2">
            <input type="email" wire:model.defer="guestEmail" placeholder="Votre email" class="w-full p-2 border mt-2">
        @endif

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-2">Envoyer</button>
    </form>

    @if (session()->has('message'))
        <div class="mt-2 text-green-600">{{ session('message') }}</div>
    @endif

    <!-- Affichage des commentaires -->
    <div class="mt-4 space-y-4">
        @foreach($comments as $comment)
            <div class="p-4 border rounded">
                <p>{!! nl2br(e($comment->content)) !!}</p>

                @if($comment->attachment)
                    <div class="mt-2">
                        <a href="{{ asset('storage/' . $comment->attachment) }}" target="_blank" class="text-blue-500 underline">
                            Voir la pièce jointe
                        </a>
                    </div>
                @endif

                <small class="text-gray-600">
                    Par {{ $comment->user ? $comment->user->name : $comment->guest_name }} - {{ $comment->created_at->diffForHumans() }}
                </small>
                <div class="mt-2">
                    <button wire:click="likeComment({{ $comment->id }})" class="text-blue-500">
                        Like ({{ $comment->likes->count() }})
                    </button>
                    <!-- Bouton pour répondre (optionnel) -->
                    <!-- Vous pouvez ajouter ici une logique pour afficher un formulaire de réponse -->
                </div>

                <!-- Affichage des réponses -->
                @if($comment->replies->count() > 0)
                    <div class="mt-4 ml-4 border-l pl-4">
                        @foreach($comment->replies as $reply)
                            <div class="mb-2">
                                <p>{!! nl2br(e($reply->content)) !!}</p>
                                <small class="text-gray-600">
                                    Par {{ $reply->user ? $reply->user->name : $reply->guest_name }} - {{ $reply->created_at->diffForHumans() }}
                                </small>
                                <div>
                                    <button wire:click="likeComment({{ $reply->id }})" class="text-blue-500">
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
