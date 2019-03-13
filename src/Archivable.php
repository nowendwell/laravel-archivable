<?php

namespace Nowendwell\LaravelArchivable;

use Nowendwell\LaravelArchivable\ArchivableScope;
use \Carbon\Carbon;

trait Archivable {

    public static function bootArchivable()
	{
        static::addGlobalScope(new ArchivableScope);
	}

    public function archive()
    {
        $this->{$this->getQualifiedArchivedAtColumn()} = Carbon::now();
        $this->save();
    }

    public function unarchive()
    {
        $this->{$this->getQualifiedArchivedAtColumn()} = null;
        $this->save();
    }

    /**
     * Determine if the model instance has been archived.
     *
     * @return bool
     */
    public function archived()
    {
        return ! is_null($this->{$this->getArchivedAtColumn()});
    }

    /**
     * Get the fully qualified "archived at" column.
     *
     * @return string
     */
    public function getQualifiedArchivedAtColumn()
    {
        return $this->getTable().'.'.$this->getArchivedAtColumn();
    }

    /**
     * Get the name of the "archived at" column.
     *
     * @return string
     */
    public function getArchivedAtColumn()
    {
        return defined('static::ARCHIVED_AT') ? static::ARCHIVED_AT : 'archived_at';
    }

}
