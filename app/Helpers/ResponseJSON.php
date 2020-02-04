 
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
function SEND_RESPONSE($args)
{
    if (! isset($args->translate)) {
        return response()->json($args);
    }

    return response()->json([
        'message' =>
            isset($args->attribute)
                ? trans($args->translate, [
                    'attribute' => trans('success.attributes.'.$args->attribute)
                ])
                : trans($args->translate)
    ], $args->httpStatusCode);
}
