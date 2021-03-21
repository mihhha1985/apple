<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\Apple;

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
            date_default_timezone_set("Europe/Moscow");
            if(Yii::$app->request->isPost){
                $data = Yii::$app->request->post();
                $num = $data['num'];
                if($num > 0 && $num < 101){
                    for($i = 1; $i <= $num; $i++){
                        $color = Apple::getAppleColor($i);
                        $apple = new Apple($color);
                        Yii::$app->db->createCommand()->insert('apple', [
                            'color' => "{$apple->color}",
                            'created_at' => $apple->create,
                            'updated_at' => $apple->update,
                            'status' => $apple->status,
                            'size' => $apple->size,
                        ])->execute();
                    }    
                }else{
                    $session->setFlash('error', 'Вы ввлеои неверное число!');
                }    
                   
            }
            
            Apple::setTimeDecay();
            $appels = Yii::$app->db->createCommand("SELECT * FROM apple")->queryAll();
            //var_dump($appels);die;
            
            return $this->render('admin', [
                'appels' => $appels,
            ]);

        }else{
            return $this->redirect('/');
        }    
    }

    public function actionFall($id)
    {
        $session = Yii::$app->session;
        if($session->has('admin')){
            date_default_timezone_set("Europe/Moscow");
            $id = intval($id);
            $apple = Yii::$app->db->createCommand('SELECT * FROM apple WHERE id= ' . $id)->queryOne();
            if($apple['status'] == 1){
                $time = time();
                Yii::$app->db->createCommand('UPDATE apple SET status = 2 WHERE id= ' . $id)->execute();
                Yii::$app->db->createCommand("UPDATE apple SET  updated_at = $time WHERE id= $id")->execute();
                $session->setFlash('success', 'Яблоко ID: ' . $id . ' упало с дерева!');
                return $this->redirect('/site/admin');
            }else{
                $session->setFlash('error', 'Невозможно выполнить действие, яблоко уже на земле!');
                return $this->redirect('/site/admin');
            }            
        }else{
            return $this->redirect('/');
        }    
    }

    public function actionEat($id)
    {
        $session = Yii::$app->session;
        if($session->has('admin')){
            date_default_timezone_set("Europe/Moscow");
            $id = intval($id);
            $apple = Yii::$app->db->createCommand('SELECT * FROM apple WHERE id= ' . $id)->queryOne();
            if($apple['status'] == 2){
                $size = $apple['size'] - 25;
                if($size == 0){
                    Yii::$app->db->createCommand('DELETE FROM apple WHERE id= ' . $apple['id'])->execute();
                    $session->setFlash('success', 'Яблоко ID: ' . $id . ' было съедено!');
                    return $this->redirect('/site/admin');
                }else{
                    Yii::$app->db->createCommand('UPDATE apple SET size = ' . $size . ' WHERE id= ' . $apple['id'])->execute();
                    $session->setFlash('success', 'Яблоко ID: ' . $id . ' надкушено!');
                    return $this->redirect('/site/admin');
                }
            }else{
                $session->setFlash('error', 'Невозможно есть, яблоко на дереве или уже сгнило');
                return $this->redirect('/site/admin');
            }

        }    
    }


}
