<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\PhpPm\Bridge;

use Cmf\PhpPm\Bootstrap\CustomCmfBootstrap;
use PHPPM\Bridges\BridgeInterface;
use React\Http\Request;
use React\Http\Response;

/**
 * This file is bridge for Custom CMF
 * Example:
 * <code>
 * ./bin/ppm start /path/to/CustomCMF/ --bridge="CustomCmf"
 * </code>
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class CustomCmfBridge implements BridgeInterface
{
    /** @var \Cmf\System\Application */
    protected $application;

    /**
     * @param string $appBootstrap
     * @param string $appEnv
     */
    public function bootstrap($appBootstrap, $appEnv)
    {
        $bootstrap = new CustomCmfBootstrap($appEnv);
        $this->application = $bootstrap->getApplication();
    }

    /**
     * @param \React\Http\Request $request
     * @param \React\Http\Response $response
     * @param array $postData
     */
    public function onRequest(Request $request, Response $response, array $postData)
    {
        if (null === ($app = $this->application)) {
            return;
        }

        $_POST = $postData;

        $_SERVER['SERVER_PROTOCOL'] = 'HTTP' . $request->getHttpVersion();
        $_SERVER['REQUEST_URI'] = $request->getPath();
        $_SERVER['SERVER_NAME'] = explode(':', $request->getHeaders()['Host'])[0];
        $_SERVER['REQUEST_METHOD'] = $request->getMethod();
        $_SERVER['REMOTE_ADDR'] = getenv('REMOTE_ADDR');

        try {
            $result = $this->application
                ->resetRequest()
                ->killWww()
                ->dispatch();
        } catch (\Exception $exception) {
            return;
        }

        $response->writeHead(200, []);
        $response->end($result);
    }
}
