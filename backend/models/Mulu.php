<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mulu".
 *
 * @property int $id
 * @property string $name 名称
 * @property string $ico 图标
 * @property string $url 地址
 * @property int $parent_id 父类ID
 */
class Mulu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['name', 'ico', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'ico' => '图标',
            'url' => '地址',
            'parent_id' => '父类ID',
        ];
    }

    //声明一个静态方法
    public static function menu()
    {

  /*      $menu = [
            [
                'label' => '商品管理',
                'icon' => 'car',
                'url' => '#',
                'items' => [
                    ['label' => '商品列表', 'icon' => 'file-code-o', 'url' => ['/goods/index'],],
                    ['label' => '添加商品', 'icon' => 'dashboard', 'url' => ['/goods/add'],],
                ],
            ],
            [
                'label' => '品牌管理',
                'icon' => 'car',
                'url' => '#',
                'items' => [
                    ['label' => '商品列表', 'icon' => 'file-code-o', 'url' => ['/goods/index'],],
                    ['label' => '添加商品', 'icon' => 'dashboard', 'url' => ['/goods/add'],],
                ],
            ],
        ];*/

        //定义一个空数组用来装菜单
        $menuAll = [];
        //得到所有一级目录
        $menus = self::find()->where(['parent_id' => 0])->all();

        //循环取出一级分类
        foreach ($menus as $menu) {
            //定义一个新的数组
            $newMenu = [];
            //分别赋值
            $newMenu['label'] = $menu->name;
            $newMenu['icon'] = $menu->ico;
            $newMenu['url'] = $menu->url;

            // var_dump($newMenu);exit;

            //通过一级菜单找到它所有二级菜单
            $menusSon = self::find()->where(['parent_id' => $menu->id])->all();
            //再次循环取出当前的二极菜单
            foreach ($menusSon as $menuSon) {

                // ['label' => '商品列表', 'icon' => 'file-code-o', 'url' => ['/goods/index'],]
                //用来存二级菜单
                $newMenuSon = [];
                //分别赋值
                $newMenuSon['label'] = $menuSon->name;
                $newMenuSon['icon'] = $menuSon->ico;
                $newMenuSon['url'] = $menuSon->url;

                // var_dump($newMenuSon);exit;
                //扔到一级菜单下面去
                $newMenu['items'][] = $newMenuSon;

            }

        /*    var_dump($newMenu);
            exit;*/


//最后的菜单
            $menuAll[] = $newMenu;


        }


        // 返回
        return $menuAll;
    }
}
