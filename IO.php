<?php
/**
 * Created by PhpStorm.
 * User: vuquangthinh
 * Date: 1/7/2016
 * Time: 10:45 AM
 */

namespace quangthinh\yii2\sio;

use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;

require __DIR__ . DIRECTORY_SEPARATOR . 'msgpack.php';

/**
 * Class Emitter
 * @package vuquangthinh\yii2\sio
 */
class IO extends Behavior
{
	const EMIT_EVENT = 2;
	const FLAG_BROADCAST = 'broadcast';
	const FLAG_VOLATINE = 'volatile';

	public $redis;
	public $keyPrefix;

	private $_rooms;
	private $_flags;
	private $_nsp;

	public function init() {
		
		if (!is_callable([$this, 'publish'])) {
			throw new InvalidConfigException("Invalid IO::redis");
		}

		if (!$this->redis instanceof Connection) {
		}

		if ($this->keyPrefix === null) {
			$this->keyPrefix = 'socket.io';
		}

		$this->cleanOptions();

		parent::init();
	}

	public function broadcast() {
		$this->_flags[self::FLAG_BROADCAST] = true;
		return $this;
	}

	public function volatile() {
		$this->_flags[self::FLAG_VOLATINE] = true;
		return $this;
	}

	public function emit() {
		$args = func_get_args();

		$packet = [];
		$packet['type'] = self::EMIT_EVENT;
		$packet['data'] = $args;
		$packet['nsp'] = '/';

		$channel = $this->keyPrefix . '#' . $this->_nsp . '#';
		$this->redis->publish($channel, msgpack_pack([uniqid(), $packet, [
			'rooms' => array_keys($this->_rooms),
			'flags' => array_keys($this->_flags),
		]]));

		$this->cleanOptions();
	}

	private function cleanOptions() {
		$this->_flags = [];
		$this->_rooms = [];
		$this->_nsp = '/';
	}

	public function to($room) {
		$this->in($room);
		return $this;
	}

	public function in($room) {
		if (empty($this->_rooms[$room])) {
			$this->_rooms[$room] = true;
		}

		return $this;
	}

	public function of($nsp) {
		$this->_nsp = $nsp;
		return $this;
	}

	/**
	 * Check Flag
     * @param string $flag
	 * @return boolean
	 */
	public function is($flag) {
		return isset($this->_flags[$flag]) ? $this->_flags[$flag] : false;
	}
}