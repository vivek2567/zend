<?php
Doctrine_Manager::getInstance()->bindComponent('User', 'doctrine');
/**
 * BaseUser
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @property integer $id
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 * @property string $password
 * @property string $google
 * @property string $twitter
 * @property string $pinterest
 * @property string $likes
 * @property string $dislike
 * @property string $mainText
 * @property boolean $status
 * @property integer $roleId
 * @property integer $profileImageId
 * @property integer $createdBy
 * @property integer $passwordChnageTime
 * @property integer $popularKortingscode
 * @property Role $role
 * @property timestamp $currentLogIn
 * @property timestamp $lastLogIn
 * @property ProfileImage $profileimage
 * @property User $user
 * @property string $countryLocale
 * @property Doctrine_Collection $website
 * @property Doctrine_Collection $refUserWebsite
 *
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7691 2011-02-04 15:43:29Z jwage $
 */
abstract class BaseUser extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('user');
        $this->hasColumn('id', 'integer', 20, array(
             'primary' => true,
             'type' => 'integer',
             'autoincrement' => true,
             'comment' => 'PK',
             'length' => '20',
             ));
        $this->hasColumn('firstName', 'string', 255, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('lastName', 'string', 255, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('slug', 'string', null, array(
             'type' => 'string'
        ));
        $this->hasColumn('email', 'string', 255, array(
             //'unique' => true,
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('password', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('status', 'boolean', null, array(
             'default' => 1,
             'type' => 'boolean',
             ));

        $this->hasColumn('showInAboutListing', 'boolean', null, array(
                'default' => 1,
                'type' => 'boolean',
        ));
        $this->hasColumn('addtosearch', 'boolean', null, array(
                'default' => 0,
                'type' => 'boolean',
        ));
        $this->hasColumn('google', 'string',null,  array(
                'type' => 'string'

        ));
        $this->hasColumn('twitter', 'string',null,  array(
                'type' => 'string'

        ));
        $this->hasColumn('pinterest', 'string', null, array(
                'type' => 'string'

        ));
        $this->hasColumn('likes', 'string', null, array(
                'type' => 'string'

        ));
        $this->hasColumn('dislike', 'string', null, array(
                'type' => 'string'

        ));
        $this->hasColumn('mainText', 'string', null, array(
                'type' => 'string',

        ));
        $this->hasColumn('passwordChangeTime', 'string', 20, array(
                'type' => 'string',
                'defautl' => date("Y-m-d H:i:s")
        ));
        $this->hasColumn('roleId', 'integer', 20, array(
             'type' => 'integer',
             'comment' => 'FK to role.id',
             'length' => '20',
             ));
        $this->hasColumn('profileImageId', 'integer', 20, array(
                'type' => 'integer',
                'comment' => 'FK to profileimage.id',
                'length' => '20',
        ));
        $this->hasColumn('createdBy', 'integer', 20, array(
             'type' => 'integer',
             'comment' => 'FK to user.id',
             'length' => '20',
             ));

        $this->hasColumn('popularKortingscode', 'integer', 10, array(
                'type' => 'integer',
                'length' => '10',
        ));
        $this->hasColumn('currentLogIn', 'timestamp', null, array(
                'type' => 'timestamp',
             ));

        $this->hasColumn('lastLogIn', 'timestamp', null, array(
                'type' => 'timestamp',
             ));
        $this->hasColumn('countryLocale', 'string', 10, array(
                'type' => 'string',
        ));
        $this->hasColumn('editorText', 'string', 100, array(
                'type' => 'string',
        ));

    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Role as role', array(
             'local' => 'roleId',
             'foreign' => 'id'));

        $this->hasOne('ProfileImage as profileimage', array(
             'local' => 'profileImageId',
             'foreign' => 'id'));

        $this->hasOne('User as user', array(
             'local' => 'createdBy',
             'foreign' => 'id'));

        $this->hasMany('Website as website', array(
             'refClass' => 'refUserWebsite',
             'local' => 'userId',
             'foreign' => 'websiteId'));

        $this->hasMany('refUserWebsite', array(
             'local' => 'id',
             'foreign' => 'userId'));

        $this->hasMany('UserSession as usersession', array(
                'local' => 'id',
                'foreign' => 'userId'));

        $this->hasMany('Adminfavoriteshp as adminfevoriteshop', array(
                'local' => 'id',
                'foreign' => 'userId'));

        $this->hasMany('Interestingcategory as intcat', array(
                'local' => 'id',
                'foreign' => 'userId'));

        $softdelete0 = new Doctrine_Template_SoftDelete(array(
             'name' => 'deleted',
             'type' => 'boolean',
             'options' =>
             array(
              'default' => 0,
             ),
             ));
        $timestampable0 = new Doctrine_Template_Timestampable(array(
             'created' =>
             array(
              'name' => 'created_at',
             ),
             'updated' =>
             array(
              'name' => 'updated_at',
             ),
             ));
        $this->actAs($softdelete0);
        $this->actAs($timestampable0);
    }
}
