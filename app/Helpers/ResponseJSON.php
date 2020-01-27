 
 <?php
/**
 * resolving JsonResponse
 *
 * @param object $args
 * can be:
 * @param string $translate
 * @param string? $attribute
 * @param int $httpStatusCode
 * @return JsonResponse
 *
 */
function SEND_SUCCESS(object $args)
{
    if (!isset($args->httpStatusCode)) {
        $args->httpStatusCode = \HttpStatusCode::HTTP_OK;
    }
    return send($args);
}

function SEND_ERROR(object $args)
{
    if (!isset($args->httpStatusCode)) {
        $args->httpStatusCode = \HttpStatusCode::HTTP_BAD_REQUEST;
    }
    return send($args);
}

function send(object $args) {
    return response()->json([
        'message' =>
            isset($args->attribute)
                ? trans($args->translate, [
                    'attribute' => trans('success.attributes.'.$args->attribute)
                ])
                : trans($args->translate)
    ], $args->httpStatusCode);
} 
