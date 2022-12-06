<?php
namespace Aschaeffer\SonataEditableListBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Listable
{
    const ANNOTATION_NAME = 'Aschaeffer\\SonataEditableListBundle\\Annotation\\Listable';

    /**
     * @Required
     */
    public string $code;

    public function getCode(): string
    {
        return $this->code;
    }
}