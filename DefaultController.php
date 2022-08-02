<?php

namespace app\modules\lk\controllers;

use Yii;
use yii\web\Controller;

/**
 * Default controller for the `lk` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $user =  Yii::$app->users->getOne(Yii::$app->user->id);
        //$logbooks =  Yii::$app->logbooks->getListForUser(Yii::$app->user->id);
        $equipments =  Yii::$app->equipments->getListForUser(Yii::$app->user->id);
        $places = Yii::$app->places_skiings->getListForUser(Yii::$app->user->id);
        $mapWidget = Yii::$app->yandex_map->getMapListCoords($places);
        $events =  Yii::$app->events->getListForUser(Yii::$app->user->id);
        
        $events_map_widget = [];
        foreach ($events as $key => $value) 
        {
            $events_map_widget[$key] = Yii::$app->yandex_map->getMapOneCoords($value);
        }

        return $this->render('index', [
            'user' => $user,
            'equipments' => $equipments,
            'places' => $places,
            'mapWidget' => $mapWidget,
            'events' => $events,
            'events_map_widget' => $events_map_widget
        ]);
    }
}
