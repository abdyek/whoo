<?php

namespace Base;

use \Member as ChildMember;
use \MemberQuery as ChildMemberQuery;
use \authentication_code as Childauthentication_code;
use \authentication_codeQuery as Childauthentication_codeQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\MemberTableMap;
use Map\authentication_codeTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'whoo_member' table.
 *
 *
 *
 * @package    propel.generator..Base
 */
abstract class Member implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\MemberTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the email field.
     *
     * @var        string
     */
    protected $email;

    /**
     * The value for the username field.
     *
     * @var        string
     */
    protected $username;

    /**
     * The value for the password_hash field.
     *
     * @var        string|null
     */
    protected $password_hash;

    /**
     * The value for the email_verified field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $email_verified;

    /**
     * The value for the sign_up_date_time field.
     *
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        DateTime
     */
    protected $sign_up_date_time;

    /**
     * The value for the sign_out_count field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $sign_out_count;

    /**
     * The value for the provider field.
     *
     * @var        string|null
     */
    protected $provider;

    /**
     * The value for the provider_id field.
     *
     * @var        string|null
     */
    protected $provider_id;

    /**
     * @var        ObjectCollection|Childauthentication_code[] Collection to store aggregation of Childauthentication_code objects.
     * @phpstan-var ObjectCollection&\Traversable<Childauthentication_code> Collection to store aggregation of Childauthentication_code objects.
     */
    protected $collauthentication_codes;
    protected $collauthentication_codesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Childauthentication_code[]
     * @phpstan-var ObjectCollection&\Traversable<Childauthentication_code>
     */
    protected $authentication_codesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->email_verified = false;
        $this->sign_out_count = 0;
    }

    /**
     * Initializes internal state of Base\Member object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            unset($this->modifiedColumns[$col]);
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Member</code> instance.  If
     * <code>obj</code> is an instance of <code>Member</code>, delegates to
     * <code>equals(Member)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return void
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @param  string  $keyType                (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME, TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM. Defaults to TableMap::TYPE_PHPNAME.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray($keyType, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the [username] column value.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the [password_hash] column value.
     *
     * @return string|null
     */
    public function getPasswordHash()
    {
        return $this->password_hash;
    }

    /**
     * Get the [email_verified] column value.
     *
     * @return boolean
     */
    public function getEmailVerified()
    {
        return $this->email_verified;
    }

    /**
     * Get the [email_verified] column value.
     *
     * @return boolean
     */
    public function isEmailVerified()
    {
        return $this->getEmailVerified();
    }

    /**
     * Get the [optionally formatted] temporal [sign_up_date_time] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime : string)
     */
    public function getSignUpDateTime($format = null)
    {
        if ($format === null) {
            return $this->sign_up_date_time;
        } else {
            return $this->sign_up_date_time instanceof \DateTimeInterface ? $this->sign_up_date_time->format($format) : null;
        }
    }

    /**
     * Get the [sign_out_count] column value.
     *
     * @return int
     */
    public function getSignOutCount()
    {
        return $this->sign_out_count;
    }

    /**
     * Get the [provider] column value.
     *
     * @return string|null
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Get the [provider_id] column value.
     *
     * @return string|null
     */
    public function getProviderId()
    {
        return $this->provider_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v New value
     * @return $this|\Member The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[MemberTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [email] column.
     *
     * @param string $v New value
     * @return $this|\Member The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[MemberTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [username] column.
     *
     * @param string $v New value
     * @return $this|\Member The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[MemberTableMap::COL_USERNAME] = true;
        }

        return $this;
    } // setUsername()

    /**
     * Set the value of [password_hash] column.
     *
     * @param string|null $v New value
     * @return $this|\Member The current object (for fluent API support)
     */
    public function setPasswordHash($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password_hash !== $v) {
            $this->password_hash = $v;
            $this->modifiedColumns[MemberTableMap::COL_PASSWORD_HASH] = true;
        }

        return $this;
    } // setPasswordHash()

    /**
     * Sets the value of the [email_verified] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Member The current object (for fluent API support)
     */
    public function setEmailVerified($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->email_verified !== $v) {
            $this->email_verified = $v;
            $this->modifiedColumns[MemberTableMap::COL_EMAIL_VERIFIED] = true;
        }

        return $this;
    } // setEmailVerified()

    /**
     * Sets the value of [sign_up_date_time] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Member The current object (for fluent API support)
     */
    public function setSignUpDateTime($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->sign_up_date_time !== null || $dt !== null) {
            if ($this->sign_up_date_time === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->sign_up_date_time->format("Y-m-d H:i:s.u")) {
                $this->sign_up_date_time = $dt === null ? null : clone $dt;
                $this->modifiedColumns[MemberTableMap::COL_SIGN_UP_DATE_TIME] = true;
            }
        } // if either are not null

        return $this;
    } // setSignUpDateTime()

    /**
     * Set the value of [sign_out_count] column.
     *
     * @param int $v New value
     * @return $this|\Member The current object (for fluent API support)
     */
    public function setSignOutCount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->sign_out_count !== $v) {
            $this->sign_out_count = $v;
            $this->modifiedColumns[MemberTableMap::COL_SIGN_OUT_COUNT] = true;
        }

        return $this;
    } // setSignOutCount()

    /**
     * Set the value of [provider] column.
     *
     * @param string|null $v New value
     * @return $this|\Member The current object (for fluent API support)
     */
    public function setProvider($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->provider !== $v) {
            $this->provider = $v;
            $this->modifiedColumns[MemberTableMap::COL_PROVIDER] = true;
        }

        return $this;
    } // setProvider()

    /**
     * Set the value of [provider_id] column.
     *
     * @param string|null $v New value
     * @return $this|\Member The current object (for fluent API support)
     */
    public function setProviderId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->provider_id !== $v) {
            $this->provider_id = $v;
            $this->modifiedColumns[MemberTableMap::COL_PROVIDER_ID] = true;
        }

        return $this;
    } // setProviderId()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->email_verified !== false) {
                return false;
            }

            if ($this->sign_out_count !== 0) {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : MemberTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : MemberTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : MemberTableMap::translateFieldName('Username', TableMap::TYPE_PHPNAME, $indexType)];
            $this->username = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : MemberTableMap::translateFieldName('PasswordHash', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password_hash = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : MemberTableMap::translateFieldName('EmailVerified', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email_verified = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : MemberTableMap::translateFieldName('SignUpDateTime', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->sign_up_date_time = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : MemberTableMap::translateFieldName('SignOutCount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sign_out_count = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : MemberTableMap::translateFieldName('Provider', TableMap::TYPE_PHPNAME, $indexType)];
            $this->provider = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : MemberTableMap::translateFieldName('ProviderId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->provider_id = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = MemberTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Member'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MemberTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildMemberQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collauthentication_codes = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Member::setDeleted()
     * @see Member::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MemberTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildMemberQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MemberTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                MemberTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->authentication_codesScheduledForDeletion !== null) {
                if (!$this->authentication_codesScheduledForDeletion->isEmpty()) {
                    \authentication_codeQuery::create()
                        ->filterByPrimaryKeys($this->authentication_codesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->authentication_codesScheduledForDeletion = null;
                }
            }

            if ($this->collauthentication_codes !== null) {
                foreach ($this->collauthentication_codes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[MemberTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MemberTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MemberTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(MemberTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'email';
        }
        if ($this->isColumnModified(MemberTableMap::COL_USERNAME)) {
            $modifiedColumns[':p' . $index++]  = 'username';
        }
        if ($this->isColumnModified(MemberTableMap::COL_PASSWORD_HASH)) {
            $modifiedColumns[':p' . $index++]  = 'password_hash';
        }
        if ($this->isColumnModified(MemberTableMap::COL_EMAIL_VERIFIED)) {
            $modifiedColumns[':p' . $index++]  = 'email_verified';
        }
        if ($this->isColumnModified(MemberTableMap::COL_SIGN_UP_DATE_TIME)) {
            $modifiedColumns[':p' . $index++]  = 'sign_up_date_time';
        }
        if ($this->isColumnModified(MemberTableMap::COL_SIGN_OUT_COUNT)) {
            $modifiedColumns[':p' . $index++]  = 'sign_out_count';
        }
        if ($this->isColumnModified(MemberTableMap::COL_PROVIDER)) {
            $modifiedColumns[':p' . $index++]  = 'provider';
        }
        if ($this->isColumnModified(MemberTableMap::COL_PROVIDER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'provider_id';
        }

        $sql = sprintf(
            'INSERT INTO whoo_member (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'email':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'username':
                        $stmt->bindValue($identifier, $this->username, PDO::PARAM_STR);
                        break;
                    case 'password_hash':
                        $stmt->bindValue($identifier, $this->password_hash, PDO::PARAM_STR);
                        break;
                    case 'email_verified':
                        $stmt->bindValue($identifier, (int) $this->email_verified, PDO::PARAM_INT);
                        break;
                    case 'sign_up_date_time':
                        $stmt->bindValue($identifier, $this->sign_up_date_time ? $this->sign_up_date_time->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'sign_out_count':
                        $stmt->bindValue($identifier, $this->sign_out_count, PDO::PARAM_INT);
                        break;
                    case 'provider':
                        $stmt->bindValue($identifier, $this->provider, PDO::PARAM_STR);
                        break;
                    case 'provider_id':
                        $stmt->bindValue($identifier, $this->provider_id, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MemberTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getEmail();
                break;
            case 2:
                return $this->getUsername();
                break;
            case 3:
                return $this->getPasswordHash();
                break;
            case 4:
                return $this->getEmailVerified();
                break;
            case 5:
                return $this->getSignUpDateTime();
                break;
            case 6:
                return $this->getSignOutCount();
                break;
            case 7:
                return $this->getProvider();
                break;
            case 8:
                return $this->getProviderId();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Member'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Member'][$this->hashCode()] = true;
        $keys = MemberTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getEmail(),
            $keys[2] => $this->getUsername(),
            $keys[3] => $this->getPasswordHash(),
            $keys[4] => $this->getEmailVerified(),
            $keys[5] => $this->getSignUpDateTime(),
            $keys[6] => $this->getSignOutCount(),
            $keys[7] => $this->getProvider(),
            $keys[8] => $this->getProviderId(),
        );
        if ($result[$keys[5]] instanceof \DateTimeInterface) {
            $result[$keys[5]] = $result[$keys[5]]->format('Y-m-d H:i:s.u');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collauthentication_codes) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'authentication_codes';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'whoo_authentication_codes';
                        break;
                    default:
                        $key = 'authentication_codes';
                }

                $result[$key] = $this->collauthentication_codes->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Member
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MemberTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Member
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setEmail($value);
                break;
            case 2:
                $this->setUsername($value);
                break;
            case 3:
                $this->setPasswordHash($value);
                break;
            case 4:
                $this->setEmailVerified($value);
                break;
            case 5:
                $this->setSignUpDateTime($value);
                break;
            case 6:
                $this->setSignOutCount($value);
                break;
            case 7:
                $this->setProvider($value);
                break;
            case 8:
                $this->setProviderId($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return     $this|\Member
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = MemberTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setEmail($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setUsername($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setPasswordHash($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setEmailVerified($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setSignUpDateTime($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setSignOutCount($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setProvider($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setProviderId($arr[$keys[8]]);
        }

        return $this;
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Member The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(MemberTableMap::DATABASE_NAME);

        if ($this->isColumnModified(MemberTableMap::COL_ID)) {
            $criteria->add(MemberTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(MemberTableMap::COL_EMAIL)) {
            $criteria->add(MemberTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(MemberTableMap::COL_USERNAME)) {
            $criteria->add(MemberTableMap::COL_USERNAME, $this->username);
        }
        if ($this->isColumnModified(MemberTableMap::COL_PASSWORD_HASH)) {
            $criteria->add(MemberTableMap::COL_PASSWORD_HASH, $this->password_hash);
        }
        if ($this->isColumnModified(MemberTableMap::COL_EMAIL_VERIFIED)) {
            $criteria->add(MemberTableMap::COL_EMAIL_VERIFIED, $this->email_verified);
        }
        if ($this->isColumnModified(MemberTableMap::COL_SIGN_UP_DATE_TIME)) {
            $criteria->add(MemberTableMap::COL_SIGN_UP_DATE_TIME, $this->sign_up_date_time);
        }
        if ($this->isColumnModified(MemberTableMap::COL_SIGN_OUT_COUNT)) {
            $criteria->add(MemberTableMap::COL_SIGN_OUT_COUNT, $this->sign_out_count);
        }
        if ($this->isColumnModified(MemberTableMap::COL_PROVIDER)) {
            $criteria->add(MemberTableMap::COL_PROVIDER, $this->provider);
        }
        if ($this->isColumnModified(MemberTableMap::COL_PROVIDER_ID)) {
            $criteria->add(MemberTableMap::COL_PROVIDER_ID, $this->provider_id);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildMemberQuery::create();
        $criteria->add(MemberTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Member (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEmail($this->getEmail());
        $copyObj->setUsername($this->getUsername());
        $copyObj->setPasswordHash($this->getPasswordHash());
        $copyObj->setEmailVerified($this->getEmailVerified());
        $copyObj->setSignUpDateTime($this->getSignUpDateTime());
        $copyObj->setSignOutCount($this->getSignOutCount());
        $copyObj->setProvider($this->getProvider());
        $copyObj->setProviderId($this->getProviderId());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getauthentication_codes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addauthentication_code($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Member Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('authentication_code' === $relationName) {
            $this->initauthentication_codes();
            return;
        }
    }

    /**
     * Clears out the collauthentication_codes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addauthentication_codes()
     */
    public function clearauthentication_codes()
    {
        $this->collauthentication_codes = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collauthentication_codes collection loaded partially.
     */
    public function resetPartialauthentication_codes($v = true)
    {
        $this->collauthentication_codesPartial = $v;
    }

    /**
     * Initializes the collauthentication_codes collection.
     *
     * By default this just sets the collauthentication_codes collection to an empty array (like clearcollauthentication_codes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initauthentication_codes($overrideExisting = true)
    {
        if (null !== $this->collauthentication_codes && !$overrideExisting) {
            return;
        }

        $collectionClassName = authentication_codeTableMap::getTableMap()->getCollectionClassName();

        $this->collauthentication_codes = new $collectionClassName;
        $this->collauthentication_codes->setModel('\authentication_code');
    }

    /**
     * Gets an array of Childauthentication_code objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMember is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Childauthentication_code[] List of Childauthentication_code objects
     * @phpstan-return ObjectCollection&\Traversable<Childauthentication_code> List of Childauthentication_code objects
     * @throws PropelException
     */
    public function getauthentication_codes(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collauthentication_codesPartial && !$this->isNew();
        if (null === $this->collauthentication_codes || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collauthentication_codes) {
                    $this->initauthentication_codes();
                } else {
                    $collectionClassName = authentication_codeTableMap::getTableMap()->getCollectionClassName();

                    $collauthentication_codes = new $collectionClassName;
                    $collauthentication_codes->setModel('\authentication_code');

                    return $collauthentication_codes;
                }
            } else {
                $collauthentication_codes = Childauthentication_codeQuery::create(null, $criteria)
                    ->filterByMember($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collauthentication_codesPartial && count($collauthentication_codes)) {
                        $this->initauthentication_codes(false);

                        foreach ($collauthentication_codes as $obj) {
                            if (false == $this->collauthentication_codes->contains($obj)) {
                                $this->collauthentication_codes->append($obj);
                            }
                        }

                        $this->collauthentication_codesPartial = true;
                    }

                    return $collauthentication_codes;
                }

                if ($partial && $this->collauthentication_codes) {
                    foreach ($this->collauthentication_codes as $obj) {
                        if ($obj->isNew()) {
                            $collauthentication_codes[] = $obj;
                        }
                    }
                }

                $this->collauthentication_codes = $collauthentication_codes;
                $this->collauthentication_codesPartial = false;
            }
        }

        return $this->collauthentication_codes;
    }

    /**
     * Sets a collection of Childauthentication_code objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $authentication_codes A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMember The current object (for fluent API support)
     */
    public function setauthentication_codes(Collection $authentication_codes, ConnectionInterface $con = null)
    {
        /** @var Childauthentication_code[] $authentication_codesToDelete */
        $authentication_codesToDelete = $this->getauthentication_codes(new Criteria(), $con)->diff($authentication_codes);


        $this->authentication_codesScheduledForDeletion = $authentication_codesToDelete;

        foreach ($authentication_codesToDelete as $authentication_codeRemoved) {
            $authentication_codeRemoved->setMember(null);
        }

        $this->collauthentication_codes = null;
        foreach ($authentication_codes as $authentication_code) {
            $this->addauthentication_code($authentication_code);
        }

        $this->collauthentication_codes = $authentication_codes;
        $this->collauthentication_codesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related authentication_code objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related authentication_code objects.
     * @throws PropelException
     */
    public function countauthentication_codes(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collauthentication_codesPartial && !$this->isNew();
        if (null === $this->collauthentication_codes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collauthentication_codes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getauthentication_codes());
            }

            $query = Childauthentication_codeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMember($this)
                ->count($con);
        }

        return count($this->collauthentication_codes);
    }

    /**
     * Method called to associate a Childauthentication_code object to this object
     * through the Childauthentication_code foreign key attribute.
     *
     * @param  Childauthentication_code $l Childauthentication_code
     * @return $this|\Member The current object (for fluent API support)
     */
    public function addauthentication_code(Childauthentication_code $l)
    {
        if ($this->collauthentication_codes === null) {
            $this->initauthentication_codes();
            $this->collauthentication_codesPartial = true;
        }

        if (!$this->collauthentication_codes->contains($l)) {
            $this->doAddauthentication_code($l);

            if ($this->authentication_codesScheduledForDeletion and $this->authentication_codesScheduledForDeletion->contains($l)) {
                $this->authentication_codesScheduledForDeletion->remove($this->authentication_codesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Childauthentication_code $authentication_code The Childauthentication_code object to add.
     */
    protected function doAddauthentication_code(Childauthentication_code $authentication_code)
    {
        $this->collauthentication_codes[]= $authentication_code;
        $authentication_code->setMember($this);
    }

    /**
     * @param  Childauthentication_code $authentication_code The Childauthentication_code object to remove.
     * @return $this|ChildMember The current object (for fluent API support)
     */
    public function removeauthentication_code(Childauthentication_code $authentication_code)
    {
        if ($this->getauthentication_codes()->contains($authentication_code)) {
            $pos = $this->collauthentication_codes->search($authentication_code);
            $this->collauthentication_codes->remove($pos);
            if (null === $this->authentication_codesScheduledForDeletion) {
                $this->authentication_codesScheduledForDeletion = clone $this->collauthentication_codes;
                $this->authentication_codesScheduledForDeletion->clear();
            }
            $this->authentication_codesScheduledForDeletion[]= clone $authentication_code;
            $authentication_code->setMember(null);
        }

        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->email = null;
        $this->username = null;
        $this->password_hash = null;
        $this->email_verified = null;
        $this->sign_up_date_time = null;
        $this->sign_out_count = null;
        $this->provider = null;
        $this->provider_id = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collauthentication_codes) {
                foreach ($this->collauthentication_codes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collauthentication_codes = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(MemberTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
            }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
            }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
            }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
            }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);
            $inputData = $params[0];
            $keyType = $params[1] ?? TableMap::TYPE_PHPNAME;

            return $this->importFrom($format, $inputData, $keyType);
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = $params[0] ?? true;
            $keyType = $params[1] ?? TableMap::TYPE_PHPNAME;

            return $this->exportTo($format, $includeLazyLoadColumns, $keyType);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
