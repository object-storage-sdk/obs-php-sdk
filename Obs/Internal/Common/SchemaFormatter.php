<?php

/**
  * Licensed under the Apache License, Version 2.0 (the "License"); you may not use
 * this file except in compliance with the License.  You may obtain a copy of the
 * License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed
 * under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR
 * CONDITIONS OF ANY KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations under the License.
 *
 */

namespace Obs\Internal\Common;

class SchemaFormatter
{
    protected static $utcTimeZone;

    public static function format($fmt, $value)
    {
        if ($fmt === 'date-time') {
            return SchemaFormatter::formatDateTime($value);
        }

        if ($fmt === 'data-time-http') {
            return SchemaFormatter::formatDateTimeHttp($value);
        }

        if ($fmt === 'data-time-middle') {
            return SchemaFormatter::formatDateTimeMiddle($value);
        }

        if ($fmt === 'date') {
            return SchemaFormatter::formatDate($value);
        }

        if ($fmt === 'timestamp') {
            return SchemaFormatter::formatTimestamp($value);
        }

        if ($fmt === 'boolean-string') {
            return SchemaFormatter::formatBooleanAsString($value);
        }

        return $value;
    }

    public static function formatDateTimeMiddle($dateTime)
    {
        if (is_string($dateTime)) {
            $dateTime = new \DateTime($dateTime);
        }

        if ($dateTime instanceof \DateTime) {
            return $dateTime->format('Y-m-d\T00:00:00\Z');
        }
        return null;
    }

    public static function formatDateTime($value)
    {
        return self::dateFormatter($value, 'Y-m-d\TH:i:s\Z');
    }

    public static function formatDateTimeHttp($value)
    {
        return self::dateFormatter($value, 'D, d M Y H:i:s \G\M\T');
    }

    public static function formatDate($value)
    {
        return self::dateFormatter($value, 'Y-m-d');
    }

    public static function formatTime($value)
    {
        return self::dateFormatter($value, 'H:i:s');
    }

    public static function formatBooleanAsString($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
    }

    public static function formatTimestamp($value)
    {
        return (int) self::dateFormatter($value, 'U');
    }

    private static function dateFormatter($dt, $fmt)
    {
        if (is_numeric($dt)) {
            return gmdate($fmt, (int) $dt);
        }

        if (is_string($dt)) {
            $dt = new \DateTime($dt);
        }

        if ($dt instanceof \DateTime) {
            if (!SchemaFormatter::$utcTimeZone) {
                SchemaFormatter::$utcTimeZone = new \DateTimeZone('UTC');
            }

            return $dt->setTimezone(SchemaFormatter::$utcTimeZone)->format($fmt);
        }

        return null;
    }
}
