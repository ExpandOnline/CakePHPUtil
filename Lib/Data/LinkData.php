<?php

/**
 * Class LinkData
 */
class LinkData extends Data {

/**
 * @var string $_url
 */
	protected $_url = null;

/**
 * @var string $_anchor
 */
	protected $_anchor = null;

/**
 * @var string $_icon
 */
	protected $_icon = null;

/**
 * @var string $_icon
 */
	protected $_confirmation = null;

	/**
	 * @var bool
	 */
	protected $_newWindow = false;

/**
 * @param string $url
 * @param string $anchor
 * @param string $icon
 * @param string $confirmation
 * @param bool $newWindow
 */
	public function __construct($url, $anchor = null, $icon = null, $confirmation = null, $newWindow = false) {
		$this->_url = $url;
		$this->_anchor = $anchor;
		$this->_icon = $icon;
		$this->_confirmation = $confirmation;
		$this->_newWindow = $newWindow;
		if (is_null($anchor)) {
			$this->_anchor = $this->_url;
		}
	}

/**
 * @return string
 */
	public function getType() {
		return Data::LINK;
	}

/**
 * @return array|string
 */
	public function getUrl() {
		return $this->_url;
	}

	/**
	 * @return string
	 */
	public function getStringUrl() {
		if (is_array($this->_url)) {
			return Router::url($this->_url);
		}
		return $this->_url;
	}

/**
 * @return string
 */
	public function getAnchor() {
		return $this->_anchor;
	}

/**
 * @return string
 */
	public function getIcon() {
		return $this->_icon;
	}

/**
 * @return null|string
 */
	public function getConfirmation() {
		return $this->_confirmation;
	}

/**
 * @return bool
 */
	public function hasIcon() {
		return !is_null($this->_icon);
	}

/**
 * @return bool
 */
	public function hasAnchor() {
		return !is_null($this->_anchor);
	}


	/**
	 * @return null|string
	 */
	public function getNewWindow() {
		return $this->_newWindow;
	}
}