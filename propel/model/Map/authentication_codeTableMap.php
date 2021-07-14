<?php

namespace Map;

use \authentication_code;
use \authentication_codeQuery;
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
 * This class defines the structure of the 'whoo_authentication_code' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class authentication_codeTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.authentication_codeTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'whoo';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'whoo_authentication_code';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\authentication_code';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'authentication_code';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the id field
     */
    const COL_ID = 'whoo_authentication_code.id';

    /**
     * the column name for the type field
     */
    const COL_TYPE = 'whoo_authentication_code.type';

    /**
     * the column name for the code field
     */
    const COL_CODE = 'whoo_authentication_code.code';

    /**
     * the column name for the trial_count field
     */
    const COL_TRIAL_COUNT = 'whoo_authentication_code.trial_count';

    /**
     * the column name for the date_time field
     */
    const COL_DATE_TIME = 'whoo_authentication_code.date_time';

    /**
     * the column name for the member_id field
     */
    const COL_MEMBER_ID = 'whoo_authentication_code.member_id';

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
        self::TYPE_PHPNAME       => array('Id', 'Type', 'Code', 'TrialCount', 'DateTime', 'MemberId', ),
        self::TYPE_CAMELNAME     => array('id', 'type', 'code', 'trialCount', 'dateTime', 'memberId', ),
        self::TYPE_COLNAME       => array(authentication_codeTableMap::COL_ID, authentication_codeTableMap::COL_TYPE, authentication_codeTableMap::COL_CODE, authentication_codeTableMap::COL_TRIAL_COUNT, authentication_codeTableMap::COL_DATE_TIME, authentication_codeTableMap::COL_MEMBER_ID, ),
        self::TYPE_FIELDNAME     => array('id', 'type', 'code', 'trial_count', 'date_time', 'member_id', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Type' => 1, 'Code' => 2, 'TrialCount' => 3, 'DateTime' => 4, 'MemberId' => 5, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'type' => 1, 'code' => 2, 'trialCount' => 3, 'dateTime' => 4, 'memberId' => 5, ),
        self::TYPE_COLNAME       => array(authentication_codeTableMap::COL_ID => 0, authentication_codeTableMap::COL_TYPE => 1, authentication_codeTableMap::COL_CODE => 2, authentication_codeTableMap::COL_TRIAL_COUNT => 3, authentication_codeTableMap::COL_DATE_TIME => 4, authentication_codeTableMap::COL_MEMBER_ID => 5, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'type' => 1, 'code' => 2, 'trial_count' => 3, 'date_time' => 4, 'member_id' => 5, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'authentication_code.Id' => 'ID',
        'id' => 'ID',
        'authentication_code.id' => 'ID',
        'authentication_codeTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'whoo_authentication_code.id' => 'ID',
        'Type' => 'TYPE',
        'authentication_code.Type' => 'TYPE',
        'type' => 'TYPE',
        'authentication_code.type' => 'TYPE',
        'authentication_codeTableMap::COL_TYPE' => 'TYPE',
        'COL_TYPE' => 'TYPE',
        'whoo_authentication_code.type' => 'TYPE',
        'Code' => 'CODE',
        'authentication_code.Code' => 'CODE',
        'code' => 'CODE',
        'authentication_code.code' => 'CODE',
        'authentication_codeTableMap::COL_CODE' => 'CODE',
        'COL_CODE' => 'CODE',
        'whoo_authentication_code.code' => 'CODE',
        'TrialCount' => 'TRIAL_COUNT',
        'authentication_code.TrialCount' => 'TRIAL_COUNT',
        'trialCount' => 'TRIAL_COUNT',
        'authentication_code.trialCount' => 'TRIAL_COUNT',
        'authentication_codeTableMap::COL_TRIAL_COUNT' => 'TRIAL_COUNT',
        'COL_TRIAL_COUNT' => 'TRIAL_COUNT',
        'trial_count' => 'TRIAL_COUNT',
        'whoo_authentication_code.trial_count' => 'TRIAL_COUNT',
        'DateTime' => 'DATE_TIME',
        'authentication_code.DateTime' => 'DATE_TIME',
        'dateTime' => 'DATE_TIME',
        'authentication_code.dateTime' => 'DATE_TIME',
        'authentication_codeTableMap::COL_DATE_TIME' => 'DATE_TIME',
        'COL_DATE_TIME' => 'DATE_TIME',
        'date_time' => 'DATE_TIME',
        'whoo_authentication_code.date_time' => 'DATE_TIME',
        'MemberId' => 'MEMBER_ID',
        'authentication_code.MemberId' => 'MEMBER_ID',
        'memberId' => 'MEMBER_ID',
        'authentication_code.memberId' => 'MEMBER_ID',
        'authentication_codeTableMap::COL_MEMBER_ID' => 'MEMBER_ID',
        'COL_MEMBER_ID' => 'MEMBER_ID',
        'member_id' => 'MEMBER_ID',
        'whoo_authentication_code.member_id' => 'MEMBER_ID',
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
        $this->setName('whoo_authentication_code');
        $this->setPhpName('authentication_code');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\authentication_code');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('type', 'Type', 'VARCHAR', true, 32, null);
        $this->addColumn('code', 'Code', 'VARCHAR', true, 64, null);
        $this->addColumn('trial_count', 'TrialCount', 'INTEGER', true, null, 0);
        $this->addColumn('date_time', 'DateTime', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addForeignKey('member_id', 'MemberId', 'INTEGER', 'whoo_member', 'id', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Member', '\\Member', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':member_id',
    1 => ':id',
  ),
), null, null, null, false);
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
        return $withPrefix ? authentication_codeTableMap::CLASS_DEFAULT : authentication_codeTableMap::OM_CLASS;
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
     * @return array           (authentication_code object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = authentication_codeTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = authentication_codeTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + authentication_codeTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = authentication_codeTableMap::OM_CLASS;
            /** @var authentication_code $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            authentication_codeTableMap::addInstanceToPool($obj, $key);
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
            $key = authentication_codeTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = authentication_codeTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var authentication_code $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                authentication_codeTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(authentication_codeTableMap::COL_ID);
            $criteria->addSelectColumn(authentication_codeTableMap::COL_TYPE);
            $criteria->addSelectColumn(authentication_codeTableMap::COL_CODE);
            $criteria->addSelectColumn(authentication_codeTableMap::COL_TRIAL_COUNT);
            $criteria->addSelectColumn(authentication_codeTableMap::COL_DATE_TIME);
            $criteria->addSelectColumn(authentication_codeTableMap::COL_MEMBER_ID);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.type');
            $criteria->addSelectColumn($alias . '.code');
            $criteria->addSelectColumn($alias . '.trial_count');
            $criteria->addSelectColumn($alias . '.date_time');
            $criteria->addSelectColumn($alias . '.member_id');
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
            $criteria->removeSelectColumn(authentication_codeTableMap::COL_ID);
            $criteria->removeSelectColumn(authentication_codeTableMap::COL_TYPE);
            $criteria->removeSelectColumn(authentication_codeTableMap::COL_CODE);
            $criteria->removeSelectColumn(authentication_codeTableMap::COL_TRIAL_COUNT);
            $criteria->removeSelectColumn(authentication_codeTableMap::COL_DATE_TIME);
            $criteria->removeSelectColumn(authentication_codeTableMap::COL_MEMBER_ID);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.type');
            $criteria->removeSelectColumn($alias . '.code');
            $criteria->removeSelectColumn($alias . '.trial_count');
            $criteria->removeSelectColumn($alias . '.date_time');
            $criteria->removeSelectColumn($alias . '.member_id');
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
        return Propel::getServiceContainer()->getDatabaseMap(authentication_codeTableMap::DATABASE_NAME)->getTable(authentication_codeTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a authentication_code or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or authentication_code object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(authentication_codeTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \authentication_code) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(authentication_codeTableMap::DATABASE_NAME);
            $criteria->add(authentication_codeTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = authentication_codeQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            authentication_codeTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                authentication_codeTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the whoo_authentication_code table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return authentication_codeQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a authentication_code or Criteria object.
     *
     * @param mixed               $criteria Criteria or authentication_code object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(authentication_codeTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from authentication_code object
        }

        if ($criteria->containsKey(authentication_codeTableMap::COL_ID) && $criteria->keyContainsValue(authentication_codeTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.authentication_codeTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = authentication_codeQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // authentication_codeTableMap
