<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/4
 * Time: 10:36
 */

namespace frontend\controllers;


use yii\redis\Connection;
use yii\web\Controller;

class Day15Controller extends Controller
{
    public function actionSet(){

        $redis=\Yii::$app->redis;
        $redis=new Connection();
        $redis->set('name','刘超');



    }

    public function actionGet()
    {
        $redis=new Connection();
      echo  $redis->get('name');

    }


    /**
     * 抽奖
     */
    public function actionJiang()
    {

        //1.准备好奖品
        $jiangs=[
            '陈一发儿VIP一个',
            '陈一发儿VIP一个',
            '陈一发儿VIP一个',
            '陈一发儿VIP一个',
            '陈一发儿VIP一个',
            '100万',
            '法拉利一辆',
            '法拉利一辆',
            '牛肉一包',
            '牛肉一包',

        ];

        //2. 奖品入Redis
        $redis=new Connection();

        foreach ($jiangs as $k=>$jiang){

            $redis->sadd('jiang',$k);



        }

    }

    public function actionOk(){
        //1.准备好奖品
        $jiangs=[
            '陈一发儿VIP一个',
            '陈一发儿VIP一个',
            '陈一发儿VIP一个',
            '陈一发儿VIP一个',
            '陈一发儿VIP一个',
            '100万',
            '法拉利一辆',
            '法拉利一辆',
            '牛肉一包',
            '牛肉一包',

        ];
        //设置概率 5%的概念中奖
        $num=rand(1,100);

        if ($num>50){

        exit('谢谢');

        }


        $redis=new Connection();

       // var_dump($redis->smembers('jiang'));
        //判断还有没有奖品


        if ($redis->smembers('jiang')){

            //随机抽奖
           $key= $redis->srandmember('jiang');

           echo "恭喜你中奖：".$jiangs[$key];

           //干掉当前奖品
            $redis->srem('jiang',$key);

        }else{

            echo "下来再来";
        }

    }

    /**
     * 发邮箱
     */
    public function actionEmail()
    {
        \Yii::$app->mailer->compose()
            ->setFrom('liu3chao@163.com')
            ->setTo('1285143051@qq.com')
            ->setSubject('PHP 1108大神班马要要毕业了')
          //  ->setTextBody('Plain text content')
            ->setHtmlBody('<b>祝大家学来有成</b>')
            ->send();
    }
}