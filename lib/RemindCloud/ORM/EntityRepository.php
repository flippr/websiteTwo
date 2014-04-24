<?php
namespace RemindCloud\ORM;

use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;

class EntityRepository extends \Doctrine\ORM\EntityRepository
{
    const MAX_COUNT = 75;

    public function addClientBaseQuery(&$qb)
    {
        $qb->andWhere('client.suspendReminders <> 1')
            ->andWhere('client.inactive <> 1')
            ->andWhere('client.validEmail = 1');

        return $qb;
    }

    /**
     * Wrapper for Doctrine\ORM\Query::getSingleResult().
     *
     * @see Doctrine\ORM\Query::getSingleResult()
     *
     * @param \Doctrine\ORM\QueryBuilder $qb The query builder to get the result form.
     * @param integer $hydrate Hydration mode.
     *
     * @return array|null
     */
    public function getSingleResult($qb, $hydrate = Query::HYDRATE_OBJECT)
    {
        try
        {
            $result = $qb->getQuery()->getSingleResult($hydrate);
        }
        catch (NoResultException $e)
        {
            $result = null;
        }

        return $result;
    }

    /**
     * Wrapper for Doctrine\ORM\Query::getResult().
     *
     * @see Doctrine\ORM\Query::getResult()
     *
     * @param \Doctrine\ORM\QueryBuilder $qb The query builder to get the result form.
     * @param integer $hydrate Hydration mode.
     *
     * @return array|null
     */
    public function getResult($qb, $hydrate = Query::HYDRATE_OBJECT)
    {
        try
        {
            $result = $qb->getQuery()->getResult($hydrate);
        }
        catch (NoResultException $e)
        {
            $result = null;
        }

        return $result;
    }

    /**
     * Finds the count of a query using criteria.
     *
     * @see createQueryBuilderFromCriteria()
     *
     * @param array $criteria
     * @param array|boolean    @entities
     *
     * @return the result
     */
    public function countByCriteria(array $criteria = array())
    {
        $ownTable = $this->getClassMetadata()->table['name'];

        $qb = $this->createQueryBuilderFromCriteria($criteria, false);
        $qb->select("count({$ownTable})");
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Find using criteria.
     *
     * @see createQueryBuilderFromCriteria()
     *
     * @param array $criteria
     * @param array|boolean $entities
     * @param integer $hydrate
     *
     * @return the result
     */
    public function findByCriteria(array $criteria = array(),
                                   $hydrate = Query::HYDRATE_OBJECT, $limitResults = true
    )
    {
        return $this->createQueryBuilderFromCriteria($criteria, $limitResults)
            ->getQuery()->getResult($hydrate);
    }

    /**
     * Find one using criteria.
     *
     * @see createQueryBuilderFromCriteria()
     *
     * @param array $criteria
     * @param array|boolean $entities
     * @param integer $hydrate
     *
     * @return the result
     */
    public function findOneByCriteria(array $criteria = array(),
                                      $hydrate = Query::HYDRATE_OBJECT, $limitResults = true
    )
    {
        return $this->createQueryBuilderFromCriteria($criteria, $limitResults)
            ->getQuery()->getSingleResult($hydrate);
    }

    /**
     * Builds a query based on passed criteria.
     *
     * @todo More documentation on filter usage.
     *
     * @param array $criteria The criteria to filter by.
     * @param boolean $limitResults If set to false no upper bound will be set on the returned results.
     *
     * @return Doctrine\ORM\QueryBuilder
     */
    public function createQueryBuilderFromCriteria(array $criteria = array(),
                                                   $limitResults = true
    )
    {
        $em = $this->getEntityManager();
        $selfMetadata = $em->getClassMetadata($this->getEntityName());
        $selfTable = $selfMetadata->table['name'];

        $qb = $this->createQueryBuilder($selfTable);

        if (isset($criteria['select']))
        {
            $qb->select($criteria['select']);
            unset($criteria['select']);
        }

        if (isset($criteria['entities']))
        {
            $this->_addEntitiesToQueryBuilder($qb, $criteria['entities'], $selfTable);
            unset($criteria['entities']);
        }

        $this->_addSortingToQueryBuilder($qb, $criteria);
        $this->_addFilteringToQueryBuilder($qb, $criteria);
        $this->_addLimitToQueryBuilder($qb, $criteria, $limitResults);

        return $qb;
    }

    /**
     * Adds limit to QueryBuilder.
     *
     * @param Doctrine\ORM\QueryBuilder $qb The QueryBuilder to add to.
     * @param array $criteria Array of criteria.
     * @param boolean $limitResults Limits the results to self::MAX_COUNT if true.
     *
     * @return Doctrine\ORM\QueryBuilder
     */
    private function _addLimitToQueryBuilder(&$qb, &$criteria, $limitResults)
    {
        if (isset($criteria['start']))
        {
            $start = $criteria['start'];
            unset($criteria['start']);
        }
        else
        {
            $start = 0;
        }

        $count = null;
        if (isset($criteria['count']))
        {
            $count = ($limitResults && $criteria['count'] > self::MAX_COUNT) ? self::MAX_COUNT
                : $criteria['count'];
            unset($criteria['count']);
        }
        else if ($limitResults)
        {
            $count = self::MAX_COUNT;
        }

        if ($count)
        {
            $qb->setFirstResult($start)->setMaxResults($count);
        }

        return $qb;
    }

    /**
     * Adds sorting to QueryBuilder. Two types of sorting are supported
     * (additional fields separated with a comma):
     * 1) Passing a key of sort with a list, e.g., sort=+arg,_arg1
     * 2) Passing sort directly, e.g., sort(+arg) or sort(+arg,_arg1)
     *
     * @param Doctrine\ORM\QueryBuilder $qb The QueryBuilder to add to.
     * @param array $criteria Array of criteria.
     *
     * @return Doctrine\ORM\QueryBuilder
     */
    private function _addSortingToQueryBuilder(&$qb, &$criteria)
    {
        $em = $this->getEntityManager();
        $selfMetadata = $em->getClassMetadata($this->getEntityName());
        $selfTable = $selfMetadata->table['name'];

        if (isset($criteria['sort']))
        {
            $sort = $criteria['sort'];
        }
        else
        {
            $sort = null;
            foreach (array_keys($criteria) as $k)
            {
                if (preg_match('/^sort\(([\+|_|-]?.+)\)/i', $k, $matches))
                {
                    $sort = $matches[1];
                    break;
                }
            }
        }

        if (empty($sort))
        {
            return $qb;
        }

        if (preg_match("/(?P<sep>,|;)/", $sort, $matches))
        {
            $sort = explode($matches['sep'], $sort);
        }

        if (!is_array($sort))
        {
            $sort = array($sort);
        }

        foreach ($sort as $v)
        {
            $field = null;
            $dir = 'ASC';
            $table = null;

            if (preg_match('/(?P<dir>\+|_|-)?(?:(?P<table>\w+)-)?(?P<field>\w+)/i', $v,
                $matches)
            )
            {
                $table = ($matches['table']) ? $matches['table'] : $selfTable;
                $field = $matches['field'];
                switch ($matches['dir'])
                {
                    case '_':
                    case '+':
                        $dir = 'ASC';
                        break;
                    case '-':
                        $dir = 'DESC';
                        break;
                }

                $qb->addOrderBy("{$table}.{$field}", $dir);
            }
        }

        return $qb;
    }

    /**
     * Recursive method to add entities via left joins to a QueryBuilder.
     *
     * @param Doctrine\ORM\QueryBuilder $qb The query builder to use.
     * @param array $criteria Array of criteria.
     * @param string $parent Parent table to join entities on.
     *
     * @return Doctrine\ORM\QueryBuilder
     */
    private function _addEntitiesToQueryBuilder(&$qb, $entities, $parent)
    {
        if (!is_array($entities))
        {
            if (preg_match("/(?P<sep>,|;)/", $entities, $matches))
            {
                $entities = explode($matches['sep'], $entities);
            }
        }

        if (!is_array($entities))
        {
            $entities = array($entities);
        }

        foreach ($entities as $e)
        {
            if (preg_match('/(?P<parent>\w+)-(?P<entity>\w+)/', $e, $matches))
            {
                $qb = $this
                    ->_addEntitiesToQueryBuilder($qb, $matches['entity'],
                        $matches['parent']);
            }
            else
            {
                $qb->leftJoin("{$parent}.{$e}", $e);
            }
        }

        return $qb;
    }

    /**
     * Adds filtering to QueryBuilder based on criteria. All
     * criteria is checked with entity metadata to ensure field
     * integrity.
     *
     * @param Doctrine\ORM\QueryBuilder $qb QueryBuilder to add criteria to.
     * @param array $criteria Array of criteria to filter with.
     *
     * @return Doctrine\ORM\QueryBuilder
     */
    private function _addFilteringToQueryBuilder(&$qb, &$criteria)
    {
        $em = $this->getEntityManager();
        $selfMetadata = $em->getClassMetadata($this->getEntityName());
        $selfTable = $selfMetadata->table['name'];
        $metadata[$selfTable] = $selfMetadata;

        $filters = array();
        foreach ($criteria as $k => $v)
        {
            if (is_array($v))
            {
                continue;
            }

            $matches = array();
            if (preg_match('/^(?P<table>\w+)-(?P<field>\w+)$/', $k, $matches))
            {
                $table = $matches['table'];
                $field = $matches['field'];

                if (isset($selfMetadata->associationMappings[$table]))
                {
                    if (!isset($metadata[$table]))
                    {
                        $entityName = $selfMetadata
                            ->associationMappings[$table]['targetEntity'];
                        $metadata[$table] = $em->getClassMetadata($entityName);
                    }

                    if (isset($metadata[$table]->reflFields[$field]))
                    {
                        $filters[$table][$field] = $v;
                    }
                }
            }
            elseif (isset($selfMetadata->reflFields[$k]))
            {
                $filters[$selfTable][$k] = $v;
            }
        }

        foreach ($filters as $table => $fields)
        {
            foreach ($fields as $field => $value)
            {
                $value = trim($value);

                $clause = "{$table}.{$field}";
                $expr = null;
                if (strstr($value, '*'))
                {
                    $expr = $qb->expr()
                        ->like($clause,
                            $qb->expr()->literal(str_replace('*', '%', $value)));
                }
                elseif (strcasecmp($value, 'null') == 0)
                {
                    $expr = "{$clause} IS NULL";
                }
                elseif (strcasecmp($value, '!null') == 0)
                {
                    $expr = "{$clause} IS NOT NULL";
                }
                elseif (preg_match("/^(?P<op>!|>=|<=|>|<)/", $value, $matches))
                {
                    $value = str_replace($matches['op'], '', $value);
                    switch ($matches['op'])
                    {
                        case '!':
                            $expr = $qb->expr()->neq($clause, $value);
                            break;
                        case '>':
                            $expr = $qb->expr()->gt($clause, $value);
                            break;
                        case '>=':
                            $expr = $qb->expr()->gte($clause, $value);
                            break;
                        case '<':
                            $expr = $qb->expr()->lt($clause, $value);
                            break;
                        case '<=':
                            $expr = $qb->expr()->lte($clause, $value);
                            break;
                    }
                }
                else
                {
                    $expr = $qb->expr()
                        ->eq($clause, $qb->expr()->literal((string)$value));
                }

                if ($expr)
                {
                    $qb->andWhere($expr);
                }
            }
        }

        return $qb;
    }
}
