<?php

namespace App\Enums;

enum HoquJobStatus: string
{
  case New = 'new';
  case Progress = 'progress';
  case Done = 'done';
}
