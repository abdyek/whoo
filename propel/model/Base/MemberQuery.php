<?php

namespace Base;

use \Member as ChildMember;
use \MemberQuery as ChildMemberQuery;
use \Exception;
use \PDO;
use Map\MemberTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'whoo_member' table.
 *
 *
 *
 * @method     ChildMemberQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMemberQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildMemberQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method     ChildMemberQuery orderByPasswordHash($order = Criteria::ASC) Order by the password_hash column
 * @method     ChildMemberQuery orderByEmailVerified($order = Criteria::ASC) Order by the email_verified column
 * @method     ChildMemberQuery orderBySignUpDateTime($order = Criteria::ASC) Order by the sign_up_date_time column
 * @method     ChildMemberQuery orderBySignOutCount($order = Criteria::ASC) Order by the sign_out_count column
 * @method     ChildMemberQuery orderByProvider($order = Criteria::ASC) Order by the provider column
 * @method     ChildMemberQuery orderByProviderId($order = Criteria::ASC) Order by the provider_id column
 *
 * @method     ChildMemberQuery groupById() Group by the id column
 * @method     ChildMemberQuery groupByEmail() Group by the email column
 * @method     ChildMemberQuery groupByUsername() Group by the username column
 * @method     ChildMemberQuery groupByPasswordHash() Group by the password_hash column
 * @method     ChildMemberQuery groupByEmailVerified() Group by the email_verified column
 * @method     ChildMemberQuery groupBySignUpDateTime() Group by the sign_up_date_time column
 * @method     ChildMemberQuery groupBySignOutCount() Group by the sign_out_count column
 * @method     ChildMemberQuery groupByProvider() Group by the provider column
 * @method     ChildMemberQuery groupByProviderId() Group by the provider_id column
 *
 * @method     ChildMemberQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMemberQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMemberQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMemberQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMemberQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMemberQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMemberQuery leftJoinauthentication_code($relationAlias = null) Adds a LEFT JOIN clause to the query using the authentication_code relation
 * @method     ChildMemberQuery rightJoinauthentication_code($relationAlias = null) Adds a RIGHT JOIN clause to the query using the authentication_code relation
 * @method     ChildMemberQuery innerJoinauthentication_code($relationAlias = null) Adds a INNER JOIN clause to the query using the authentication_code relation
 *
 * @method     ChildMemberQuery joinWithauthentication_code($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the authentication_code relation
 *
 * @method     ChildMemberQuery leftJoinWithauthentication_code() Adds a LEFT JOIN clause and with to the query using the authentication_code relation
 * @method     ChildMemberQuery rightJoinWithauthentication_code() Adds a RIGHT JOIN clause and with to the query using the authentication_code relation
 * @method     ChildMemberQuery innerJoinWithauthentication_code() Adds a INNER JOIN clause and with to the query using the authentication_code relation
 *
 * @method     \authentication_codeQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMember|null findOne(ConnectionInterface $con = null) Return the first ChildMember matching the query
 * @method     ChildMember findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMember matching the query, or a new ChildMember object populated from the query conditions when no match is found
 *
 * @method     ChildMember|null findOneById(int $id) Return the first ChildMember filtered by the id column
 * @method     ChildMember|null findOneByEmail(string $email) Return the first ChildMember filtered by the email column
 * @method     ChildMember|null findOneByUsername(string $username) Return the first ChildMember filtered by the username column
 * @method     ChildMember|null findOneByPasswordHash(string $password_hash) Return the first ChildMember filtered by the password_hash column
 * @method     ChildMember|null findOneByEmailVerified(boolean $email_verified) Return the first ChildMember filtered by the email_verified column
 * @method     ChildMember|null findOneBySignUpDateTime(string $sign_up_date_time) Return the first ChildMember filtered by the sign_up_date_time column
 * @method     ChildMember|null findOneBySignOutCount(int $sign_out_count) Return the first ChildMember filtered by the sign_out_count column
 * @method     ChildMember|null findOneByProvider(string $provider) Return the first ChildMember filtered by the provider column
 * @method     ChildMember|null findOneByProviderId(string $provider_id) Return the first ChildMember filtered by the provider_id column *

 * @method     ChildMember requirePk($key, ConnectionInterface $con = null) Return the ChildMember by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMember requireOne(ConnectionInterface $con = null) Return the first ChildMember matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMember requireOneById(int $id) Return the first ChildMember filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMember requireOneByEmail(string $email) Return the first ChildMember filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMember requireOneByUsername(string $username) Return the first ChildMember filtered by the username column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMember requireOneByPasswordHash(string $password_hash) Return the first ChildMember filtered by the password_hash column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMember requireOneByEmailVerified(boolean $email_verified) Return the first ChildMember filtered by the email_verified column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMember requireOneBySignUpDateTime(string $sign_up_date_time) Return the first ChildMember filtered by the sign_up_date_time column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMember requireOneBySignOutCount(int $sign_out_count) Return the first ChildMember filtered by the sign_out_count column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMember requireOneByProvider(string $provider) Return the first ChildMember filtered by the provider column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMember requireOneByProviderId(string $provider_id) Return the first ChildMember filtered by the provider_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMember[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMember objects based on current ModelCriteria
 * @psalm-method ObjectCollection&\Traversable<ChildMember> find(ConnectionInterface $con = null) Return ChildMember objects based on current ModelCriteria
 * @method     ChildMember[]|ObjectCollection findById(int $id) Return ChildMember objects filtered by the id column
 * @psalm-method ObjectCollection&\Traversable<ChildMember> findById(int $id) Return ChildMember objects filtered by the id column
 * @method     ChildMember[]|ObjectCollection findByEmail(string $email) Return ChildMember objects filtered by the email column
 * @psalm-method ObjectCollection&\Traversable<ChildMember> findByEmail(string $email) Return ChildMember objects filtered by the email column
 * @method     ChildMember[]|ObjectCollection findByUsername(string $username) Return ChildMember objects filtered by the username column
 * @psalm-method ObjectCollection&\Traversable<ChildMember> findByUsername(string $username) Return ChildMember objects filtered by the username column
 * @method     ChildMember[]|ObjectCollection findByPasswordHash(string $password_hash) Return ChildMember objects filtered by the password_hash column
 * @psalm-method ObjectCollection&\Traversable<ChildMember> findByPasswordHash(string $password_hash) Return ChildMember objects filtered by the password_hash column
 * @method     ChildMember[]|ObjectCollection findByEmailVerified(boolean $email_verified) Return ChildMember objects filtered by the email_verified column
 * @psalm-method ObjectCollection&\Traversable<ChildMember> findByEmailVerified(boolean $email_verified) Return ChildMember objects filtered by the email_verified column
 * @method     ChildMember[]|ObjectCollection findBySignUpDateTime(string $sign_up_date_time) Return ChildMember objects filtered by the sign_up_date_time column
 * @psalm-method ObjectCollection&\Traversable<ChildMember> findBySignUpDateTime(string $sign_up_date_time) Return ChildMember objects filtered by the sign_up_date_time column
 * @method     ChildMember[]|ObjectCollection findBySignOutCount(int $sign_out_count) Return ChildMember objects filtered by the sign_out_count column
 * @psalm-method ObjectCollection&\Traversable<ChildMember> findBySignOutCount(int $sign_out_count) Return ChildMember objects filtered by the sign_out_count column
 * @method     ChildMember[]|ObjectCollection findByProvider(string $provider) Return ChildMember objects filtered by the provider column
 * @psalm-method ObjectCollection&\Traversable<ChildMember> findByProvider(string $provider) Return ChildMember objects filtered by the provider column
 * @method     ChildMember[]|ObjectCollection findByProviderId(string $provider_id) Return ChildMember objects filtered by the provider_id column
 * @psalm-method ObjectCollection&\Traversable<ChildMember> findByProviderId(string $provider_id) Return ChildMember objects filtered by the provider_id column
 * @method     ChildMember[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildMember> paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MemberQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\MemberQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'whoo', $modelName = '\\Member', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMemberQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMemberQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMemberQuery) {
            return $criteria;
        }
        $query = new ChildMemberQuery();
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
     * @return ChildMember|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MemberTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MemberTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildMember A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, email, username, password_hash, email_verified, sign_up_date_time, sign_out_count, provider, provider_id FROM whoo_member WHERE id = :p0';
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
            /** @var ChildMember $obj */
            $obj = new ChildMember();
            $obj->hydrate($row);
            MemberTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildMember|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMemberQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MemberTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMemberQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MemberTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildMemberQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MemberTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MemberTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MemberTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMemberQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MemberTableMap::COL_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the username column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE username = 'fooValue'
     * $query->filterByUsername('%fooValue%', Criteria::LIKE); // WHERE username LIKE '%fooValue%'
     * </code>
     *
     * @param     string $username The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMemberQuery The current query, for fluid interface
     */
    public function filterByUsername($username = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MemberTableMap::COL_USERNAME, $username, $comparison);
    }

    /**
     * Filter the query on the password_hash column
     *
     * Example usage:
     * <code>
     * $query->filterByPasswordHash('fooValue');   // WHERE password_hash = 'fooValue'
     * $query->filterByPasswordHash('%fooValue%', Criteria::LIKE); // WHERE password_hash LIKE '%fooValue%'
     * </code>
     *
     * @param     string $passwordHash The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMemberQuery The current query, for fluid interface
     */
    public function filterByPasswordHash($passwordHash = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($passwordHash)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MemberTableMap::COL_PASSWORD_HASH, $passwordHash, $comparison);
    }

    /**
     * Filter the query on the email_verified column
     *
     * Example usage:
     * <code>
     * $query->filterByEmailVerified(true); // WHERE email_verified = true
     * $query->filterByEmailVerified('yes'); // WHERE email_verified = true
     * </code>
     *
     * @param     boolean|string $emailVerified The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMemberQuery The current query, for fluid interface
     */
    public function filterByEmailVerified($emailVerified = null, $comparison = null)
    {
        if (is_string($emailVerified)) {
            $emailVerified = in_array(strtolower($emailVerified), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(MemberTableMap::COL_EMAIL_VERIFIED, $emailVerified, $comparison);
    }

    /**
     * Filter the query on the sign_up_date_time column
     *
     * Example usage:
     * <code>
     * $query->filterBySignUpDateTime('2011-03-14'); // WHERE sign_up_date_time = '2011-03-14'
     * $query->filterBySignUpDateTime('now'); // WHERE sign_up_date_time = '2011-03-14'
     * $query->filterBySignUpDateTime(array('max' => 'yesterday')); // WHERE sign_up_date_time > '2011-03-13'
     * </code>
     *
     * @param     mixed $signUpDateTime The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMemberQuery The current query, for fluid interface
     */
    public function filterBySignUpDateTime($signUpDateTime = null, $comparison = null)
    {
        if (is_array($signUpDateTime)) {
            $useMinMax = false;
            if (isset($signUpDateTime['min'])) {
                $this->addUsingAlias(MemberTableMap::COL_SIGN_UP_DATE_TIME, $signUpDateTime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($signUpDateTime['max'])) {
                $this->addUsingAlias(MemberTableMap::COL_SIGN_UP_DATE_TIME, $signUpDateTime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MemberTableMap::COL_SIGN_UP_DATE_TIME, $signUpDateTime, $comparison);
    }

    /**
     * Filter the query on the sign_out_count column
     *
     * Example usage:
     * <code>
     * $query->filterBySignOutCount(1234); // WHERE sign_out_count = 1234
     * $query->filterBySignOutCount(array(12, 34)); // WHERE sign_out_count IN (12, 34)
     * $query->filterBySignOutCount(array('min' => 12)); // WHERE sign_out_count > 12
     * </code>
     *
     * @param     mixed $signOutCount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMemberQuery The current query, for fluid interface
     */
    public function filterBySignOutCount($signOutCount = null, $comparison = null)
    {
        if (is_array($signOutCount)) {
            $useMinMax = false;
            if (isset($signOutCount['min'])) {
                $this->addUsingAlias(MemberTableMap::COL_SIGN_OUT_COUNT, $signOutCount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($signOutCount['max'])) {
                $this->addUsingAlias(MemberTableMap::COL_SIGN_OUT_COUNT, $signOutCount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MemberTableMap::COL_SIGN_OUT_COUNT, $signOutCount, $comparison);
    }

    /**
     * Filter the query on the provider column
     *
     * Example usage:
     * <code>
     * $query->filterByProvider('fooValue');   // WHERE provider = 'fooValue'
     * $query->filterByProvider('%fooValue%', Criteria::LIKE); // WHERE provider LIKE '%fooValue%'
     * </code>
     *
     * @param     string $provider The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMemberQuery The current query, for fluid interface
     */
    public function filterByProvider($provider = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($provider)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MemberTableMap::COL_PROVIDER, $provider, $comparison);
    }

    /**
     * Filter the query on the provider_id column
     *
     * Example usage:
     * <code>
     * $query->filterByProviderId('fooValue');   // WHERE provider_id = 'fooValue'
     * $query->filterByProviderId('%fooValue%', Criteria::LIKE); // WHERE provider_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $providerId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMemberQuery The current query, for fluid interface
     */
    public function filterByProviderId($providerId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($providerId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MemberTableMap::COL_PROVIDER_ID, $providerId, $comparison);
    }

    /**
     * Filter the query by a related \authentication_code object
     *
     * @param \authentication_code|ObjectCollection $authentication_code the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMemberQuery The current query, for fluid interface
     */
    public function filterByauthentication_code($authentication_code, $comparison = null)
    {
        if ($authentication_code instanceof \authentication_code) {
            return $this
                ->addUsingAlias(MemberTableMap::COL_ID, $authentication_code->getMemberId(), $comparison);
        } elseif ($authentication_code instanceof ObjectCollection) {
            return $this
                ->useauthentication_codeQuery()
                ->filterByPrimaryKeys($authentication_code->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByauthentication_code() only accepts arguments of type \authentication_code or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the authentication_code relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMemberQuery The current query, for fluid interface
     */
    public function joinauthentication_code($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('authentication_code');

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
            $this->addJoinObject($join, 'authentication_code');
        }

        return $this;
    }

    /**
     * Use the authentication_code relation authentication_code object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \authentication_codeQuery A secondary query class using the current class as primary query
     */
    public function useauthentication_codeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinauthentication_code($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'authentication_code', '\authentication_codeQuery');
    }

    /**
     * Use the authentication_code relation authentication_code object
     *
     * @param callable(\authentication_codeQuery):\authentication_codeQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withauthentication_codeQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useauthentication_codeQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }
    /**
     * Use the relation to authentication_code table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string $typeOfExists Either ExistsCriterion::TYPE_EXISTS or ExistsCriterion::TYPE_NOT_EXISTS
     *
     * @return \authentication_codeQuery The inner query object of the EXISTS statement
     */
    public function useauthentication_codeExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        return $this->useExistsQuery('authentication_code', $modelAlias, $queryClass, $typeOfExists);
    }

    /**
     * Use the relation to authentication_code table for a NOT EXISTS query.
     *
     * @see useauthentication_codeExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \authentication_codeQuery The inner query object of the NOT EXISTS statement
     */
    public function useauthentication_codeNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        return $this->useExistsQuery('authentication_code', $modelAlias, $queryClass, 'NOT EXISTS');
    }
    /**
     * Exclude object from result
     *
     * @param   ChildMember $member Object to remove from the list of results
     *
     * @return $this|ChildMemberQuery The current query, for fluid interface
     */
    public function prune($member = null)
    {
        if ($member) {
            $this->addUsingAlias(MemberTableMap::COL_ID, $member->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the whoo_member table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MemberTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MemberTableMap::clearInstancePool();
            MemberTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MemberTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MemberTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MemberTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MemberTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MemberQuery
