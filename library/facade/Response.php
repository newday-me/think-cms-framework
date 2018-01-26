<?php

namespace cms\facade;

use think\Facade;

/**
 * @see \cms\Response
 * @mixin \cms\Response
 * @method \cms\Response api(int $code, string $msg = '', mixed $data = [], array $header = [], array $options = []) static 接口返回
 * @method \cms\Response redirect(string $url, bool $build = true, array $header = [], array $options = []) static 跳转链接
 * @method \cms\Response xml(string $xml, array $header = [], array $options) static 返回XML
 * @method \cms\Response json(array $json, array $header = [], array $options = []) static 返回JSON
 * @method \cms\Response data(mixed $data, string $type, int $code, array $header = [], array $options = []) static 返回结果
 **/
class Response extends Facade
{
}