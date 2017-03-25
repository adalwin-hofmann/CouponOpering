<?php
/**
  * Geometry Helper
  *
  * Helper for creating mysql spatial objects.
  *
  * @author  Caleb Beery <cbeery@saveoneverything.com>
  *
  */

class GeometryHelper
{
    /**
     * Return the mysql query syntax for creating point geometry.
     * @static
     *
     * @param float $pox_x
     * @param float $pox_y
     * @param string $format = 'raw'
     * @return mixed The mysql query syntax required to build point geometry.
     */
    public static function point($pos_x, $pos_y, $format = 'raw') 
    {
        if($format == 'raw')
        {
            return DB::raw("GEOMFROMTEXT(CONCAT('POINT(', ".$pos_x.", ' ', ".$pos_y.", ')'))");
        }
        else
        {
            return "GEOMFROMTEXT(CONCAT('POINT(', ".$pos_x.", ' ', ".$pos_y.", ')'))";
        }
    }

    /**
     * Return the mysql query syntax required to build polygon geometry.
     * @static
     *
     * @param float $pox_x Latitude of center point.
     * @param float $pox_y Longitude of center point.
     * @param int $corners
     * @param float $radius Given in miles.
     * @return mixed The mysql query syntax required to build polygon geometry.
     */
    public static function polygon($pos_x, $pos_y, $corners, $radius)
    {
        if($corners < 3)
        {
            return DB::raw('NULL');
        }

        $first_point = (string)($pos_x+$radius/60)." ".$pos_y;
        $points = $first_point.",";
        for($i = 1; $i < $corners; $i++)
        {
            $arc = deg2rad(360 / $corners * $i);
            $pos_a = $pos_x + (cos($arc) * $radius / 69.047);
            $pos_b = $pos_y + (sin($arc) * $radius / (cos(deg2rad($pos_x + (cos($arc) * $radius / pow(69.047, 2)))) * 69.047));
            $points .= (string)($pos_a." ".$pos_b.",");
        }

        return DB::raw("GEOMFROMTEXT(CONCAT('POLYGON((', '".$points."', '".$first_point."', '))'))");
    }

    /**
     * Return the distance in miles between two sets of latitude/longitude points.
     * @static
     *
     * @param float $x_a
     * @param float $y_a
     * @param float $x_b
     * @param float $y_b
     * @return float The distance in miles.
     */
    public static function getDistance($x_a, $y_a, $x_b, $y_b)
    {
        $dist = (3959 * acos(cos(deg2rad($x_a)) * cos(deg2rad($x_b)) 
               * cos( deg2rad($y_b) - deg2rad($y_a)) + sin(deg2rad($x_a)) 
               * sin( deg2rad($x_b))));

        return $dist;
    }
}