<?php

namespace Nowendwell\LaravelArchivable;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use \Carbon\Carbon;

class ArchivableScope implements Scope
{
    /**
     * All of the extensions to be added to the builder.
     *
     * @var array
     */
    protected $extensions = ['Archive', 'Unarchive', 'WithArchived', 'WithoutArchived', 'OnlyArchived'];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereNull($model->getQualifiedArchivedAtColumn());
    }

    /**
     * Extend the query builder with the needed functions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }
    }

    /**
     * Get the "deleted at" column for the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return string
     */
    protected function getArchivedAtColumn(Builder $builder)
    {
        if (count($builder->getQuery()->joins) > 0) {
            return $builder->getModel()->getQualifiedArchivedAtColumn();
        }

        return $builder->getModel()->getArchivedAtColumn();
    }

    /**
     * Add the archive extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addArchive(Builder $builder)
    {
        $builder->macro('archive', function (Builder $builder) {
            return $builder->update([$builder->getModel()->getArchivedAtColumn() => Carbon::now()]);
        });
    }

    /**
     * Add the unarchive extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addUnarchive(Builder $builder)
    {
        $builder->macro('unarchive', function (Builder $builder) {
            $builder->withArchived();

            return $builder->update([$builder->getModel()->getArchivedAtColumn() => null]);
        });
    }

    /**
     * Add the with-archived extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addWithArchived(Builder $builder)
    {
        $builder->macro('withArchived', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });
    }

    /**
     * Add the without-archived extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addWithoutArchived(Builder $builder)
    {
        $builder->macro('withoutArchived', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->whereNull(
                $model->getQualifiedArchivedAtColumn()
            );

            return $builder;
        });
    }

    /**
     * Add the only-archived extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addOnlyArchived(Builder $builder)
    {
        $builder->macro('onlyArchived', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->whereNotNull(
                $model->getQualifiedArchivedAtColumn()
            );

            return $builder;
        });
    }
}
