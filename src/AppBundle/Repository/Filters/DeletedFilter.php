<?php


namespace AppBundle\Repository\Filters;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

class DeletedFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if ($targetEntity->hasField("deletedAt")) {
            $date = date("Y-m-d h:m:s");

            return $targetTableAlias . ".deleted_at < '" . $date . "' OR " . $targetTableAlias . ".deleted_at IS NULL";
        }

        return "";
    }
}