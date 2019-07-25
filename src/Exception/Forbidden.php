<?php

namespace ApiVideo\StatusPage\Exception;

use DomainException;

final class Forbidden extends DomainException implements ClientExceptionInterface
{
}
