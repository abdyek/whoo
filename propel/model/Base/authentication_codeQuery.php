<?php

namespace Base;

use \authentication_code as Childauthentication_code;
use \authentication_codeQuery as Childauthentication_codeQuery;
use \Exception;
use \PDO;
use Map\authentication_codeTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'whoo_authentication_code' table.
 *
 *
 *
 * @method     Childauthentication_codeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     Childauthentication_codeQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     Childauthentication_codeQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method     Childauthentication_codeQuery orderByTrialCount($order = Criteria::ASC) Order by the trial_count column
 * @method     Childauthentication_codeQuery orderByDateTime($order = Criteria::ASC) Order by the date_time column
 * @method     Childauthentication_codeQuery orderByMemberId($order = Criteria::ASC) Order by the member_id column
 *
 * @method     Childauthentication_codeQuery groupById() Group by the id column
 * @method     Childauthentication_codeQuery groupByType() Group by the type column
 * @method     Childauthentication_codeQuery groupByCode() Group by the code column
 * @method     Childauthentication_codeQuery groupByTrialCount() Group by the trial_count column
 * @method     Childauthentication_codeQuery groupByDateTime() Group by the date_time column
 * @method     Childauthentication_codeQuery groupByMemberId() Group by the member_id column
 *
 * @method     Childauthentication_codeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     Childauthentication_codeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     Childauthentication_codeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     Childauthentication_codeQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     Childauthentication_codeQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     Childauthentication_codeQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     Childauthentication_codeQuery leftJoinMember($relationAlias = null) Adds a LEFT JOIN clause to the query using the Member relation
 * @method     Childauthentication_codeQuery rightJoinMember($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Member relation
 * @method     Childauthentication_codeQuery innerJoinMember($relationAlias = null) Adds a INNER JOIN clause to the query using the Member relation
 *
 * @method     Childauthentication_codeQuery joinWithMember($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Member relation
 *
 * @method     Childauthentication_codeQuery leftJoinWithMember() Adds a LEFT JOIN clause and with to the query using the Member relation
 * @method     Childauthentication_codeQuery rightJoinWithMember() Adds a RIGHT JOIN clause and with to the query using the Member relation
 * @method     Childauthentication_codeQuery innerJoinWithMember() Adds a INNER JOIN clause and with to the query using the Member relation
 *
 * @method     \MemberQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     Childauthentication_code|null findOne(ConnectionInterface $con = null) Return the first Childauthentication_code matching the query
 * @method     Childauthentication_code findOneOrCreate(ConnectionInterface $con = null) Return the first Childauthentication_code matching the query, or a new Childauthentication_code object populated from the query conditions when no match is found
 *
 * @method     Childauthentication_code|null findOneById(int $id) Return the first Childauthentication_code filtered by the id column
 * @method     Childauthentication_code|null findOneByType(string $type) Return the first Childauthentication_code filtered by the type column
 * @method     Childauthentication_code|null findOneByCode(string $code) Return the first Childauthentication_code filtered by the code column
 * @method     Childauthentication_code|null findOneByTrialCount(int $trial_count) Return the first Childauthentication_code filtered by the trial_count column
 * @method     Childauthentication_code|null findOneByDateTime(string $date_time) Return the first Childauthentication_code filtered by the date_time column
 * @method     Childauthentication_code|null findOneByMemberId(int $member_id) Return the first Childauthentication_code filtered by the member_id column *

 * @method     Childauthentication_code requirePk($key, ConnectionInterface $con = null) Return the Childauthentication_code by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     Childauthentication_code requireOne(ConnectionInterface $con = null) Return the first Childauthentication_code matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     Childauthentication_code requireOneById(int $id) Return the first Childauthentication_code filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     Childauthentication_code requireOneByType(string $type) Return the first Childauthentication_code filtered by the type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     Childauthentication_code requireOneByCode(string $code) Return the first Childauthentication_code filtered by the code column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     Childauthentication_code requireOneByTrialCount(int $trial_count) Return the first Childauthentication_code filtered by the trial_count column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     Childauthentication_code requireOneByDateTime(string $date_time) Return the first Childauthentication_code filtered by the date_time column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     Childauthentication_code requireOneByMemberId(int $member_id) Return the first Childauthentication_code filtered by the member_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     Childauthentication_code[]|ObjectCollection find(ConnectionInterface $con = null) Return Childauthentication_code objects based on current ModelCriteria
 * @psalm-method ObjectCollection&\Traversable<Childauthentication_code> find(ConnectionInterface $con = null) Return Childauthentication_code objects based on current ModelCriteria
 * @method     Childauthentication_code[]|ObjectCollection findById(int $id) Return Childauthentication_code objects filtered by the id column
 * @psalm-method ObjectCollection&\Traversable<Childauthentication_code> findById(int $id) Return Childauthentication_code objects filtered by the id column
 * @method     Childauthentication_code[]|ObjectCollection findByType(string $type) Return Childauthentication_code objects filtered by the type column
 * @psalm-method ObjectCollection&\Traversable<Childauthentication_code> findByType(string $type) Return Childauthentication_code objects filtered by the type column
 * @method     Childauthentication_code[]|ObjectCollection findByCode(string $code) Return Childauthentication_code objects filtered by the code column
 * @psalm-method ObjectCollection&\Traversable<Childauthentication_code> findByCode(string $code) Return Childauthentication_code objects filtered by the code column
 * @method     Childauthentication_code[]|ObjectCollection findByTrialCount(int $trial_count) Return Childauthentication_code objects filtered by the trial_count column
 * @psalm-method ObjectCollection&\Traversable<Childauthentication_code> findByTrialCount(int $trial_count) Return Childauthentication_code objects filtered by the trial_count column
 * @method     Childauthentication_code[]|ObjectCollection findByDateTime(string $date_time) Return Childauthentication_code objects filtered by the date_time column
 * @psalm-method ObjectCollection&\Traversable<Childauthentication_code> findByDateTime(string $date_time) Return Childauthentication_code objects filtered by the date_time column
 * @method     Childauthentication_code[]|ObjectCollection findByMemberId(int $member_id) Return Childauthentication_code objects filtered by the member_id column
 * @psalm-method ObjectCollection&\Traversable<Childauthentication_code> findByMemberId(int $member_id) Return Childauthentication_code objects filtered by the member_id column
 * @method     Childauthentication_code[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<Childauthentication_code> paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class authentication_codeQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\authentication_codeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'whoo', $modelName = '\\authentication_code', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new Childauthentication_codeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return Childauthentication_codeQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof Childauthentication_codeQuery) {
            return $criteria;
        }
        $query = new Childauthentication_codeQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return Childauthentication_code|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(authentication_codeTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = authentication_codeTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return Childauthentication_code A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, type, code, trial_count, date_time, member_id FROM whoo_authentication_code WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var Childauthentication_code $obj */
            $obj = new Childauthentication_code();
            $obj->hydrate($row);
            authentication_codeTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return Childauthentication_code|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|Childauthentication_codeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(authentication_codeTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|Childauthentication_codeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(authentication_codeTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|Childauthentication_codeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(authentication_codeTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(authentication_codeTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(authentication_codeTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE type = 'fooValue'
     * $query->filterByType('%fooValue%', Criteria::LIKE); // WHERE type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|Childauthentication_codeQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(authentication_codeTableMap::COL_TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE code = 'fooValue'
     * $query->filterByCode('%fooValue%', Criteria::LIKE); // WHERE code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $code The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|Childauthentication_codeQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(authentication_codeTableMap::COL_CODE, $code, $comparison);
    }

    /**
     * Filter the query on the trial_count column
     *
     * Example usage:
     * <code>
     * $query->filterByTrialCount(1234); // WHERE trial_count = 1234
     * $query->filterByTrialCount(array(12, 34)); // WHERE trial_count IN (12, 34)
     * $query->filterByTrialCount(array('min' => 12)); // WHERE trial_count > 12
     * </code>
     *
     * @param     mixed $trialCount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|Childauthentication_codeQuery The current query, for fluid interface
     */
    public function filterByTrialCount($trialCount = null, $comparison = null)
    {
        if (is_array($trialCount)) {
            $useMinMax = false;
            if (isset($trialCount['min'])) {
                $this->addUsingAlias(authentication_codeTableMap::COL_TRIAL_COUNT, $trialCount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($trialCount['max'])) {
                $this->addUsingAlias(authentication_codeTableMap::COL_TRIAL_COUNT, $trialCount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(authentication_codeTableMap::COL_TRIAL_COUNT, $trialCount, $comparison);
    }

    /**
     * Filter the query on the date_time column
     *
     * Example usage:
     * <code>
     * $query->filterByDateTime('2011-03-14'); // WHERE date_time = '2011-03-14'
     * $query->filterByDateTime('now'); // WHERE date_time = '2011-03-14'
     * $query->filterByDateTime(array('max' => 'yesterday')); // WHERE date_time > '2011-03-13'
     * </code>
     *
     * @param     mixed $dateTime The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|Childauthentication_codeQuery The current query, for fluid interface
     */
    public function filterByDateTime($dateTime = null, $comparison = null)
    {
        if (is_array($dateTime)) {
            $useMinMax = false;
            if (isset($dateTime['min'])) {
                $this->addUsingAlias(authentication_codeTableMap::COL_DATE_TIME, $dateTime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateTime['max'])) {
                $this->addUsingAlias(authentication_codeTableMap::COL_DATE_TIME, $dateTime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(authentication_codeTableMap::COL_DATE_TIME, $dateTime, $comparison);
    }

    /**
     * Filter the query on the member_id column
     *
     * Example usage:
     * <code>
     * $query->filterByMemberId(1234); // WHERE member_id = 1234
     * $query->filterByMemberId(array(12, 34)); // WHERE member_id IN (12, 34)
     * $query->filterByMemberId(array('min' => 12)); // WHERE member_id > 12
     * </code>
     *
     * @see       filterByMember()
     *
     * @param     mixed $memberId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|Childauthentication_codeQuery The current query, for fluid interface
     */
    public function filterByMemberId($memberId = null, $comparison = null)
    {
        if (is_array($memberId)) {
            $useMinMax = false;
            if (isset($memberId['min'])) {
                $this->addUsingAlias(authentication_codeTableMap::COL_MEMBER_ID, $memberId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($memberId['max'])) {
                $this->addUsingAlias(authentication_codeTableMap::COL_MEMBER_ID, $memberId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(authentication_codeTableMap::COL_MEMBER_ID, $memberId, $comparison);
    }

    /**
     * Filter the query by a related \Member object
     *
     * @param \Member|ObjectCollection $member The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return Childauthentication_codeQuery The current query, for fluid interface
     */
    public function filterByMember($member, $comparison = null)
    {
        if ($member instanceof \Member) {
            return $this
                ->addUsingAlias(authentication_codeTableMap::COL_MEMBER_ID, $member->getId(), $comparison);
        } elseif ($member instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(authentication_codeTableMap::COL_MEMBER_ID, $member->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByMember() only accepts arguments of type \Member or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Member relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|Childauthentication_codeQuery The current query, for fluid interface
     */
    public function joinMember($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Member');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Member');
        }

        return $this;
    }

    /**
     * Use the Member relation Member object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \MemberQuery A secondary query class using the current class as primary query
     */
    public function useMemberQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMember($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Member', '\MemberQuery');
    }

    /**
     * Use the Member relation Member object
     *
     * @param callable(\MemberQuery):\MemberQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withMemberQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useMemberQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }
    /**
     * Use the relation to Member table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string $typeOfExists Either ExistsCriterion::TYPE_EXISTS or ExistsCriterion::TYPE_NOT_EXISTS
     *
     * @return \MemberQuery The inner query object of the EXISTS statement
     */
    public function useMemberExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        return $this->useExistsQuery('Member', $modelAlias, $queryClass, $typeOfExists);
    }

    /**
     * Use the relation to Member table for a NOT EXISTS query.
     *
     * @see useMemberExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \MemberQuery The inner query object of the NOT EXISTS statement
     */
    public function useMemberNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        return $this->useExistsQuery('Member', $modelAlias, $queryClass, 'NOT EXISTS');
    }
    /**
     * Exclude object from result
     *
     * @param   Childauthentication_code $authentication_code Object to remove from the list of results
     *
     * @return $this|Childauthentication_codeQuery The current query, for fluid interface
     */
    public function prune($authentication_code = null)
    {
        if ($authentication_code) {
            $this->addUsingAlias(authentication_codeTableMap::COL_ID, $authentication_code->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the whoo_authentication_code table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(authentication_codeTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            authentication_codeTableMap::clearInstancePool();
            authentication_codeTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(authentication_codeTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(authentication_codeTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            authentication_codeTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            authentication_codeTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // authentication_codeQuery
