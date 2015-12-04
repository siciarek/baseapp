<?php

namespace Application\MainBundle\Common;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LaafFrame implements ContainerAwareInterface {

    const TYPE_REQUEST = 'request';
    const TYPE_INFO = 'info';
    const TYPE_DATA = 'data';
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR = 'error';

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function getDataFrame($msg = null, $data = [], $warningOnEmptyData = false, $auth = null) {
        return $this->getFrame(self::TYPE_DATA, $msg, $data, $warningOnEmptyData, $auth);
    }

    public function getRequestFrame($msg = null, $data = null, $auth = null) {
        return $this->getFrame(self::TYPE_REQUEST, $msg, $data, false, $auth);
    }

    public function getInfoFrame($msg = null, $data = null, $auth = null) {
        return $this->getFrame(self::TYPE_INFO, $msg, $data, false, $auth);
    }

    public function getWarningFrame($msg = null, $data = null, $auth = null) {
        return $this->getFrame(self::TYPE_WARNING, $msg, $data, false, $auth);
    }

    public function getErrorFrame($msg = null, $data = null, $auth = null) {
        return $this->getFrame(self::TYPE_ERROR, $msg, $data, false, $auth);
    }

    protected function getFrame($type = self::TYPE_INFO, $msg = null, $data = null, $warningOnEmptyData = false, $auth = null) {

        $datetime = date('Y-m-d H:i:s');

        $frames = [
            self::TYPE_REQUEST => [
                'success' => true,
                'type' => self::TYPE_REQUEST,
                'datetime' => $datetime,
                'msg' => 'Request',
                'auth' => $auth,
                'data' => new \stdClass(),
            ],
            self::TYPE_INFO => [
                'success' => true,
                'type' => self::TYPE_INFO,
                'datetime' => $datetime,
                'msg' => 'OK',
                'auth' => $auth,
                'data' => new \stdClass(),
            ],
            self::TYPE_DATA => [
                'success' => true,
                'type' => self::TYPE_DATA,
                'datetime' => $datetime,
                'msg' => 'Data',
                'auth' => $auth,
                'data' => [
                    'items' => [],
                    'currentItemCount' => 0,
                    'itemsPerPage' => 0,
                    'startIndex' => 0,
                    'totalItems' => 0,
                    'pagingLinkTemplate' => null,
                    'pageIndex' => 1,
                    'totalPages' => 1,
                ],
            ],
            self::TYPE_WARNING => [
                'success' => false,
                'type' => self::TYPE_WARNING,
                'datetime' => $datetime,
                'msg' => 'Warning',
                'auth' => $auth,
                'data' => new \stdClass(),
            ],
            self::TYPE_ERROR => [
                'success' => false,
                'type' => self::TYPE_ERROR,
                'datetime' => $datetime,
                'msg' => 'Error',
                'auth' => $auth,
                'data' => new \stdClass(),
            ],
        ];

        if (!array_key_exists($type, $frames)) {
            throw new \Exception('Invalid LAAF frame type.');
        }

        $frame = $frames[$type];

        if ($msg !== null) {
            $frame['msg'] = $msg;
        }

        if ($data !== null and $type !== self::TYPE_DATA) {
            $frame['data'] = $data;
        }

        if ($auth === null) {
            unset($frame['auth']);
        } else {
            $frame['auth'] = $auth;
        }

        if ($type === self::TYPE_DATA) {

            if (!is_array($data)) {
                throw new \Exception('Array type data is required.');
            }

            $items = array_values($data);
            
            if($warningOnEmptyData and count($items) === 0) {
                $frame = $this->getWarningFrame('No data found.');
                return $frame;
            }
            
            $frame['data']['items'] = $items;
            $frame['data']['currentItemCount'] = count($items);
            $frame['data']['itemsPerPage'] = count($items);
            $frame['data']['totalItems'] = count($items);
        }

        return $frame;
    }

    /**
     * Gets the container
     *
     * @return ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function getContainer() {
        return $this->container;
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

}
