<?php

declare(strict_types=1);


namespace Aschaeffer\SonataEditableListBundle\Model;

use Sonata\Doctrine\Model\ManagerInterface;
use Sonata\Doctrine\Model\PageableManagerInterface;

interface ItemManagerInterface extends ManagerInterface, PageableManagerInterface
{
}
