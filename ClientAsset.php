<?php

class ClientAsset extends \yii\web\AssetBundle {
    public $sourcePath = '@npm/socket.io-client/dist';

    public $js = [
        'socket.io.js',
    ];
}