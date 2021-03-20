<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        if(Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            $session = Yii::$app->session;
            if($data['login'] == Yii::$app->params['login'] && $data['password'] == Yii::$app->params['password'])
            {
                $session->set('admin', 'on');
                return $this->redirect('/site/admin');
            }else{
                $session->setFlash('error', 'Вы ввели неверный логин или пароль!');    
            }
        }

        return $this->render('index');
    }

    public function actionAdmin()
    {
        $session = Yii::$app->session;
        if($session->has('admin')){
            echo 'admin';
        }else{
            return $this->redirect('/');
        }    
    }

}
