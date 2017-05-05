<?php

namespace AppBundle\Services;

use AppBundle\Entity\Module;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

class DirectoryNamer implements DirectoryNamerInterface
{
    /**
     * Returns the name of a directory where files will be uploaded
     *
     * Directory name is formed based on course abbreviation.
     *
     * @param Module          $module
     * @param PropertyMapping $mapping
     *
     * @return string
     */
    public function directoryName($module, PropertyMapping $mapping)
    {
        $course = $module->getCourse();
        $abbreviation = $course->getAbbreviation();

        return '/'.$abbreviation.'/';
    }
}
