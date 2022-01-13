<?php

namespace Abdyek\Whoo\Tool;
use Abdyek\Whoo\Config\Propel as PropelConfig;

class Propel {
    public static function setConfig($config) {
        if(isset($config['database'])) {
            $database = $config['database'];
            if(isset($database['adapter'])) {
                PropelConfig::$ADAPTER = $database['adapter'];
            }
            if(isset($database['dsn'])) {
                PropelConfig::$DSN = $database['dsn'];
            }
            if(isset($database['host'])) {
                PropelConfig::$HOST = $database['host'];
            }
            if(isset($database['dbname'])) {
                PropelConfig::$DBNAME = $database['dbname'];
            }
            if(isset($database['user'])) {
                PropelConfig::$USER = $database['user'];
            }
            if(isset($database['password'])) {
                PropelConfig::$PASSWORD = $database['password'];
            }
            if(isset($database['utf-8'])) {
                PropelConfig::$UTF8 = $database['utf-8'];
            }
        }
    }
    public static function generateSchema($dbname): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
<database name="' . $dbname . '" defaultIdMethod="native">
    <table name="whoo_user" phpName="User">
        <column name="id" type="integer" required="true" primaryKey="true" autoIncrement="true"/>
        <column name="email" type="varchar" size="255" required="true" />
        <column name="username" type="varchar" size="40" required="false" />
        <column name="password_hash" type="varchar" size="60" required="false"/>
        <column name="email_verified" type="boolean" required="true" defaultValue="false"/>
        <column name="sign_up_date_time" type="timestamp" required="true" defaultExpr="CURRENT_TIMESTAMP"/>
        <column name="sign_out_count" type="integer" required="true" defaultValue="0" />
        <column name="provider" type="varchar" size="255" required="false" />
        <column name="provider_id" type="varchar" size="255" required="false" />
        <column name="two_factor_authentication" type="boolean" required="true" defaultValue="false"/>
    </table>
    <table name="whoo_authentication_code" phpName="AuthenticationCode">
        <column name="id" type="integer" required="true" primaryKey="true" autoIncrement="true"/>
        <column name="type" type="integer" required="true" />
        <column name="code" type="varchar" size="64" required="true" />
        <column name="trial_count" type="integer" required="true" defaultValue="0" />
        <column name="date_time" type="timestamp" required="true" defaultExpr="CURRENT_TIMESTAMP"/>
        <column name="user_id" type="integer" required="true" />
        <foreign-key foreignTable="whoo_user">
            <reference local="user_id" foreign="id" />
        </foreign-key>
    </table>
</database>';
    }
}
