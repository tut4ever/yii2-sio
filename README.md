# yii2-sio
socket.io emitter

# Install
```
composer require quangthinh/yii2-sio
```

Add to Component section at config file

```
        'sio' => [
            'class' => 'quangthinh\yii\sio\Connection',
            'redis' => [
                'class' => 'yii\redis\Connection',
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 0,
            ],
        ],
```
# Usage
port of socket.io-emitter

```
Yii::$app->sio
  ->to("ROOM_ID")
  ->emit("EVENT", "MSG", ...)
```
