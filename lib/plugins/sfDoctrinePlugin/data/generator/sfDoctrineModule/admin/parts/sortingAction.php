    protected function addSortQuery(Doctrine_Query $query)
    {
        if (array(null, null) == ($sort = $this->getSort()))
        {
            return;
        }

        if (!in_array(strtolower($sort[1]), array('asc', 'desc')))
        {
            $sort[1] = 'asc';
        }

        if (isset($query->getSqlParts()["select"]) && !empty($query->getSqlParts()["select"]) &&
            !\str_contains($query->getSqlParts()["select"][0], $sort[0])) {
            $query->addSelect($sort[0]);
        }
        $query->addOrderBy("{$sort[0]} {$sort[1]}");
    }

    protected function getSort()
    {
        if (null !== $sort = $this->getUser()->getAttribute('<?php echo $this->getModuleName() ?>.sort', null, 'admin_module')) {
            return $sort;
        }

       $this->setSort($this->configuration->getDefaultSort());

        return $this->getUser()->getAttribute('<?php echo $this->getModuleName() ?>.sort', null, 'admin_module');
    }

    protected function setSort(array $sort)
    {
        if (null !== $sort[0] && null === $sort[1]) {
            $sort[1] = 'asc';
        }

        $this->getUser()->setAttribute('<?php echo $this->getModuleName() ?>.sort', $sort, 'admin_module');
    }

    protected function isValidSortColumn($column)
    {
        return Doctrine_Core::getTable('<?php echo $this->getModelClass() ?>')->hasColumn($column);
    }
