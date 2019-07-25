<?php

namespace ApiVideo\StatusPage\Exception;

use DomainException;

final class Unauthorized extends DomainException implements ClientExceptionInterface
{
}
