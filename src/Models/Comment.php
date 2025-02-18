<?php
namespace MaximeEtundi\OpenWireComment\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'content',
        'attachment',
        'user_id',
        'guest_name',
        'guest_email',
        'ip_address',
        'user_agent',
        'parent_id'
    ];
    
    protected $table = 'owc_comments';
    // Relation polymorphique avec le modèle associé
    public function commentable()
    {
        return $this->morphTo();
    }

    // Relation pour les réponses
    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    // Relation vers l'utilisateur (si authentifié)
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    // Relation vers les likes
    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }
}
