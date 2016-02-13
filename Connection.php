<?php
/**
 * Created by PhpStorm.
 * User: vuquangthinh
 * Date: 1/7/2016
 * Time: 10:45 AM
 */

namespace vuquangthinh\yii2\sio;

use Yii;
use yii\base\Component;
use yii\redis\Connection as Redis;
use yii\redis\Session;

/**
 * Class Emitter
 * @package common\sio
 *
 * @method IO broadcast()
 * @method IO volatile()
 * @method IO emit(string $event, mixed $args ...)
 * @method IO in(string $room)
 * @method IO to(string $room)
 * @method IO of(string $room)
 * @method boolean is(string $flag)
 */
class Connection extends Component
{
    public $redis = 'redis';
    public $keyPrefix = null;

    public function behaviors() {
        return [
            [
                'class' => IO::className(),
                'redis' => $this->redis,
                'keyPrefix' => $this->keyPrefix,
            ]
        ];
    }

    public function init() {
        if (is_string($this->redis)) {
            $this->redis = Yii::$app->get($this->redis);
        } else if (is_array($this->redis)) {
            if (empty($this->redis['class'])) {
                $this->redis['class'] = Connection::className();
            }

            $this->redis = Yii::createObject($this->redis);
        }

        parent::init();
    }
}