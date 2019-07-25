<?php

namespace ApiVideo\StatusPage\Exception;

use OutOfBoundsException;

final class NotFound extends OutOfBoundsException implements ClientExceptionInterface
{
}
