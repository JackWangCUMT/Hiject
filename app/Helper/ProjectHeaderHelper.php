<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Helper;

use Hiject\Core\Base;

/**
 * Project Header Helper
 */
class ProjectHeaderHelper extends Base
{
    /**
     * Get current search query
     *
     * @access public
     * @param  array  $project
     * @return string
     */
    public function getSearchQuery(array $project)
    {
        $search = $this->request->getStringParam('search', $this->userSession->getFilters($project['id']));
        $this->userSession->setFilters($project['id'], $search);
        return urldecode($search);
    }

    /**
     * Render project header (views switcher and search box)
     *
     * @access public
     * @param  array  $project
     * @param  string $controller
     * @param  string $action
     * @param  bool   $boardView
     * @return string
     */
    public function render(array $project, $controller, $action, $boardView = false)
    {
        $filters = array(
            'controller' => $controller,
            'action' => $action,
            'project_id' => $project['id'],
            'search' => $this->getSearchQuery($project),
        );

        return $this->template->render('project_header/header', array(
            'project' => $project,
            'filters' => $filters,
            'categories_list' => $this->categoryModel->getList($project['id'], false),
            'users_list' => $this->projectUserRoleModel->getAssignableUsersList($project['id'], false),
            'custom_filters_list' => $this->customFilterModel->getAll($project['id'], $this->userSession->getId()),
            'board_view' => $boardView,
        ));
    }

    /**
     * Get project description
     *
     * @access public
     * @param  array  &$project
     * @return string
     */
    public function getDescription(array &$project)
    {
        if ($project['owner_id'] > 0) {
            $description = t('Project owner: ').'**'.$this->helper->text->e($project['owner_name'] ?: $project['owner_username']).'**'.PHP_EOL.PHP_EOL;

            if (! empty($project['description'])) {
                $description .= '***'.PHP_EOL.PHP_EOL;
                $description .= $project['description'];
            }
        } else {
            $description = $project['description'];
        }

        return $description;
    }
}
