<?php
namespace MaximeEtundi\OpenWireComment\Models;

use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    protected $fillable = [
        'comment_id',
        'user_id',
        'ip_address'
    ];

    protected $table = 'owc_comment_likes';

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
