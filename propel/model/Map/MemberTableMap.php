<?php

namespace Map;

use \Member;
use \MemberQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'whoo_member' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class MemberTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.MemberTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'whoo';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'whoo_member';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Member';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Member';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the id field
     */
    const COL_ID = 'whoo_member.id';

    /**
     * the column name for the email field
     */
    const COL_EMAIL = 'whoo_member.email';

    /**
     * the column name for the username field
     */
    const COL_USERNAME = 'whoo_member.username';

    /**
     * the column name for the password_hash field
     */
    const COL_PASSWORD_HASH = 'whoo_member.password_hash';

    /**
     * the column name for the email_verified field
     */
    const COL_EMAIL_VERIFIED = 'whoo_member.email_verified';

    /**
     * the column name for the sign_up_date_time field
     */
    const COL_SIGN_UP_DATE_TIME = 'whoo_member.sign_up_date_time';

    /**
     * the column name for the sign_out_count field
     */
    const COL_SIGN_OUT_COUNT = 'whoo_member.sign_out_count';

    /**
     * the column name for the provider field
     */
    const COL_PROVIDER = 'whoo_member.provider';

    /**
     * the column name for the provider_id field
     */
    const COL_PROVIDER_ID = 'whoo_member.provider_id';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Email', 'Username', 'PasswordHash', 'EmailVerified', 'SignUpDateTime', 'SignOutCount', 'Provider', 'ProviderId', ),
        self::TYPE_CAMELNAME     => array('id', 'email', 'username', 'passwordHash', 'emailVerified', 'signUpDateTime', 'signOutCount', 'provider', 'providerId', ),
        self::TYPE_COLNAME       => array(MemberTableMap::COL_ID, MemberTableMap::COL_EMAIL, MemberTableMap::COL_USERNAME, MemberTableMap::COL_PASSWORD_HASH, MemberTableMap::COL_EMAIL_VERIFIED, MemberTableMap::COL_SIGN_UP_DATE_TIME, MemberTableMap::COL_SIGN_OUT_COUNT, MemberTableMap::COL_PROVIDER, MemberTableMap::COL_PROVIDER_ID, ),
        self::TYPE_FIELDNAME     => array('id', 'email', 'username', 'password_hash', 'email_verified', 'sign_up_date_time', 'sign_out_count', 'provider', 'provider_id', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Email' => 1, 'Username' => 2, 'PasswordHash' => 3, 'EmailVerified' => 4, 'SignUpDateTime' => 5, 'SignOutCount' => 6, 'Provider' => 7, 'ProviderId' => 8, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'email' => 1, 'username' => 2, 'passwordHash' => 3, 'emailVerified' => 4, 'signUpDateTime' => 5, 'signOutCount' => 6, 'provider' => 7, 'providerId' => 8, ),
        self::TYPE_COLNAME       => array(MemberTableMap::COL_ID => 0, MemberTableMap::COL_EMAIL => 1, MemberTableMap::COL_USERNAME => 2, MemberTableMap::COL_PASSWORD_HASH => 3, MemberTableMap::COL_EMAIL_VERIFIED => 4, MemberTableMap::COL_SIGN_UP_DATE_TIME => 5, MemberTableMap::COL_SIGN_OUT_COUNT => 6, MemberTableMap::COL_PROVIDER => 7, MemberTableMap::COL_PROVIDER_ID => 8, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'email' => 1, 'username' => 2, 'password_hash' => 3, 'email_verified' => 4, 'sign_up_date_time' => 5, 'sign_out_count' => 6, 'provider' => 7, 'provider_id' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'Member.Id' => 'ID',
        'id' => 'ID',
        'member.id' => 'ID',
        'MemberTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'whoo_member.id' => 'ID',
        'Email' => 'EMAIL',
        'Member.Email' => 'EMAIL',
        'email' => 'EMAIL',
        'member.email' => 'EMAIL',
        'MemberTableMap::COL_EMAIL' => 'EMAIL',
        'COL_EMAIL' => 'EMAIL',
        'whoo_member.email' => 'EMAIL',
        'Username' => 'USERNAME',
        'Member.Username' => 'USERNAME',
        'username' => 'USERNAME',
        'member.username' => 'USERNAME',
        'MemberTableMap::COL_USERNAME' => 'USERNAME',
        'COL_USERNAME' => 'USERNAME',
        'whoo_member.username' => 'USERNAME',
        'PasswordHash' => 'PASSWORD_HASH',
        'Member.PasswordHash' => 'PASSWORD_HASH',
        'passwordHash' => 'PASSWORD_HASH',
        'member.passwordHash' => 'PASSWORD_HASH',
        'MemberTableMap::COL_PASSWORD_HASH' => 'PASSWORD_HASH',
        'COL_PASSWORD_HASH' => 'PASSWORD_HASH',
        'password_hash' => 'PASSWORD_HASH',
        'whoo_member.password_hash' => 'PASSWORD_HASH',
        'EmailVerified' => 'EMAIL_VERIFIED',
        'Member.EmailVerified' => 'EMAIL_VERIFIED',
        'emailVerified' => 'EMAIL_VERIFIED',
        'member.emailVerified' => 'EMAIL_VERIFIED',
        'MemberTableMap::COL_EMAIL_VERIFIED' => 'EMAIL_VERIFIED',
        'COL_EMAIL_VERIFIED' => 'EMAIL_VERIFIED',
        'email_verified' => 'EMAIL_VERIFIED',
        'whoo_member.email_verified' => 'EMAIL_VERIFIED',
        'SignUpDateTime' => 'SIGN_UP_DATE_TIME',
        'Member.SignUpDateTime' => 'SIGN_UP_DATE_TIME',
        'signUpDateTime' => 'SIGN_UP_DATE_TIME',
        'member.signUpDateTime' => 'SIGN_UP_DATE_TIME',
        'MemberTableMap::COL_SIGN_UP_DATE_TIME' => 'SIGN_UP_DATE_TIME',
        'COL_SIGN_UP_DATE_TIME' => 'SIGN_UP_DATE_TIME',
        'sign_up_date_time' => 'SIGN_UP_DATE_TIME',
        'whoo_member.sign_up_date_time' => 'SIGN_UP_DATE_TIME',
        'SignOutCount' => 'SIGN_OUT_COUNT',
        'Member.SignOutCount' => 'SIGN_OUT_COUNT',
        'signOutCount' => 'SIGN_OUT_COUNT',
        'member.signOutCount' => 'SIGN_OUT_COUNT',
        'MemberTableMap::COL_SIGN_OUT_COUNT' => 'SIGN_OUT_COUNT',
        'COL_SIGN_OUT_COUNT' => 'SIGN_OUT_COUNT',
        'sign_out_count' => 'SIGN_OUT_COUNT',
        'whoo_member.sign_out_count' => 'SIGN_OUT_COUNT',
        'Provider' => 'PROVIDER',
        'Member.Provider' => 'PROVIDER',
        'provider' => 'PROVIDER',
        'member.provider' => 'PROVIDER',
        'MemberTableMap::COL_PROVIDER' => 'PROVIDER',
        'COL_PROVIDER' => 'PROVIDER',
        'whoo_member.provider' => 'PROVIDER',
        'ProviderId' => 'PROVIDER_ID',
        'Member.ProviderId' => 'PROVIDER_ID',
        'providerId' => 'PROVIDER_ID',
        'member.providerId' => 'PROVIDER_ID',
        'MemberTableMap::COL_PROVIDER_ID' => 'PROVIDER_ID',
        'COL_PROVIDER_ID' => 'PROVIDER_ID',
        'provider_id' => 'PROVIDER_ID',
        'whoo_member.provider_id' => 'PROVIDER_ID',
    ];

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('whoo_member');
        $this->setPhpName('Member');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Member');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('email', 'Email', 'VARCHAR', true, 255, null);
        $this->addColumn('username', 'Username', 'VARCHAR', true, 40, null);
        $this->addColumn('password_hash', 'PasswordHash', 'VARCHAR', false, 60, null);
        $this->addColumn('email_verified', 'EmailVerified', 'BOOLEAN', true, 1, false);
        $this->addColumn('sign_up_date_time', 'SignUpDateTime', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('sign_out_count', 'SignOutCount', 'INTEGER', true, null, 0);
        $this->addColumn('provider', 'Provider', 'VARCHAR', false, 255, null);
        $this->addColumn('provider_id', 'ProviderId', 'VARCHAR', false, 255, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('authentication_code', '\\authentication_code', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':member_id',
    1 => ':id',
  ),
), null, null, 'authentication_codes', false);
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? MemberTableMap::CLASS_DEFAULT : MemberTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Member object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = MemberTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MemberTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MemberTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MemberTableMap::OM_CLASS;
            /** @var Member $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MemberTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = MemberTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MemberTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Member $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MemberTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(MemberTableMap::COL_ID);
            $criteria->addSelectColumn(MemberTableMap::COL_EMAIL);
            $criteria->addSelectColumn(MemberTableMap::COL_USERNAME);
            $criteria->addSelectColumn(MemberTableMap::COL_PASSWORD_HASH);
            $criteria->addSelectColumn(MemberTableMap::COL_EMAIL_VERIFIED);
            $criteria->addSelectColumn(MemberTableMap::COL_SIGN_UP_DATE_TIME);
            $criteria->addSelectColumn(MemberTableMap::COL_SIGN_OUT_COUNT);
            $criteria->addSelectColumn(MemberTableMap::COL_PROVIDER);
            $criteria->addSelectColumn(MemberTableMap::COL_PROVIDER_ID);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.username');
            $criteria->addSelectColumn($alias . '.password_hash');
            $criteria->addSelectColumn($alias . '.email_verified');
            $criteria->addSelectColumn($alias . '.sign_up_date_time');
            $criteria->addSelectColumn($alias . '.sign_out_count');
            $criteria->addSelectColumn($alias . '.provider');
            $criteria->addSelectColumn($alias . '.provider_id');
        }
    }

    /**
     * Remove all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be removed as they are only loaded on demand.
     *
     * @param Criteria $criteria object containing the columns to remove.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function removeSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->removeSelectColumn(MemberTableMap::COL_ID);
            $criteria->removeSelectColumn(MemberTableMap::COL_EMAIL);
            $criteria->removeSelectColumn(MemberTableMap::COL_USERNAME);
            $criteria->removeSelectColumn(MemberTableMap::COL_PASSWORD_HASH);
            $criteria->removeSelectColumn(MemberTableMap::COL_EMAIL_VERIFIED);
            $criteria->removeSelectColumn(MemberTableMap::COL_SIGN_UP_DATE_TIME);
            $criteria->removeSelectColumn(MemberTableMap::COL_SIGN_OUT_COUNT);
            $criteria->removeSelectColumn(MemberTableMap::COL_PROVIDER);
            $criteria->removeSelectColumn(MemberTableMap::COL_PROVIDER_ID);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.email');
            $criteria->removeSelectColumn($alias . '.username');
            $criteria->removeSelectColumn($alias . '.password_hash');
            $criteria->removeSelectColumn($alias . '.email_verified');
            $criteria->removeSelectColumn($alias . '.sign_up_date_time');
            $criteria->removeSelectColumn($alias . '.sign_out_count');
            $criteria->removeSelectColumn($alias . '.provider');
            $criteria->removeSelectColumn($alias . '.provider_id');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(MemberTableMap::DATABASE_NAME)->getTable(MemberTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Member or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Member object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MemberTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Member) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MemberTableMap::DATABASE_NAME);
            $criteria->add(MemberTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = MemberQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            MemberTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                MemberTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the whoo_member table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return MemberQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Member or Criteria object.
     *
     * @param mixed               $criteria Criteria or Member object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MemberTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Member object
        }

        if ($criteria->containsKey(MemberTableMap::COL_ID) && $criteria->keyContainsValue(MemberTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.MemberTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = MemberQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // MemberTableMap
