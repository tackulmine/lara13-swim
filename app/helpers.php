<?php

use Carbon\Carbon;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

if (! function_exists('generateMediaSource')) {
    function generateMediaSource($mediaUrl, $suffix = '_200x200')
    {
        if (empty($mediaUrl)) {
            return;
        }

        $nameLength = strlen($mediaUrl);
        $ext = strrchr($mediaUrl, '.');
        $extLength = strlen($ext);
        // $filename = 'https://cdn.popmama.com/' . substr($mediaUrl, 0, ($nameLength - $extLength)) . $suffix . $ext;
        $filename = config('general.cdn_url').substr($mediaUrl, 0, ($nameLength - $extLength)).$suffix.$ext;

        return $filename;
    }
}

if (! function_exists('wordLimiter')) {
    function wordLimiter(string $text, int $limit = 10, string $suffix = '...')
    {
        return Str::words(strip_tags($text), $limit, $suffix);
    }
}

if (! function_exists('getDateFormatModel')) {
    function getDateFormatModel($date)
    {
        if (strtotime($date) <= 0) {
            return;
        } elseif ((strtotime(date('Y-m-d')) - strtotime($date)) / (60 * 60 * 24) <= 1) {
            $date = Carbon::parse($date);

            return '<time class="timeago" datetime="'.$date->format('c').'">
            '.$date->format('d-M-Y@H:ia').'
            </time>';
        } else {
            return Carbon::parse($date)->format('d M Y @ H:i:s');
        }

        /*return '<time class="timeago" datetime="' . optional($date)->format('c') . '">
    ' . optional($date)->format('d-M-Y@H:ia') . '
    </time>';
     */
    }
}

if (! function_exists('getUserFormatModel')) {
    function getUserFormatModel($name, $email)
    {
        if (empty($name)) {
            return;
        }

        return '<span data-toggle="tooltip" data-placement="auto top" title="'.$email.'">'.$name.'</span>';
    }
}

if (! function_exists('getQueryParams')) {
    function getQueryParams()
    {
        return request()->query() ? array_filter(Arr::except(request()->query(), ['_token', '_method'])) : [];
    }
}

if (! function_exists('getQueryHttpBuilder')) {
    function getQueryHttpBuilder($prefix = '?')
    {
        return request()->query() ? $prefix.http_build_query(getQueryParams()) : '';
    }
}

if (! function_exists('generateRandomCharacter')) {
    function generateRandomCharacter($count = 0)
    {
        if (empty($count)) {
            $count = 16;
        }

        $character_set_array = [];
        $character_set_array[] = ['count' => $count, 'characters' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'];
        $temp_array = [];
        foreach ($character_set_array as $character_set) {
            for ($i = 0; $i < $character_set['count']; $i++) {
                $temp_array[] = $character_set['characters'][rand(0, strlen($character_set['characters']) - 1)];
            }
        }

        shuffle($temp_array);

        return implode('', $temp_array);
    }
}

if (! function_exists('cleanWhiteSpace')) {
    function cleanWhiteSpace($string)
    {
        if (empty($string)) {
            return $string;
        }

        $buffer = trim($string);
        $buffer = preg_replace('/\s+/', ' ', $buffer);
        $buffer = preg_replace('/\s*(?:(?=[=\-\+\|%&\*\)\[\]\{\};:\,\.\<\>\!\@\#\^`~]))/', '', $buffer);
        $buffer = preg_replace('/(?:(?<=[=\-\+\|%&\*\)\[\]\{\};:\,\.\<\>\?\!\@\#\^`~]))\s*/', '', $buffer);
        // $buffer = preg_replace('/([^a-zA-Z0-9\s\-=+\|!@#$%^&*()`~\[\]{};:\'",<.>\/?])\s+([^a-zA-Z0-9\s\-=+\|!@#$%^&*()`~\[\]{};:\'",<.>\/?])/', '$1$2', $buffer);

        return $buffer;
    }
}

if (! function_exists('cleanPhoneNumber')) {
    function cleanPhoneNumber($string)
    {
        if (empty($string)) {
            return $string;
        }

        return str_replace(['-', '+', '_', ' '], '', $string);
    }
}

if (! function_exists('humanize')) {
    /**
     * Humanize.
     *
     * Takes multiple words separated by the separator and changes them to spaces
     *
     * @param  string  $str  Input string
     * @param  string  $separator  Input separator
     * @return string
     */
    function humanize($str, $separator = '_')
    {
        return ucwords(preg_replace('/['.preg_quote($separator).']+/', ' ', trim(false ? mb_strtolower($str) : strtolower($str))));
    }
}

if (! function_exists('parseBetweenDate')) {
    function parseBetweenDate($date1, $date2, $monthFormat = 'M')
    {
        $date1 = ! is_int($date1) ? strtotime($date1) : $date1;
        $date2 = ! is_int($date2) ? strtotime($date2) : $date2;
        // dd($date1, $date2);

        if (date('Y', $date1) != date('Y', $date2)) {
            return Carbon::createFromTimestamp($date1)->format("j {$monthFormat} Y").
                ' &ndash; '.
                Carbon::createFromTimestamp($date2)->format("j {$monthFormat} Y");
        }

        if (date('m', $date1) != date('m', $date2)) {
            return Carbon::createFromTimestamp($date1)->format("j {$monthFormat}").
                ' &ndash; '.
                Carbon::createFromTimestamp($date2)->format("j {$monthFormat} Y");
        }

        if (date('j', $date1) != date('j', $date2)) {
            return date('j', $date1).
                ' &ndash; '.
                Carbon::createFromTimestamp($date2)->format("j {$monthFormat} Y");
        }

        return Carbon::createFromTimestamp($date2)->format("j {$monthFormat} Y");
    }
}

if (! function_exists('parseBetweenDateCustom')) {
    function parseBetweenDateCustom($date1, $date2, $customFormat = 'F Y')
    {
        $date1 = ! is_int($date1) ? strtotime($date1) : $date1;
        $date2 = ! is_int($date2) ? strtotime($date2) : $date2;
        // dd($date1, $date2);

        if (date('Y', $date1) != date('Y', $date2)) {
            return Carbon::createFromTimestamp($date1)->format($customFormat).
                ' &ndash; '.
                Carbon::createFromTimestamp($date2)->format($customFormat);
        } else {
            if (date('m', $date1) != date('m', $date2)) {
                $customFormatNoYear = trim(str_replace('Y', '', $customFormat));

                return Carbon::createFromTimestamp($date1)->format($customFormatNoYear).
                    ' &ndash; '.
                    Carbon::createFromTimestamp($date2)->format($customFormat);
            } else {
                return (
                    stristr($customFormat, 'j') || stristr($customFormat, 'd')
                    ? date('j', $date1).' &ndash; '
                    : ''
                ).
                    Carbon::createFromTimestamp($date2)->format($customFormat);
            }
        }
    }
}

if (! function_exists('parseBetweenDateTime')) {
    function parseBetweenDateTime($date1, $date2, $monthFormat = 'M')
    {
        $date1 = ! is_int($date1) ? strtotime($date1) : $date1;
        $date2 = ! is_int($date2) ? strtotime($date2) : $date2;
        // dd($date1, $date2);

        if (date('Y', $date1) != date('Y', $date2)) {
            return Carbon::createFromTimestamp($date1)->format("j {$monthFormat} Y @H:ia").
                ' &ndash; '.
                Carbon::createFromTimestamp($date2)->format("j {$monthFormat} Y @H:ia");
        } else {
            if (date('Y-m-d', $date1) == date('Y-m-d', $date2)) {
                return Carbon::createFromTimestamp($date1)->format("j {$monthFormat} Y @H:ia").
                    ' &ndash; '.
                    Carbon::createFromTimestamp($date2)->format('@H:ia');
            } else {
                if (date('H:i:s', $date1) != date('H:i:s', $date2)) {
                    return Carbon::createFromTimestamp($date1)->format("j {$monthFormat} Y @H:ia").
                        ' &ndash; '.
                        Carbon::createFromTimestamp($date2)->format("j {$monthFormat} Y @H:ia");
                } else {
                    if (date('m', $date1) != date('m', $date2)) {
                        return Carbon::createFromTimestamp($date1)->format("j {$monthFormat}").
                            ' &ndash; '.
                            Carbon::createFromTimestamp($date2)->format("j {$monthFormat} Y @H:ia");
                    } else {
                        return date('j', $date1).
                            ' &ndash; '.
                            Carbon::createFromTimestamp($date2)->format("j {$monthFormat} Y @H:ia");
                    }
                }
            }
        }
    }
}

if (! function_exists('uploadAvatar')) {
    function uploadAvatar(UploadedFile $uploadedFile, $newFileName)
    {
        // $uploadedFileName = $uploadedFile->getClientOriginalName();
        // $oriFileName = Str::slug(pathinfo($uploadedFileName, PATHINFO_FILENAME));
        // // $oriFileExt = pathinfo($uploadedFileName, PATHINFO_EXTENSION);
        $oriFileExt = strtolower($uploadedFile->extension());

        $fileName = $newFileName.'.'.$oriFileExt;
        $thumbFileName = $newFileName.'_3x4'.'.'.$oriFileExt;

        // directory
        $directory = 'avatars';

        // save original file
        $uploadedFile->storeAs($directory, $fileName, 'shared');

        // resize file
        Image::make($uploadedFile)
            ->fit(300, 400) // 3/4
            ->save(config('filesystems.disks.shared.root')."/{$directory}/".$thumbFileName);

        return "{$directory}/".$thumbFileName;
    }
}

if (! function_exists('uploadEventPhoto')) {
    function uploadEventPhoto(UploadedFile $uploadedFile, $newFileName)
    {
        $oriFileExt = strtolower($uploadedFile->extension());

        $fileName = $newFileName.'.'.$oriFileExt;
        $thumbFileName = $newFileName.'_100x100'.'.'.$oriFileExt;

        // directory
        $directory = 'events';

        // save original file
        $uploadedFile->storeAs($directory, $fileName, 'shared');

        // resize file
        Image::make($uploadedFile)
            ->fit(100, 100) // 1/1
            ->save(config('filesystems.disks.shared.root')."/{$directory}/".$thumbFileName);

        return "{$directory}/".$thumbFileName;
    }
}

if (! function_exists('uploadEventRegistrationFile')) {
    function uploadEventRegistrationFile(UploadedFile $uploadedFile, $newFileName, $suffixDirectory = '')
    {
        $oriFileExt = strtolower($uploadedFile->extension());

        $fileName = $newFileName.'.'.$oriFileExt;

        // directory
        $directory = 'event-registrations';

        // suffix directory
        if (! empty(trim($suffixDirectory))) {
            $directory .= DIRECTORY_SEPARATOR.$suffixDirectory;
        }

        // save original file
        $uploadedFile->storeAs($directory, $fileName, 'shared');

        if (in_array($oriFileExt, ['jpg', 'jpeg', 'png'])) {
            // resize file 1000px
            Image::make($uploadedFile)
                ->resize(1000, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(config('filesystems.disks.shared.root')."/{$directory}/".$newFileName.'_1000'.'.'.$oriFileExt);
            // resize file 200px
            Image::make($uploadedFile)
                ->resize(200, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(config('filesystems.disks.shared.root')."/{$directory}/".$newFileName.'_200'.'.'.$oriFileExt);
            // resize n crop file
            Image::make($uploadedFile)
                ->fit(100, 100) // 1/1
                ->save(config('filesystems.disks.shared.root')."/{$directory}/".$newFileName.'_100x100'.'.'.$oriFileExt);
        }

        return "{$directory}/".$fileName;
    }
}

if (! function_exists('uploadMemberFile')) {
    function uploadMemberFile(UploadedFile $uploadedFile, $newFileName, $suffixDirectory = '')
    {
        $oriFileExt = strtolower($uploadedFile->extension());

        $fileName = $newFileName.'.'.$oriFileExt;

        // directory
        $directory = 'members';

        // suffix directory
        if (! empty(trim($suffixDirectory))) {
            $directory .= DIRECTORY_SEPARATOR.$suffixDirectory;
        }

        // save original file
        $uploadedFile->storeAs($directory, $fileName, 'shared');

        if (in_array($oriFileExt, ['jpg', 'jpeg', 'png'])) {
            // resize file 1000px
            Image::make($uploadedFile)
                ->resize(1000, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(config('filesystems.disks.shared.root')."/{$directory}/".$newFileName.'_1000'.'.'.$oriFileExt);
            // resize file 200px
            Image::make($uploadedFile)
                ->resize(200, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(config('filesystems.disks.shared.root')."/{$directory}/".$newFileName.'_200'.'.'.$oriFileExt);
            // resize n crop file
            Image::make($uploadedFile)
                ->fit(100, 100) // 1/1
                ->save(config('filesystems.disks.shared.root')."/{$directory}/".$newFileName.'_100x100'.'.'.$oriFileExt);
        }

        return "{$directory}/".$fileName;
    }
}

if (! function_exists('getFileCustomSize')) {
    function getFileCustomSize($fileUrl = '', $size = 1000)
    {
        if (empty($fileUrl)) {
            return '';
        }

        $extractFileName = explode('.', basename($fileUrl), 2);
        $oriFileExt = end($extractFileName);

        $newFileName = $extractFileName[0]."_{$size}.{$oriFileExt}";

        $baseFileUrl = str_replace(basename($fileUrl), '', $fileUrl);

        return $baseFileUrl.$newFileName;
    }
}

if (! function_exists('getAvatar')) {
    function getAvatar($avatar, $email, $size = 200)
    {
        if (! empty($avatar)) {
            return $avatar;
        }

        return Gravatar::get($email ?? 'email@example.com', ['size' => $size]);
    }
}

if (! function_exists('getGenders')) {
    function getGenders()
    {
        return ['male' => 'Laki-laki', 'female' => 'Perempuan'];
    }
}

if (! function_exists('parseGender')) {
    function parseGender($gender)
    {
        $genderText = '';
        switch ($gender) {
            case 'male':
                $genderText = 'Putra';
                break;
            case 'female':
                $genderText = 'Putri';
                break;
            default:
                $genderText = '-';
                break;
        }

        return $genderText;
    }
}

if (! function_exists('parseGenderAbbr')) {
    function parseGenderAbbr($gender)
    {
        $genderText = '';
        switch ($gender) {
            case 'male':
                $genderText = 'L';
                break;
            case 'female':
                $genderText = 'P';
                break;
            case 'mix':
                $genderText = 'Mix';
                break;
            default:
                $genderText = '-';
                break;
        }

        return $genderText;
    }
}

if (! function_exists('parseGenderText')) {
    function parseGenderText($gender)
    {
        $genderText = '';
        switch ($gender) {
            case 'male':
                $genderText = 'Laki-laki';
                break;
            case 'female':
                $genderText = 'Perempuan';
            case 'mix':
                $genderText = 'Mix';
                break;
            default:
                $genderText = '-';
                break;
        }

        return $genderText;
    }
}

if (! function_exists('parsePointToInt')) {
    function parsePointToInt($point)
    {
        if (empty($point)) {
            return '';
        }

        return str_replace([':', '.', ','], '', $point);
    }
}

if (! function_exists('parsePointToDecimal')) {
    function parsePointToDecimal($point, $decLen = 2)
    {
        if (empty($point)) {
            return '';
        }

        $pointRaw = str_replace([':', ','], '', $point);

        if (! Str::contains($pointRaw, '.')) {
            $decimalDivs = [
                1 => 10,
                2 => 100,
                3 => 1000,
            ];
            $pointRaw = $pointRaw / $decimalDivs[$decLen];
        }

        return number_format($pointRaw, $decLen, '.', '');
    }
}

if (! function_exists('normalizePointMinSecMilli')) {
    function normalizePointMinSecMilli($point, $decLen = 2)
    {
        if (empty($point)) {
            return '';
        }

        // parsing point
        $pointDecs = explode('.', $point, 2);
        $pointMinSec = explode(':', $pointDecs[0], 2);
        $pointMilliSec = end($pointDecs);
        $pointMin = $pointMinSec[0];
        $pointSec = end($pointMinSec);

        if (intval($pointSec) >= 60) {
            $pointMin = intval($pointMin) + floor(intval($pointSec) / 60);
            $pointSec = intval($pointSec) % 60;
        }

        return str_pad($pointMin, 2, '0', STR_PAD_LEFT)
            .':'.str_pad($pointSec, 2, '0', STR_PAD_LEFT)
            .'.'.str_pad($pointMilliSec, $decLen, '0', STR_PAD_RIGHT);
    }
}

if (! function_exists('normalizePoint')) {
    function normalizePoint($point, $decLen = 2)
    {
        if (empty($point)) {
            return '00:00.'.str_pad('', $decLen, '0', STR_PAD_RIGHT);
        }

        // cleaning up point
        $point = str_replace([':', ','], ['', '.'], $point);

        // formatting point to dec
        $pointDec = number_format(floatval($point), $decLen, '.', '');

        // normalize point become 0000.00 or 0000.000
        // adding 0 to the left if less than 4 digits before decimal
        $pointStr = str_pad($pointDec, ($decLen == 2 ? 7 : 8), '0', STR_PAD_LEFT);

        // normalize point become 00:00.00 or 00:00.000
        $stdPoint = substr_replace($pointStr, ':', 2, 0);

        $stdPoint = normalizePointMinSecMilli($stdPoint, $decLen);

        return $stdPoint;
    }
}

if (! function_exists('normalizePoints')) {
    function normalizePoints(array $points)
    {
        // echo implode(' | ', $points);
        // echo "\r\n";
        $parsedPoints = [];
        foreach ($points as $point) {
            $parsedPoints[] = normalizePoint($point);
        }

        // echo implode("\r\n", $parsedPoints);

        return $parsedPoints;
    }
}

if (! function_exists('numberFormatIdn')) {
    function numberFormatIdn($number, $decimalLength = 0, $decimalPoint = ',', $separator = '.')
    {
        // $number = 1234.5678;
        $formattedNumber = number_format($number, $decimalLength, $decimalPoint, $separator);

        return $formattedNumber; // Outputs: 1.234,57
    }
}

if (! function_exists('strSquish')) {
    function strSquish($value)
    {
        return preg_replace('~(\s|\x{3164}|\x{1160})+~u', ' ', preg_replace('~^[\s\x{FEFF}]+|[\s\x{FEFF}]+$~u', '', $value));
    }
}

if (! function_exists('generateLaneOrder')) {
    /**
     * Generate lane order center-out for any number of lanes.
     *
     * Example:
     * 6 lanes => [3,4,2,5,1,6]
     * 8 lanes => [4,5,3,6,2,7,1,8]
     */
    function generateLaneOrder(int $totalLanes): array
    {
        $centerLeft = intdiv($totalLanes, 2);
        $centerRight = $centerLeft + 1;

        $order = [];

        // GENAP → mulai dari kedua tengah: L, R, L-1, R+1, ...
        // GANJIL → mulai dari tengah saja: C, L, R, L-1, R+1, ...
        if ($totalLanes % 2 === 0) {
            // genap
            $left = $centerLeft;
            $right = $centerRight;
            while (count($order) < $totalLanes) {
                if ($left >= 1) {
                    $order[] = $left;
                    $left--;
                }
                if ($right <= $totalLanes) {
                    $order[] = $right;
                    $right++;
                }
            }
        } else {
            // ganjil (misal 7 -> mulai dari 4 lalu 3,5,2,6,1,7)
            $center = $centerRight;
            $order[] = $center;

            $left = $center - 1;
            $right = $center + 1;

            while (count($order) < $totalLanes) {
                if ($left >= 1) {
                    $order[] = $left;
                    $left--;
                }
                if ($right <= $totalLanes) {
                    $order[] = $right;
                    $right++;
                }
            }
        }

        return $order;
    }
}

// if (! function_exists('generateHeats')) {
//     function generateHeats($totalLanes = 6)
//     {
//         // Generate lane order dynamically
//         return generateLaneOrder($totalLanes);
//     }
// }

if (! function_exists('getRelegions')) {
    function getRelegions()
    {
        return [
            'islam' => 'Islam',
            'protestan' => 'Kristen Protestan',
            'katolik' => 'Kristen Katolik',
            'hindu' => 'Hindu',
            'buddha' => 'Buddha',
            'konghucu' => 'Konghucu',
        ];
    }
}

if (! function_exists('getRelegionNameBySlug')) {
    function getRelegionNameBySlug($slug)
    {
        $relegions = getRelegions();

        return $relegions[$slug] ?? null;
    }
}

if (! function_exists('getEducations')) {
    function getEducations()
    {
        return [
            'paud' => 'PAUD',
            'tk' => 'TK',
            'sd' => 'SD',
            'smp' => 'SMP',
            'sma' => 'SMA',
            'diploma' => 'Diploma',
            'sarjana' => 'Sarjana',
            'lainnya' => 'Lainnya',
        ];
    }
}

if (! function_exists('getEducationNameBySlug')) {
    function getEducationNameBySlug($slug)
    {
        $educations = getEducations();

        return $educations[$slug] ?? null;
    }
}

if (! function_exists('extractNumbers')) {
    function extractNumbers($string)
    {
        // Pattern to match one or more digits, optionally followed by a decimal part
        $pattern = '/\\d+(\\.\\d+)?/';
        preg_match_all($pattern, $string, $matches);

        // Returns an array of all matched numbers
        return $matches[0];
    }
}
