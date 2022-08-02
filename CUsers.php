<?php
namespace app\components;

use yii\base\BaseObject;
use app\models\User;
use app\models\Storerooms;

class CUsers extends BaseObject
{

    public function __construct($param1, $param2, $config = [])
    {
        // ... initialization before configuration is applied

        parent::__construct($config);
    }

    public function init()
    {
        parent::init();

        // ... initialization after configuration is applied
    }
    
     /**
     *
     * @return array
     */
    public function getList($page = 1, $limit = 6, $sort = 'created_at')
    {
        $model = new User();
        return $model->find()->orderBy($sort)->limit($limit)->asArray()->all();
    }
    
     /**
     *
     * @return array
     */
    public function getOne(int $id) 
    {
        $model = new User();
        return $model->find()->
                leftJoin('user_subscribers as us1', 'us1.user_id = user.id')->
                leftJoin('user_subscribers as us2', 'us2.dest_user_id = user.id')->
                //leftJoin('user_comments', 'user_comments.user_id = user.id')->
                select(['user.*', 
                        new \yii\db\Expression('count(us2.id) as count_subscribers'),
                        new \yii\db\Expression('count(us1.id) as count_subscriptions'),
                        new \yii\db\Expression('(0) as comment_count'),
                        new \yii\db\Expression('DATEDIFF(now(), from_unixtime(user.created_at)) as how_old_site')
                    ])->
                where(['user.id' => $id])->groupBy('user.id')->asArray()->one();
    }
    
     /**
     *
     * @return object
     */
    public function getOneObject(int $id) 
    {
        $model = new User();
        return $model->find($id)->one();
    }
    
     /**
     *
     * @return object
     */
    public function getStoreroom(int $id) 
    {
        $model = new Storerooms();
        return $model->find()->where(['user_id' => $id])->one();
    }
    
     /**
     *
     * @return bool
     */
    public function delAvatar(int $id)
    {
        return User::updateAll(['avatar' => ''], ['id' => $id]);
    }
    
     /**
     *
     * @return bool
     */
    public function del(int $id)
    {
        return User::deleteAll(['id' => $id]);
    }
}