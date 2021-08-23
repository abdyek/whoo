<?php

namespace Base;

use \User as ChildUser;
use \UserQuery as ChildUserQuery;
use \Exception;
use \PDO;
use Map\UserTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'whoo_user' table.
 *
 *
 *
 * @method     ChildUserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildUserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildUserQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method     ChildUserQuery orderByPasswordHash($order = Criteria::ASC) Order by the password_hash column
 * @method     ChildUserQuery orderByEmailVerified($order = Criteria::ASC) Order by the email_verified column
 * @method     ChildUserQuery orderBySignUpDateTime($order = Criteria::ASC) Order by the sign_up_date_time column
 * @method     ChildUserQuery orderBySignOutCount($order = Criteria::ASC) Order by the sign_out_count column
 * @method     ChildUserQuery orderByProvider($order = Criteria::ASC) Order by the provider column
 * @method     ChildUserQuery orderByProviderId($order = Criteria::ASC) Order by the provider_id column
 * @method     ChildUserQuery orderByTwoFactorAuthentication($order = Criteria::ASC) Order by the two_factor_authentication column
 *
 * @method     ChildUserQuery groupById() Group by the id column
 * @method     ChildUserQuery groupByEmail() Group by the email column
 * @method     ChildUserQuery groupByUsername() Group by the username column
 * @method     ChildUserQuery groupByPasswordHash() Group by the password_hash column
 * @method     ChildUserQuery groupByEmailVerified() Group by the email_verified column
 * @method     ChildUserQuery groupBySignUpDateTime() Group by the sign_up_date_time column
 * @method     ChildUserQuery groupBySignOutCount() Group by the sign_out_count column
 * @method     ChildUserQuery groupByProvider() Group by the provider column
 * @method     ChildUserQuery groupByProviderId() Group by the provider_id column
 * @method     ChildUserQuery groupByTwoFactorAuthentication() Group by the two_factor_authentication column
 *
 * @method     ChildUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUserQuery leftJoinAuthenticationCode($relationAlias = null) Adds a LEFT JOIN clause to the query using the AuthenticationCode relation
 * @method     ChildUserQuery rightJoinAuthenticationCode($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AuthenticationCode relation
 * @method     ChildUserQuery innerJoinAuthenticationCode($relationAlias = null) Adds a INNER JOIN clause to the query using the AuthenticationCode relation
 *
 * @method     ChildUserQuery joinWithAuthenticationCode($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AuthenticationCode relation
 *
 * @method     ChildUserQuery leftJoinWithAuthenticationCode() Adds a LEFT JOIN clause and with to the query using the AuthenticationCode relation
 * @method     ChildUserQuery rightJoinWithAuthenticationCode() Adds a RIGHT JOIN clause and with to the query using the AuthenticationCode relation
 * @method     ChildUserQuery innerJoinWithAuthenticationCode() Adds a INNER JOIN clause and with to the query using the AuthenticationCode relation
 *
 * @method     \AuthenticationCodeQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUser|null findOne(ConnectionInterface $con = null) Return the first ChildUser matching the query
 * @method     ChildUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUser matching the query, or a new ChildUser object populated from the query conditions when no match is found
 *
 * @method     ChildUser|null findOneById(int $id) Return the first ChildUser filtered by the id column
 * @method     ChildUser|null findOneByEmail(string $email) Return the first ChildUser filtered by the email column
 * @method     ChildUser|null findOneByUsername(string $username) Return the first ChildUser filtered by the username column
 * @method     ChildUser|null findOneByPasswordHash(string $password_hash) Return the first ChildUser filtered by the password_hash column
 * @method     ChildUser|null findOneByEmailVerified(boolean $email_verified) Return the first ChildUser filtered by the email_verified column
 * @method     ChildUser|null findOneBySignUpDateTime(string $sign_up_date_time) Return the first ChildUser filtered by the sign_up_date_time column
 * @method     ChildUser|null findOneBySignOutCount(int $sign_out_count) Return the first ChildUser filtered by the sign_out_count column
 * @method     ChildUser|null findOneByProvider(string $provider) Return the first ChildUser filtered by the provider column
 * @method     ChildUser|null findOneByProviderId(string $provider_id) Return the first ChildUser filtered by the provider_id column
 * @method     ChildUser|null findOneByTwoFactorAuthentication(boolean $two_factor_authentication) Return the first ChildUser filtered by the two_factor_authentication column *

 * @method     ChildUser requirePk($key, ConnectionInterface $con = null) Return the ChildUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOne(ConnectionInterface $con = null) Return the first ChildUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser requireOneById(int $id) Return the first ChildUser filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByEmail(string $email) Return the first ChildUser filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByUsername(string $username) Return the first ChildUser filtered by the username column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPasswordHash(string $password_hash) Return the first ChildUser filtered by the password_hash column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByEmailVerified(boolean $email_verified) Return the first ChildUser filtered by the email_verified column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneBySignUpDateTime(string $sign_up_date_time) Return the first ChildUser filtered by the sign_up_date_time column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneBySignOutCount(int $sign_out_count) Return the first ChildUser filtered by the sign_out_count column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByProvider(string $provider) Return the first ChildUser filtered by the provider column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByProviderId(string $provider_id) Return the first ChildUser filtered by the provider_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByTwoFactorAuthentication(boolean $two_factor_authentication) Return the first ChildUser filtered by the two_factor_authentication column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 * @psalm-method ObjectCollection&\Traversable<ChildUser> find(ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 * @method     ChildUser[]|ObjectCollection findById(int $id) Return ChildUser objects filtered by the id column
 * @psalm-method ObjectCollection&\Traversable<ChildUser> findById(int $id) Return ChildUser objects filtered by the id column
 * @method     ChildUser[]|ObjectCollection findByEmail(string $email) Return ChildUser objects filtered by the email column
 * @psalm-method ObjectCollection&\Traversable<ChildUser> findByEmail(string $email) Return ChildUser objects filtered by the email column
 * @method     ChildUser[]|ObjectCollection findByUsername(string $username) Return ChildUser objects filtered by the username column
 * @psalm-method ObjectCollection&\Traversable<ChildUser> findByUsername(string $username) Return ChildUser objects filtered by the username column
 * @method     ChildUser[]|ObjectCollection findByPasswordHash(string $password_hash) Return ChildUser objects filtered by the password_hash column
 * @psalm-method ObjectCollection&\Traversable<ChildUser> findByPasswordHash(string $password_hash) Return ChildUser objects filtered by the password_hash column
 * @method     ChildUser[]|ObjectCollection findByEmailVerified(boolean $email_verified) Return ChildUser objects filtered by the email_verified column
 * @psalm-method ObjectCollection&\Traversable<ChildUser> findByEmailVerified(boolean $email_verified) Return ChildUser objects filtered by the email_verified column
 * @method     ChildUser[]|ObjectCollection findBySignUpDateTime(string $sign_up_date_time) Return ChildUser objects filtered by the sign_up_date_time column
 * @psalm-method ObjectCollection&\Traversable<ChildUser> findBySignUpDateTime(string $sign_up_date_time) Return ChildUser objects filtered by the sign_up_date_time column
 * @method     ChildUser[]|ObjectCollection findBySignOutCount(int $sign_out_count) Return ChildUser objects filtered by the sign_out_count column
 * @psalm-method ObjectCollection&\Traversable<ChildUser> findBySignOutCount(int $sign_out_count) Return ChildUser objects filtered by the sign_out_count column
 * @method     ChildUser[]|ObjectCollection findByProvider(string $provider) Return ChildUser objects filtered by the provider column
 * @psalm-method ObjectCollection&\Traversable<ChildUser> findByProvider(string $provider) Return ChildUser objects filtered by the provider column
 * @method     ChildUser[]|ObjectCollection findByProviderId(string $provider_id) Return ChildUser objects filtered by the provider_id column
 * @psalm-method ObjectCollection&\Traversable<ChildUser> findByProviderId(string $provider_id) Return ChildUser objects filtered by the provider_id column
 * @method     ChildUser[]|ObjectCollection findByTwoFactorAuthentication(boolean $two_factor_authentication) Return ChildUser objects filtered by the two_factor_authentication column
 * @psalm-method ObjectCollection&\Traversable<ChildUser> findByTwoFactorAuthentication(boolean $two_factor_authentication) Return ChildUser objects filtered by the two_factor_authentication column
 * @method     ChildUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildUser> paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\UserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'whoo', $modelName = '\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserQuery) {
            return $criteria;
        }
        $query = new ChildUserQuery();
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UserTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, email, username, password_hash, email_verified, sign_up_date_time, sign_out_count, provider, provider_id, two_factor_authentication FROM whoo_user WHERE id = :p0';
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
            /** @var ChildUser $obj */
            $obj = new ChildUser();
            $obj->hydrate($row);
            UserTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_EMAIL, $email, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByUsername($username = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_USERNAME, $username, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPasswordHash($passwordHash = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($passwordHash)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PASSWORD_HASH, $passwordHash, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByEmailVerified($emailVerified = null, $comparison = null)
    {
        if (is_string($emailVerified)) {
            $emailVerified = in_array(strtolower($emailVerified), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserTableMap::COL_EMAIL_VERIFIED, $emailVerified, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterBySignUpDateTime($signUpDateTime = null, $comparison = null)
    {
        if (is_array($signUpDateTime)) {
            $useMinMax = false;
            if (isset($signUpDateTime['min'])) {
                $this->addUsingAlias(UserTableMap::COL_SIGN_UP_DATE_TIME, $signUpDateTime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($signUpDateTime['max'])) {
                $this->addUsingAlias(UserTableMap::COL_SIGN_UP_DATE_TIME, $signUpDateTime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_SIGN_UP_DATE_TIME, $signUpDateTime, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterBySignOutCount($signOutCount = null, $comparison = null)
    {
        if (is_array($signOutCount)) {
            $useMinMax = false;
            if (isset($signOutCount['min'])) {
                $this->addUsingAlias(UserTableMap::COL_SIGN_OUT_COUNT, $signOutCount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($signOutCount['max'])) {
                $this->addUsingAlias(UserTableMap::COL_SIGN_OUT_COUNT, $signOutCount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_SIGN_OUT_COUNT, $signOutCount, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByProvider($provider = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($provider)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PROVIDER, $provider, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByProviderId($providerId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($providerId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PROVIDER_ID, $providerId, $comparison);
    }

    /**
     * Filter the query on the two_factor_authentication column
     *
     * Example usage:
     * <code>
     * $query->filterByTwoFactorAuthentication(true); // WHERE two_factor_authentication = true
     * $query->filterByTwoFactorAuthentication('yes'); // WHERE two_factor_authentication = true
     * </code>
     *
     * @param     boolean|string $twoFactorAuthentication The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByTwoFactorAuthentication($twoFactorAuthentication = null, $comparison = null)
    {
        if (is_string($twoFactorAuthentication)) {
            $twoFactorAuthentication = in_array(strtolower($twoFactorAuthentication), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserTableMap::COL_TWO_FACTOR_AUTHENTICATION, $twoFactorAuthentication, $comparison);
    }

    /**
     * Filter the query by a related \AuthenticationCode object
     *
     * @param \AuthenticationCode|ObjectCollection $authenticationCode the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByAuthenticationCode($authenticationCode, $comparison = null)
    {
        if ($authenticationCode instanceof \AuthenticationCode) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $authenticationCode->getUserId(), $comparison);
        } elseif ($authenticationCode instanceof ObjectCollection) {
            return $this
                ->useAuthenticationCodeQuery()
                ->filterByPrimaryKeys($authenticationCode->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAuthenticationCode() only accepts arguments of type \AuthenticationCode or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AuthenticationCode relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinAuthenticationCode($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AuthenticationCode');

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
            $this->addJoinObject($join, 'AuthenticationCode');
        }

        return $this;
    }

    /**
     * Use the AuthenticationCode relation AuthenticationCode object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \AuthenticationCodeQuery A secondary query class using the current class as primary query
     */
    public function useAuthenticationCodeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAuthenticationCode($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AuthenticationCode', '\AuthenticationCodeQuery');
    }

    /**
     * Use the AuthenticationCode relation AuthenticationCode object
     *
     * @param callable(\AuthenticationCodeQuery):\AuthenticationCodeQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withAuthenticationCodeQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useAuthenticationCodeQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }
    /**
     * Use the relation to AuthenticationCode table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string $typeOfExists Either ExistsCriterion::TYPE_EXISTS or ExistsCriterion::TYPE_NOT_EXISTS
     *
     * @return \AuthenticationCodeQuery The inner query object of the EXISTS statement
     */
    public function useAuthenticationCodeExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        return $this->useExistsQuery('AuthenticationCode', $modelAlias, $queryClass, $typeOfExists);
    }

    /**
     * Use the relation to AuthenticationCode table for a NOT EXISTS query.
     *
     * @see useAuthenticationCodeExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \AuthenticationCodeQuery The inner query object of the NOT EXISTS statement
     */
    public function useAuthenticationCodeNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        return $this->useExistsQuery('AuthenticationCode', $modelAlias, $queryClass, 'NOT EXISTS');
    }
    /**
     * Exclude object from result
     *
     * @param   ChildUser $user Object to remove from the list of results
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserTableMap::COL_ID, $user->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the whoo_user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserTableMap::clearInstancePool();
            UserTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // UserQuery
