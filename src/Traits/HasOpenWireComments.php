<?php

namespace MaximeEtundi\OpenWireComment\Traits;

use MaximeEtundi\OpenWireComment\Models\Comment;

trait HasOpenWireComments
{
    /**
     * Retourne la relation polymorphique des commentaires
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function openwireComments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
