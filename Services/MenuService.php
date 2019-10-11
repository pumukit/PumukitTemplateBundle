<?php

namespace Pumukit\TemplateBundle\Services;

use Pumukit\NewAdminBundle\Menu\ItemInterface;

class MenuService implements ItemInterface
{
    public function getName(): string
    {
        return 'Edit Templates';
    }

    public function getUri(): string
    {
        return 'pumukit_template_crud_index';
    }

    public function getAccessRole(): string
    {
        return 'ROLE_ACCESS_TEMPLATES';
    }
}
